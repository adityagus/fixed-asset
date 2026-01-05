<template>
  <div>
    <div class="panel p-6 no-print mb-2">
      <h2 class="text-2xl font-bold mb-6">Cetak Barcode Asset</h2>
      <div class="mb-6 flex gap-4 flex-wrap">
        <select v-model="selectedBranch" class="form-select w-64">
          <option value="">Semua Cabang</option>
          <!-- <option v-for="branch in branches" :key="branch" :value="branch">{{ branch }}</option> -->
            <option v-for="branch in branches" :key="branch.id_area" :value="branch.id_area">{{ branch.nm_area }}</option>
        </select>
        <select v-model="selectedType" class="form-select w-64">
          <option value="">Semua Tipe Barang</option>
          <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
        </select>
        <button class="btn btn-primary" @click="printBarcodes">Print All</button>
      </div>
    </div>
    <div class="print-area">
      <div class="grid grid-cols-6 gap-6">
        <div v-for="asset in filteredAssets" :key="asset.asset_number" class="flex flex-col items-center mb-6 col-span-1 barcode-sticker">
          <span class="text-xs mb-1 w-full text-center block">{{ asset.item.nama_brg }}</span>
          <span class="text-xs mb-1">{{ asset.asset_number }}</span>
          <qrcode-vue :value="asset.asset_number" :size="100" class="mb-1" />
          <span class="text-xs">{{ asset.location }}</span>
        </div>
      </div>
    </div>
  </div>

</template>

<style>
@media print {
  html, body {
    background: white !important;
  }
  body * {
    visibility: hidden !important;
    background: white !important;
  }
  .print-area, .print-area * {
    visibility: visible !important;
  }
  .print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100vw;
    background: white !important;
    margin: 0;
    padding: 0;
    box-shadow: none !important;
  }
  .barcode-sticker {
    page-break-inside: avoid;
    background: white !important;
    box-shadow: none !important;
    border: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  .no-print {
    display: none !important;
  }
}
</style>

<script setup lang="ts">
import { ref, computed } from 'vue';
import QrcodeVue from 'qrcode.vue';
import { useGetCabangList, useGetReportBarcode } from '@/services/queries';

const {data: branches } = useGetCabangList();

// const branches = computed(() => {
//   return cabangList.value?.data || [];
// });
const types = ['Laptop', 'Printer', 'Monitor', 'Meja', 'Kursi'];

const selectedBranch = ref('');
const selectedType = ref('');

const {data: reportBarcodeData} = useGetReportBarcode();

// const assets = ref(Array.from({ length: 300 }, (_, i) => ({
//   assetTag: `AT-${(i + 1).toString().padStart(3, '0')}`,
//   name: `ASSET ${i + 1}`,
//   type: ['Laptop', 'Printer', 'Monitor', 'Meja', 'Kursi'][Math.floor(Math.random() * 5)],
//   branch: ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Bali'][i % 5],
// })));

console.log("reportBarcodeData", reportBarcodeData);
const assets = computed(() => {
  return reportBarcodeData.value?.data || [];
});

// const assets = ref([
//     { assetTag: 'LPT0000001', name: 'LENOVO IP320S', type: 'Laptop', branch: 'Jakarta' },
//     { assetTag: 'LPT0000002', name: 'ASUS A-407UA', type: 'Laptop', branch: 'Jakarta' },
//   { assetTag: 'LPT0000003', name: 'LENOVO V310', type: 'Laptop', branch: 'Bandung' },
//   { assetTag: 'LPT0000004', name: 'LENOVO V330', type: 'Laptop', branch: 'Bandung' },
//   { assetTag: 'LPT0000005', name: 'LENOVO V130', type: 'Laptop', branch: 'Surabaya' },
//   { assetTag: 'LPT0000006', name: 'LENOVO V130', type: 'Laptop', branch: 'Medan' },
//   { assetTag: 'LPT0000007', name: 'LENOVO V130', type: 'Laptop', branch: 'Bali' },
//   { assetTag: 'LPT0000008', name: 'LENOVO V130', type: 'Laptop', branch: 'Jakarta' },
//   { assetTag: 'LPT0000009', name: 'LENOVO V130', type: 'Laptop', branch: 'Bandung' },
//   { assetTag: 'LPT0000010', name: 'LENOVO V130', type: 'Laptop', branch: 'Surabaya' },
//   { assetTag: 'LPT0000011', name: 'LENOVO IP320S', type: 'Laptop', branch: 'Medan' },
//   { assetTag: 'LPT0000012', name: 'LENOVO V130', type: 'Laptop', branch: 'Bali' },
//   { assetTag: 'LPT0000013', name: 'LENOVO V130', type: 'Laptop', branch: 'Jakarta' },
//   { assetTag: 'LPT0000014', name: 'LENOVO V130', type: 'Laptop', branch: 'Bandung' },
//   { assetTag: 'LPT0000015', name: 'LENOVO V130', type: 'Laptop', branch: 'Surabaya' },
//   { assetTag: 'LPT0000016', name: 'LENOVO V130', type: 'Laptop', branch: 'Medan' },
//   { assetTag: 'LPT0000017', name: 'LENOVO IP 320', type: 'Laptop', branch: 'Bali' },
//   { assetTag: 'LPT0000018', name: 'LENOVO V130', type: 'Laptop', branch: 'Jakarta' },
// ]);

const filteredAssets = computed(() => {
  return assets.value.filter(asset => {
    const branchMatch = !selectedBranch.value || asset.branch === selectedBranch.value;
    const typeMatch = !selectedType.value || asset.type === selectedType.value;
    return branchMatch && typeMatch;
  });
});

const printArea = ref<HTMLElement | null>(null);
function printBarcodes() {
  window.print();
  // print only the barcode area with not open blank window
  // const printArea = document.querySelector('.print-area');
  // if (printArea) {
  //   const printWindow = window.open('', '_blank');
  //   printWindow?.document.write(printArea.innerHTML);
  //   printWindow?.document.close();
  //   printWindow?.print();
  // }
}
</script>

