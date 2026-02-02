export type AlertType = 'success' | 'error' | 'warning' | 'info' | 'question';
    
export type statusMstBarang = 'active' | 'inactive' | 'pending';
export interface mstBarang {
    id: number;
    nama_brg: string;
    // id_katbrg: number;
    // id_tipebrg: number;
    // id_merkbrg: number;
    category: mstKategoriBarang;
    tipe: mstTipeBarang;
    merk: mstMerkBarang;
    vendor: mstVendor;
    ket_brg: string;
    status: statusMstBarang;
    created_at: string;
    updated_at: string;
}

export interface mstKategoriBarang {
    id: number;
    nama_katbrg: string;
    type_katbrg: string;
    umur: number;
    status: boolean;
    created_at: string;
    updated_at: string;
}

export interface mstMerkBarang {
    id: number;
    nama_merkbrg: string;
    status: boolean;
    created_at: string;
    updated_at: string;
}

export interface mstTipeBarang {
    id: number;
    nama_tipebrg: string;
    kode: string;
    status: boolean;
    created_at: string;
    updated_at: string;
}

export interface mstVendor {
    id: number;
    nama: string;
    alamat: string;
    kota: string;
    telp1: string;
    telp2: string;
    pic: string;
    nm_bank: string;
    no_rek: string;
    atas_nm: string;
    status: boolean;
    created_at: string;
    updated_at: string;
}