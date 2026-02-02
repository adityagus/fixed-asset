import { invoke } from "@vueuse/core";

export interface PurchaseOrder {
    id: number;
    purchase_request_id: number;
    supplier: string;
    items: PurchaseOrderItem[];
    status: "In Progress" | "Approved" | "Rejected";
    created_at: string;
    updated_at: string;
}

export interface PurchaseOrderItem {
    id: number;
    purchase_order_id: number;
    item_name: string;
    quantity: number;
    unit_price: number;
    total_price: number;
}

export interface purchaseOrderNotes {
    id: number;
    purchase_order_id: number;
    note: string;
    created_at: string;
    created_by: string;
}

export interface PurchaseOrderResponse {
    message: string;
    data: PurchaseOrder;
}

export interface PurchaseRequestItem {
    id: number;
    purchase_order_number: number;
    item_name: string;
    quantity: number;
    unit_price: number;
    total_price: number;
}

export interface SubmitPurchaseOrderPayload {
    id: number;
    po_number: string;
    po_date: string;
    purchase_request_number: string;
    cabang: string;
    justification: string;
    formNumber: string;
    prReference: string;
    requestDate: string;
    requestedBy: string;
    status: string;
    items?: Omit<PurchaseRequestItem, "id" | "purchase_request_id">[];
}

export interface RegisterAssetItem {
    id?: number;
    item_name: string;
    category?: string;
    asset_tag?: string;
    location?: string;
    quantity: number;
    unit_price: number;
    total_price: number;
    purchase_order_number?: string;
    // tambahkan properti lain jika perlu
}

export interface SubmitRegisterAssetPayload {
    id: number;
    po_date: string;
    // relation purchase_request_number: string; --- IGNORE ---
    purchase_request_number: string;
    purchase_order_number: string;
    prReference: string;
    po_number: string;
    ra_number?: string;
    ra_date?: string;
    poReference: string;
    // 2 data yang mirip
    invoice_date?: string;
    invoiceDate?: string;

    cabang: string;
    justification: string;
    formNumber: string;
    requestDate: string;
    requestedBy: string;
    department?: string;
    status: string;
    items: RegisterAssetItem[];
    // tambahkan properti lain jika perlu
}
