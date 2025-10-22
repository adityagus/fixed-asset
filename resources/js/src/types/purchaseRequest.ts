export interface PurchaseRequest {
  id: number;
  title: string;
  description: string;
  amount: number;
  status: 'pending' | 'approved' | 'rejected';
  created_at: string;
  updated_at: string;
  user_id: number;
  user_name: string;
  user_email: string;
  attachments: string[];
  comments: Comment[];
}