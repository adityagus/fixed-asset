import { axiosInstance } from '@/utils/axios';

// Fungsi untuk mengambil data laporan aset dari backend
export const getAssetReport = async () => {
  try {
    const response = await axiosInstance.get('/report/asset');
    return response.data;
  } catch (error) {
    console.error('API getAssetReport error:', error);
    throw error;
  }
};

export const getAssetReportPaginated = async (data: { limit: number; offset: number }) => {
    try {
        const response = await axiosInstance.post('/report/asset/paginated', data);       
        return response.data;
    } catch (error) {
        console.error('API getAssetReportPaginated error:', error);
        throw error;
    }
}

export const getAssetByCabangPaginated = async (data: { cabang_id: string; tipe_aset: string; limit: number; offset: number }) => {
    try {
        const response = await axiosInstance.post('/report/asset/cabang/paginated', data);
        return response.data.data;
    } catch (error) {
        console.error('API getAssetByCabangPaginated error:', error);
        throw error;
    }
}

export const getReportBarcode = async () => {
    try {
        const response = await axiosInstance.get('/report/barcode');
        return response.data;
    } catch (error) {
        console.error('API getReportBarcode error:', error);
        throw error;
    }
}

export const getReportByCabang = async (data: any) => {
    try {
        const response = await axiosInstance.post('/report/by-cabang', data);
        return response.data;
    }catch (error) {       
        console.error('API getReportByCabang error:', error);
        throw error;
    }    
}

export const getHeaderStatistik = async () => {
    try {
        console.log('Fetching header statistik from API');
        const response = await axiosInstance.get('/statistik/header-asset');
        console.log('API getHeaderStatistik response:', response.data);
        return response.data.data;
    } catch (error) {
        console.error('API getHeaderStatistik error:', error);
        throw error;
    }
}

export const getBodyStatistik = async () => {
    try {
        console.log('Fetching body statistik from API');
        const response = await axiosInstance.get('/statistik/body-asset');
        console.log('API getBodyStatistik response:', response.data);
        return response.data.data;
    } catch (error) {
        console.error('API getBodyStatistik error:', error);
        throw error;
    }   
}
