<template>
  <div class="panel p-6">
    <h2 class="text-2xl font-bold mb-6">Detail Aset</h2>
    <div v-if="asset">
      <div class="mb-4">
        <span class="font-semibold">Kode Aset:</span> {{ asset.assetTag }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Nama:</span> {{ asset.name }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Kategori:</span> {{ asset.category }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Lokasi:</span> {{ asset.location }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Cabang:</span> {{ asset.branch }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Status:</span> {{ asset.status }}
      </div>
    </div>
    <div v-else>
      <p class="text-red-600">Aset tidak ditemukan.</p>
    </div>
    <button class="btn btn-outline-secondary mt-6" @click="$router.back()">Kembali</button>
  </div>
</template>

<script lang="ts" setup>
// Komponen ini menampilkan detail aset berdasarkan kode aset dari URL
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const assetTag = route.params.assetId as string;

// Data dummy aset untuk contoh
const allAssets = [
  { assetTag: 'AT-001', name: 'Laptop Lenovo', category: 'Elektronik', location: 'Jakarta', branch: 'Jakarta', status: 'Aktif' },
  { assetTag: 'AT-002', name: 'Printer Epson', category: 'Elektronik', location: 'Gudang A', branch: 'Jakarta', status: 'Gudang' },
  { assetTag: 'AT-003', name: 'Meja Kerja', category: 'Furnitur', location: 'Bandung', branch: 'Bandung', status: 'Aktif' },
  { assetTag: 'AT-004', name: 'Monitor LG', category: 'Elektronik', location: 'Gudang B', branch: 'Surabaya', status: 'Gudang' },
  { assetTag: 'AT-005', name: 'Kursi Kantor', category: 'Furnitur', location: 'Medan', branch: 'Medan', status: 'Aktif' },
];

// Cari aset berdasarkan kode aset
const asset = ref(allAssets.find(a => a.assetTag === assetTag));
</script>
