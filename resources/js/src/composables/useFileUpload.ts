import { QueryClient, useMutation, useQueryClient } from '@tanstack/vue-query';
import { deleteFile, postUploadFile } from '@/services/api/masterService';
import { axiosInstance } from '@/utils/axios';
import { uploadFileData } from '@/types/submission';

// Tipe data upload
interface UploadArgs {
  data: {
    formType: string;
    formNumber: string;
    uploaded_by: string;
  }; // atau formNumber, sesuaikan dengan kebutuhan di postUploadFile
  file: File;
  [key: string]: unknown; // opsional, kalau ada properti tambahan
}

export function useFileUpload() {
  const queryclient = useQueryClient();
  return useMutation({
    mutationFn: async (args: UploadArgs) => {
      console.log('Uploading file for PR Number:', args.data, args.file);
      await postUploadFile(args.data, args.file);
    },
    onSuccess: (data, variables, context) => {
      // console.log('Invalidating file list query for PR Number:', args, 'res', res);
      console.log("dagt", variables.data.formNumber);
      const formNumber = variables.data.formNumber;
      queryclient.invalidateQueries({ queryKey: ['fileList', formNumber] });// Sesuaikan query key sesuai kebutuhan
      console.log('File uploaded successfully', res);
    },
    onError: (error) => {
      console.error('Error uploading file:', error);
    },
    onSettled(data, error, variables, context) {
      console.log('File upload mutation settled');
    },
  });
}



export const useFileDelete = () => {
  const queryclient = useQueryClient();
  
  return useMutation({
    mutationFn: async (id:number) => {
      const response = await deleteFile(id);
      if (!response.status || response.status !== 200) {
        throw new Error('Failed to delete file');
      }
      return response.data;
    },
    onSuccess: (data, variables, context) => {
      const formNumber = data.form_number;
      console.log('Invalidating file list query for PR Number:', formNumber);
      queryclient.invalidateQueries({ queryKey: ['fileList', formNumber] });// Sesuaikan query 
      
      // queryclient.invalidateQueries(['fileList']);// Sesuaikan query key sesuai kebutuhan
    },
    onError: (error) => {
      console.error('Error deleting file:', error);
    },
    onSettled(data, error, variables, context) {
      console.log('File delete mutation settled');
    }
  });
}