<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle($request, Closure $next)
  {
    // jika tidak ada maka redirect ke login
    if (!JWTAuth::parseToken()->check()) {
      return response()->json(['success' => false, 'message' => 'Token not provided'], 401);
    }
    
    try {
      $user = JWTAuth::parseToken()->authenticate();
      // you can store user in request if needed
      $request->merge(['jwt_user' => $user]);
    } catch (TokenExpiredException $e) {
      return response()->json(['success' => false, 'message' => 'Token expired'], 401);
    } catch (TokenInvalidException $e) {
      return response()->json(['success' => false, 'message' => 'Token invalid'], 401);
    } catch (JWTException $e) {
      return response()->json(['success' => false, 'message' => 'Token absent'], 401);
    }

    return $next($request);
  }
}
