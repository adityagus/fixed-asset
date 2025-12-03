import { axiosInstance } from '@/utils/axios';
import type { PurchaseRequest } from '@/types/purchaseRequest';
import type {
  Submission,
  SubmissionListParams,
  SubmissionListResponse,
  SubmissionDetailResponse,
  CreateSubmissionPayload,
  UpdateSubmissionPayload,
} from '@/types/submission';

export const login = async (formdata: { user: string; pass: string }) => {
  return (await axiosInstance.post('/login', formdata)).data;
}


export const getPurchaseRequestIds = async () => {
  return (await axiosInstance.get<PurchaseRequest[]>('/purchase-requests')).data.map(pr => pr.id) || [];
}

// export const getSubmission = async (type: string, requestBy: string) => {
//   return (await axiosInstance.post<Submission>(`/submissions/${type}`, { requestBy })).data
// }

export const getSubmission = async (
    type: string,
): Promise<SubmissionListResponse> => {
  const response = await axiosInstance.get<any>(`/submission/${type}`);
  console.log('API getSubmission response:', response.data);
  return response?.data;
};

export const postSubmission = async (formType) => {
  try {
    const response = await axiosInstance.post<any>(`/${formType}/create`);
    console.log('API postSubmission response:', response.data);
    return response.data;

  } catch (error) {
    console.error('API postSubmission error:', error);
    throw error;
  }
};

export const deleteSubmission = async (type: string, requestNumber: string) => {
  try {
    console.log('Deleting submission:', type, requestNumber);
    const response = await axiosInstance.delete<any>(`/submission/delete/${type}/${requestNumber}`);
    console.log('API deleteSubmission response:', response.data);
    return response.data;
  } catch (error) {
    console.error('API deleteSubmission error:', error);
    throw error;
  }
}


export const getSubmissionDetail = async (formType, requestNumber) => {
  console.log('Fetching submission detail for', formType, requestNumber);
  try {
    const response = await axiosInstance.get<SubmissionDetailResponse>(`/${formType}/detail/${requestNumber}`);
    console.log('API getSubmissionDetail response:', response.data);
    return response.data;
  } catch (error) {
    console.error('API getSubmissionDetail error:', error);
    throw error;
  }
}

export const getListApproval = async (type) => {
  try {
    console.log('Fetching approval list for', type);
    const response = await axiosInstance.post<Array<any>>(`/approvals/${type}`)
    console.log('listApproval');
    return response.data ?? [];
  } catch (error) {
    console.error('Api approval list error', error);
    throw error;
  }
}

export const setApprovalStatus = async (payload: { formNumber: number; layer: number; status: string; type: string }) => {
  try {
    const response = await axiosInstance.post('/set-approval', payload);
    console.log('API setApprovalStatus response:', response.data);
    return response.data;
  } catch (error) {
    console.error('API setApprovalStatus error:', error);
    throw error;
  }
};


// List Approved
export const getListApproved = async (formType) => {
  try {
    const response = await axiosInstance.get(`/${formType}/list-approved`);
    return response?.data?.data ?? [];
  } catch (error) {
    console.error('API getListApproved error:', error);
    throw error;
  }
}

export const postSendNote = async(notes)=>{
    try {
        console.log('Sending note:', notes);
        notes.form_type = notes.formType;
        notes.form_number = notes.formNumber;
        delete notes.formType;
        delete notes.formNumber;
        const response = await axiosInstance.post<any>(`/send-note`, notes);
        console.log('API postSendNote response:', response.data);
        return response.data;
      } catch (error) {
        console.error('API postSendNote error:', error);
        throw error;
      }
}

export const getNotes = async (formType, formNumber) => {
    try {
        console.log('masuk service', formType, formNumber);
        const response = await axiosInstance.get<any>(`/notes`, {
            params: {
                formType: formType,
                formNumber: formNumber,
            },
        })
        console.log('API getNotes response:', response.data)
        return response.data
    } catch (error) {
        console.error('API getNotes error:', error)
        throw error
    }
}

