import { UserData, UserResponse } from '@/types/user';
import { axiosInstance } from '@/utils/axios';
import { useRouter } from 'vue-router';

export const getCheckUser = async () => {
  const router = useRouter();  
  
  try {
    // contoh: jika axios mengembalikan { data: { message, data: { ... } } }
    const res = await axiosInstance.get<UserResponse>('/user');
    // fungsi mengembalikan user data langsung (sesuai implementasimu sebelumnya)
    console.log('API getCheckUser response:', res.data.data);
    return res.data.data;
  } catch (error) {
    router.push('/login');
    console.error('Error fetching user data:', error);
    throw error;
  }
};

