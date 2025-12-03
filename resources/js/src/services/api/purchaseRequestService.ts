import axios from 'axios';
import type { PurchaseRequest, PurchaseRequestItem, SubmitPurchaseRequestPayload } from '@/types/purchaseRequest';
import type {
  Submission,
  SubmissionListParams,
  SubmissionListResponse,
  SubmissionDetailResponse,
  CreateSubmissionPayload,
  UpdateSubmissionPayload,
} from '@/types/submission';
import { axiosInstance } from '@/utils/axios';

export const login = async (formdata: { user: string; pass: string }) => {
  return (await axiosInstance.post('/login', formdata)).data;
}

export const getPurchaseRequestIds = async () => {
  return (await axiosInstance.get<PurchaseRequest[]>('/purchase-requests')).data.map(pr => pr.id);
}

// export const getSubmission = async (type: string, requestBy: string) => {
//   return (await axiosInstance.post<Submission>(`/submissions/${type}`, { requestBy })).data
// }

export const getSubmission = async (
  type: string
): Promise<SubmissionListResponse> => {
  const response = await axiosInstance.get<any>(`/submission/${type}`);
  console.log('API getSubmission response:', response.data);
  return response.data;
};

export const postSubmission = async (formType: {}) => {
  try {
    const response = await axiosInstance.post<any>(`/${formType}/create`);
    console.log('API postSubmission response:', response.data);
    return response.data;

  } catch (error) {
    console.error('API postSubmission error:', error);
    throw error;
  }
};

export const deleteSubmission = async (type: string, requestNumber: String) => {
  try {
    const response = await axiosInstance.delete<any>(`/submissions/delete/${type}/${requestNumber}`);
    console.log('API deleteSubmission response:', response.data);
    return response.data;
  } catch (error) {
    console.error('API deleteSubmission error:', error);
    throw error;
  }
}

export const createPurchaseRequest = async () => {
  return (await axiosInstance.post('/purchase-request/create')).data;
};

export const saveDraftPurchaseRequest = async (
  form: SubmitPurchaseRequestPayload,
): Promise<PurchaseRequest> => {
  console.log('isi form', form);
  form.pr_number  = form.formNumber;
  return (await axiosInstance.post<PurchaseRequest>('/purchase-request/saveDraft', form)).data;
};


export const submitPurchaseRequest = async (
  form: SubmitPurchaseRequestPayload,
): Promise<PurchaseRequest> => {
  console.log('isi form', form.formNumber);
  form.pr_number  = form.formNumber;
  form.jenis_permintaan  = form.jenisPermintaan;
  return (await axiosInstance.post<PurchaseRequest>('/purchase-request/submit', form)).data;
};

export const getPurchaseRequestDetail = async (pr_number: string) => {
  return (await axiosInstance.get(`/purchase-request/detail/${pr_number}`)).data;
};

