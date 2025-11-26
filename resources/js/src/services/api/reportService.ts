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

export const getReportBarcode = async () => {
    try {
        const response = await axiosInstance.get('/report/barcode');
        return response.data;
    } catch (error) {
        console.error('API getReportBarcode error:', error);
        throw error;
    }
}
