export interface UserData {
  cabang: string;
  id: number;
  idgrup: string;
  jabatan: string;
  nama: string;
  username: string;
}

export interface UserResponse {
  message: string;
  data: UserData;
}


export interface User {
  id?: string;
  name?: string;
  email?: string;
  // tambahkan field lain sesuai API
};

export interface LoginPayload {
  user: string;
  pass: string;
  rememberMe?: boolean;
};