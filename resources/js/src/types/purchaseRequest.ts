// {
//   cabang: "HO"
//   department: "Operations"
//   formNumber: "PR-19984704"
//   items: [{
//     …}]
//   justification: "Molestias cumque doloremque molestiae debitis odit maxime sed recusandae rerum architecto modi."
//   notes: ""
//   personalDetail: {
//     fullName: '',
//     email: '',
//     phone: ''
//   }
//   poReference: ""
//   requestDate: "2025-11-06T10:27"
//   requestedBy: "Gavin Bogisich Jr."
//   status: "draft"
//   supplier: ""
// }
export interface PurchaseRequest {
  [key: string]: any;
}

export interface SubmitPurchaseRequestPayload{
  cabang: string;
  justification: string;
    phone: string;
  poReference: string;
  requestDate: string;
  requestedBy: string;
  formNumber: string;
  status: string;
  items?: Omit<PurchaseRequestItem, 'id' | 'purchase_request_id'>[];
}

export interface saveAsDraftPurchaseRequestPayload{
  justification: string;
  items? : Omit<PurchaseRequestItem, 'id' | 'purchase_request_id'>[];
}

export interface PurchaseRequestItem {
  id: number;
  formNumber: number;
  item_name: string;
  qty: number;
  total_price: number;
  unit_price: number;
}

export interface purchaseRequestNotes {
  id: number;
  purchase_request_id: number;
  note: string;
  created_at: string;
  created_by: string;
}