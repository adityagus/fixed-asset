import { mstBarang } from "./master";

type is_asset = 'active' | 'inactive';

export interface AssetDetail {
    asset_number: string;
    item_id: number;
    item: mstBarang;
    regist_item_id: number;
    purchase_request_number: string;
    purchase_order_number: string;
    location: string;
    is_asset: is_asset;
    registration_asset_number: string;
    category_id: number; //tapi masih null
    warranty_until: string;
    purchase_price: string;
    vendor: number;
    assigned_to: string;
    ra_date: string;
    status: string;
}
