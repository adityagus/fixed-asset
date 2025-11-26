// import { type } from './../../../../node_modules/.vite/deps_temp/chunk-IBZUCRRA';
export interface Submission {
  id: number;
  submission_number: string;
  pr_number?: string;
  po_number?: string;
  ar_number?: string;
  cabang: string;
  created_by: string;
  justification?: string;
  notes: Notes[];
  submission_type: 'PR' | 'PO' | 'AR';
  title: string;
  approvals: ApprovalLayer[] | undefined;
  description?: string;
  status: 'draft' | 'pending' | 'approved' | 'rejected';
  requester_id: number;
  requester_name: string;
  department: string;
  total_amount?: number;
  currency?: string;
  created_at: string;
  updated_at: string;
  approved_at?: string;
  approved_by?: string;
}


export interface Notes {
   id: number;
   submission_id: number;
   note: string;
   created_at: string;
   created_by: string;
}

export interface SubmissionListParams {
  type?: 'PR' | 'PO' | 'AR';
  // page?: number;
  // per_page?: number;
  // status?: string;
  // submission_type?: string;
  // search?: string;
  // sort_by?: string;
  // sort_order?: 'asc' | 'desc';
}

export interface ApprovalLayer {
  id: number;
  submission_id: number;
  layer: number;
  approver_id: number;
  approver_name: string;
  status: 'pending' | 'approved' | 'rejected';
  created_at: string;
  updated_at: string;
}

export interface SubmissionListResponse {
  data: Submission[];
  success: boolean;
  // meta: {
  //   current_page: number;
  //   last_page: number;
  //   per_page: number;
  //   total: number;
  // };
}

export interface CreateSubmissionPayload {
  submission_type: 'PR' | 'PO' | 'AR';
  title: string;
  description?: string;
  department: string;
  items?: any[];
  total_amount?: number;
}


export interface UpdateSubmissionPayload {
  title?: string;
  description?: string;
  status?: string;
  items?: any[];
  total_amount?: number;
}

export interface SubmissionDetailResponse {
  data: Submission;
}

type UploadedFile = {
  id: string | number; // misal id dari server
  file: File;          // File asli JS
};