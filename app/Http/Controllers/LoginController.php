<?php

namespace App\Http\Controllers;

use auth;
use App\Models\Login;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Firebase\JWT\JWT; // pastikan sudah install via composer require firebase/php-jwt

class LoginController extends Controller
{
    public function login(Request $request)
    {
      
        // Pastikan variabel di-assign sebelum digunakan
        $username = $request->input('user');
        $password = $request->input('pass');
        
        // Validasi input
        if (!$username || !$password) {
            return response()->json([
                'success' => false,
                'message' => 'Username and password are required'
            ], 400);
        }

        // Cek user login menggunakan model Login (db2)
        $res = Login::cekLogin($username);

        // Ambil user dari Auth langsung sebagai object
        $user = Auth()->user();

        // Jika Auth tidak mengembalikan user, gunakan hasil query manual
        if (!$user && $res && count($res) > 0) {
            $user = (object) $res[0];
        }

        if ($user) {
            // Cek password
            $isPasswordValid = false;
            if (isset($user->password)) {
                // $isPasswordValid = crypt($password, $user->password) == $user->password;
                // dd($password, $user->password);
                $isPasswordValid = $password === $user->password;
            }

            if ($isPasswordValid) {
                $nama = $user->nm_depan ?? ($user->nama ?? '');
                if (!empty($user->nm_belakang)) {
                    $nama .= ' ' . $user->nm_belakang;
                }

                $userData = [
                    'id' => $user->id ?? $user->username,
                    'username' => $user->username,
                    'nama' => Str::title($nama),
                    'cabang' => $user->fk_cabang_user ?? null,
                    'jabatan' => $user->nm_jabatan ?? null,
                    'idgrup' => $user->kd_jabatan ?? ($user->id_jabatan ?? null),
                ];

                // Buat JWT token manual jika bukan model User Laravel
                $token = null;
                try {
                    $payload = [
                        'iss' => config('app.url'),
                        'iat' => time(),
                        'exp' => time() + (config('jwt.ttl') * 60),
                        'sub' => $userData['id'], // tambahkan claim 'sub'
                        'user' => $userData
                    ];
                    $jwtSecret = env('JWT_SECRET');
                    $token = JWT::encode($payload, $jwtSecret, 'HS256');
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Could not create token: ' . $e->getMessage()
                    ], 500);
                }

                $datasession = array_merge($userData, ['status' => 'login']);
                session()->put('auth', $datasession);

                $redirectUrl = ($userData['idgrup'] === 'JBT-032') ? '/lms' : '/lms';

                return response()->json([
                    'success' => true,
                    'user' => $userData,
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                    'redirect' => $redirectUrl
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username/Password Salah!'
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Username/Password Salah!'
            ], 401);
        }

        // Jika ingin menggunakan Auth Laravel (jika model User sudah terdaftar di config/auth.php)
        // $credentials = ['username' => $username, 'password' => $password];
        // if (Auth()->attempt($credentials)) {
        //     $user = Auth()->user();
        // } else {
        //     // fallback ke login manual
        //     $res = Login::cekLogin($username);
        //     if ($res && count($res) > 0) {
        //         $user = (object) $res[0];
        //     }
        // }
    }

    
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            session()->forget('auth');
            
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout, please try again'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
            
            return response()->json([
                'success' => true,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token cannot be refreshed'
            ], 401);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token is invalid'
            ], 401);
        }
    }
}