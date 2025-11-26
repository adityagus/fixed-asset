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
import { PurchaseOrder, SubmitPurchaseOrderPayload } from '@/types/purchaseOrders';


export const saveDraftPurchaseOrder = async (
  form: SubmitPurchaseOrderPayload,
): Promise<PurchaseOrder> => {
  console.log('isi form', form);
  form.po_number = form.formNumber;
  form.purchase_request_number = form.prReference;
  return (await axiosInstance.post<PurchaseOrder>('/purchase-order/saveDraft', form)).data;
};


export const submitPurchaseOrder = async (
  form: SubmitPurchaseOrderPayload,
): Promise<PurchaseOrder> => {
  console.log('isi form', form);
  form.po_number = form.formNumber;
  form.po_date = form.requestDate;
  form.purchase_request_number = form.prReference;
  return (await axiosInstance.post<PurchaseOrder>('/purchase-order/submit', form)).data;
};

