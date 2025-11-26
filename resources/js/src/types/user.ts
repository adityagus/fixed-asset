export interface UserData {
  cabang: string | null;
  id: string | number | null;
  idgrup: string | null;
  jabatan: string | null;
  nama: string | null;
  username: string | null;
}

export interface UserResponse {
  message: string;
  data: UserData;
}