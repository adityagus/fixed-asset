// // import { PurchaseRequest } from '@/types/purchaseRequest';
// import axios from 'axios';

// const baseUrl = 'http://fixedasset.test/api'; // Ganti dengan URL dasar API Anda
// const axiosInstance = axios.create({
//   baseURL: baseUrl,
// });

// export const getPurchaseRequestIds = async () => {
//   return (await axiosInstance.get<PurchaseRequest[]>('/purchase-requests')).data.map(pr => pr.id);
// }

// // export const getSubmission = async (type: string, requestBy: string) => {
// //   return (await axiosInstance.post<[]>(`/submissions/${type}`, { requestBy })).data.map((submission) => ({
// //     ...submission,
// //     prDate: new Date(submission.prDate),
// //   }));
// // }
