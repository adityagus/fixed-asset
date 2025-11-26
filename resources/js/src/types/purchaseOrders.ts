export interface PurchaseOrder {
  id: number;
  purchase_request_id: number;
  supplier: string;
  items: PurchaseOrderItem[];
  status: 'In Progress' | 'Approved' | 'Rejected';
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


export interface SubmitPurchaseOrderPayload{
  id: number
  po_number: string;
  po_date: string;
  purchase_request_number: string;
  cabang: string;
  justification: string;
  formNumber: string;
  prReference: string;
  poReference: string;
  invoiceDate: string;
  requestDate: string;
  requestedBy: string;
  status: string;
  items?: Omit<PurchaseRequestItem, 'id' | 'purchase_request_id'>[];
}