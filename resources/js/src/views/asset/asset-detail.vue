<template>
  <div class="w-full max-w-full">
    <!-- Header mirip referensi -->
    <div class="bg-white border-b border-gray-200 px-6 pt-6 pb-3 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
      <div class="flex items-center gap-3">
        <div class="w-14 h-14 flex items-center justify-center bg-gray-100 rounded-lg border border-gray-200">
          <svg width="38" height="38" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/>
            <path d="M3 10h18" stroke="currentColor" stroke-width="1.5"/>
            <rect x="8" y="3" width="8" height="4" rx="1" stroke="currentColor" stroke-width="1.5"/>
          </svg>
        </div>
        <div>
          <div class="flex items-center gap-2 mb-0.5">
            <h2 class="text-lg font-semibold text-gray-800">{{ asset?.item?.nama_brg || '-' }} <p v-if="asset?.asset_number" class="text-gray-600 font-normal">{{ asset.asset_number }}</p></h2>
            <span v-if="asset?.status" class="ml-2 px-2.5 py-0.5 rounded text-xs font-semibold border border-green-300 bg-green-50 text-green-700" style="letter-spacing:0.5px;">{{ asset.status }}</span>
          </div>
          <div class="text-xs text-gray-500">{{ asset?.item?.category?.nama_katbrg || '-' }}</div>
        </div>
      </div>
      <!-- <div class="flex gap-2 flex-wrap">
        <button class="px-3 py-1.5 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-medium flex items-center gap-1"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19.5 3 21l1.5-4L16.5 3.5z"/></svg>Edit</button>
        <button class="px-3 py-1.5 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-medium flex items-center gap-1"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 17l4 4 4-4m-4-5v9"/><path d="M20.39 19.37A9 9 0 1 0 21 12.55"/></svg>Clone</button>
        <button class="px-3 py-1.5 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-medium flex items-center gap-1"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12z"/><path d="M19 7V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v2"/></svg>Delete</button>
        <button class="px-3 py-1.5 rounded border border-gray-300 bg-white hover:bg-gray-100 text-gray-700 text-xs font-medium flex items-center gap-1"><svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 17v-1a4 4 0 0 0-3-3.87"/><path d="M7 7a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"/></svg>Retire</button>
      </div> -->
    </div>
    <!-- Tab Navigation -->
    <div class="bg-white border-b border-gray-200 px-6 flex gap-1 text-xs font-medium">
      <button class="py-2.5 px-3 border-b-2 border-primary text-primary focus:outline-none">DETAILS</button>
    <!-- Menu Maintain -->
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      SERVICES
    <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
      Layanan terkait aset
    </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      MUTASI
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Perpindahan aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      TERMINATE
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Penghentian aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      PENJUALAN
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Penjualan aset
      </span>
    </button>
    <!-- <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      CARTS
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Keranjang aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      RESERVATIONS
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Reservasi aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      PACKAGE
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Paket aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary relative group">
      EVENTS
      <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 px-2 py-1 bg-gray-800 text-white text-xs rounded shadow opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 whitespace-nowrap">
        Event aset
      </span>
    </button>
    <button class="py-2.5 px-3 text-gray-500 hover:text-primary">WORK ORDERS</button> -->
    </div>
    <!-- Main Content -->
    <div class="bg-white px-6 py-6">
      <div v-if="asset" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-3">
          <div class="text-base font-semibold text-gray-700 mb-1">Detail Aset</div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs">
            <div class="text-gray-500">ID Aset</div>
            <div>{{ asset.id || '-' }}</div>
            <div class="text-gray-500">Harga Perolehan</div>
            <div>{{ asset.susut?.total_umur && asset.susut?.nom_susut ? formatRupiah(asset.susut.total_umur * asset.susut.nom_susut) : '-' }}</div>
            <div class="text-gray-500">Dibuat Pada</div>
            <div>{{ asset.created_at ? new Date(asset.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-' }}</div>
            <div class="text-gray-500">Nilai Penyusutan</div>
            <div>{{ asset.susut?.sisa_umur && asset.susut?.nom_susut ? formatRupiah(asset.susut.sisa_umur * asset.susut.nom_susut) : '-' }}</div>
            <div class="text-gray-500">Lokasi</div>
            <div>{{ nameLocation || '-' }}</div>
            <div class="text-gray-500">Deskripsi</div>
            <div>{{ asset.description || '-' }}</div>
            <div class="text-gray-500">Tanggal Servis Terakhir</div>
            <div>-</div>
            <div class="text-gray-500">Tarif Pajak</div>
            <div>-</div>
          </div>
        </div>
        <div class="space-y-3">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs mt-6 md:mt-0">
            <div class="text-gray-500">Vendor</div>
            <div><a href="#" class="text-primary hover:underline">{{ asset.item?.vendor?.nama_vendor || '-' }}</a></div>
            <div class="text-gray-500">Tanggal Pembelian</div>
            <div>{{ asset.purchased_on || '-' }}</div>
            <div class="text-gray-500">Kategori</div>
            <div><a href="#" class="text-primary hover:underline">{{ asset.item?.category?.nama_katbrg || '-' }}</a></div>
            <div class="text-gray-500">Paket</div>
            <div><a href="#" class="text-primary hover:underline">{{ asset.item?.nama_brg ? 'Laptop dan Aksesoris - ' + asset.item.nama_brg : '-' }}</a></div>
            <div class="text-gray-500">Tanggal Servis Berikutnya</div>
            <div>-</div>
          </div>
        </div>
      </div>
      <div v-else-if="isPending" class="flex items-center justify-center h-32">
        <svg class="animate-spin h-8 w-8 text-primary mr-3" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" />
        </svg>
        <span class="text-gray-500 text-lg">Memuat detail aset...</span>
      </div>
      <div v-else class="flex flex-col items-center justify-center h-32">
        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-2 text-red-400">
          <circle cx="12" cy="12" r="10" stroke="currentColor" />
          <path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-linecap="round" />
        </svg>
        <p class="text-red-600 text-lg font-semibold">Aset tidak ditemukan.</p>
      </div>
    </div>
    <div class="flex justify-end mt-6 px-6 pb-6">
      <button class="btn btn-outline-secondary transition-all duration-200 hover:bg-primary hover:text-white hover:border-primary flex items-center gap-2 px-5 py-2 rounded-lg shadow text-xs font-medium" @click="$router.back()">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Kembali
      </button>
    </div>
  </div>
</template>
<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.text-primary {
  color: #2563eb;
}
.border-primary {
  border-color: #2563eb;
}
</style>


<script lang="ts" setup>
// Format rupiah helper
function formatRupiah(val: any) {
  const num = Number(val);
  if (val === null || val === undefined || val === '' || isNaN(num)) return '-';
  return num.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
}
// Komponen ini menampilkan detail aset berdasarkan kode aset dari URL
import { useGetAssetDetail, useGetCabangList } from '@/services/queries';
import { computed } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const assetNumber = route.params.assetNumber as string;

const { data: asset, isSuccess, isPending, isError } = useGetAssetDetail(assetNumber);
const { data: cabangList } = useGetCabangList();

// Mapping location ke nama cabang (nameLocation)
const nameLocation = computed(() => {
  if (!asset.value || !cabangList.value) return '';
  const cabang = cabangList.value.find((c) => c.id_area === asset.value.location);
  return cabang ? cabang.nm_area : asset.value.location;
});

console.log('Asset detail for asset number:', assetNumber, asset);
// Data dummy aset untuk contoh
// const allAssets = [
//   { assetTag: 'AT-001', name: 'Laptop Lenovo', category: 'Elektronik', location: 'Jakarta', branch: 'Jakarta', status: 'Aktif' },
//   { assetTag: 'AT-002', name: 'Printer Epson', category: 'Elektronik', location: 'Gudang A', branch: 'Jakarta', status: 'Gudang' },
//   { assetTag: 'AT-003', name: 'Meja Kerja', category: 'Furnitur', location: 'Bandung', branch: 'Bandung', status: 'Aktif' },
//   { assetTag: 'AT-004', name: 'Monitor LG', category: 'Elektronik', location: 'Gudang B', branch: 'Surabaya', status: 'Gudang' },
//   { assetTag: 'AT-005', name: 'Kursi Kantor', category: 'Furnitur', location: 'Medan', branch: 'Medan', status: 'Aktif' },
// ];

// Cari aset berdasarkan kode aset
// const asset = ref(allAssets.find(a => a.assetTag === assetTag));
</script>
