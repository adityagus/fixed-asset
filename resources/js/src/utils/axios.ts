import axios from 'axios';
import { useAppStore } from '@/stores/index'

const baseUrl = import.meta.env.VITE_API_URL; // Ganti dengan URL dasar API Anda
export const axiosInstance = axios.create({
  baseURL: baseUrl,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
});

// handle error

axiosInstance.interceptors.request.use(
  (config) => {
    const appStore = useAppStore(); // atau dari store Pinia
    if (appStore.token) {
      config.headers.Authorization = `Bearer ${appStore.token}`;
    }
    return config;
  }
);

// Tambahkan interceptor response untuk handle error 401
axiosInstance.interceptors.response.use(
  response => response,
  err => {
    console.log('errror auth', err);
    if (err.response && err.response.status === 401) {
      localStorage.removeItem('authToken');
      window.location.href = '/login';
    }
    return Promise.reject(err);
  }
);