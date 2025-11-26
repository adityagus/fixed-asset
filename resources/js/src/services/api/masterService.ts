import { axiosInstance } from "@/utils/axios";

export const getMasterBrg = async () => {
  return (await axiosInstance.get('/master/barang')).data;
}

interface UploadFileData {
  formNumber: string;
  formType: string;
  uploaded_by: string;
}

export const postUploadFile = async (data: UploadFileData, file: File) => {
  console.log('Preparing to upload file with data:', data, 'and file:', file);
  const formData = new FormData();
  formData.append('form_number', data.formNumber);
  formData.append('attachable_type', data.formType);
  formData.append('uploaded_by', data.uploaded_by);
  formData.append('url_file', file);
  formData.append('mime_type', file.type);
  formData.append('size', file.size.toString());
  formData.append('file_name', file.name);
  // protected $fillable = [
  //     'attachable_type',
  //     'attachable_id',
  //     'size',
  //     'file_path',
  //     'file_name',
  //     'uploaded_by',
  //   ];
  
  console.log('FormData entries:');
  formData.forEach((value, key) => {
    console.log(`  ${key}: ${value}`);
  });
  const response = (await axiosInstance.post('/uploadFile', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    }
  }));
  console.log('Upload response:', response);
  return response.data;
}

export const deleteFile = async (id: number) => {
  console.log('Deleting file with ID:', id, );
  return await axiosInstance.delete('/deleteFile', { params: { id } });
}
  

export const getFileList = async (formNumber: string) => {
  console.log('Fetching file list for PR Number:', formNumber);
  const response = await axiosInstance.get('/file-list', {
    params: { formNumber }
  });
  console.log('File list response:', response);
  return response.data;
}

export const getVendorList = async () => {
  try {
    const response = await axiosInstance.get('/master/vendor');
    console.log('Vendor list response:', response);
    return response.data;
  } catch (error) {
    console.error('Error fetching vendor list:', error);
    throw error;
  }
}