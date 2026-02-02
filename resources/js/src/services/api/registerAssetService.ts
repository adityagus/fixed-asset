import axios from "axios";
import type { PurchaseRequest, PurchaseRequestItem, SubmitPurchaseRequestPayload } from "@/types/purchaseRequest";
import type { Submission, SubmissionListParams, SubmissionListResponse, SubmissionDetailResponse, CreateSubmissionPayload, UpdateSubmissionPayload } from "@/types/submission";
import { axiosInstance } from "@/utils/axios";
import { PurchaseOrder, SubmitPurchaseOrderPayload } from "@/types/purchaseOrders";
import { SubmitRegisterAssetPayload } from "@/types/registerAsset";

export const saveDraftRegistrationAsset = async (form: SubmitPurchaseOrderPayload): Promise<PurchaseOrder> => {
    console.log("isi form", form);
    form.po_number = form.formNumber;
    form.purchase_request_number = form.prReference;
    return (await axiosInstance.post<PurchaseOrder>("/purchase-order/saveDraft", form)).data;
};

export const submitRegistrationAsset = async (form: SubmitRegisterAssetPayload): Promise<PurchaseOrder> => {
    console.log("isi form", form);
    form.ra_number = form.formNumber;
    form.purchase_order_number = form.poReference;
    form.purchase_request_number = form.prReference;
    return (await axiosInstance.post<PurchaseOrder>("/registration-asset/submit", form)).data;
};
