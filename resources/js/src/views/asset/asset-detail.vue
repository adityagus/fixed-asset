<template>
  <div class="panel p-6">
    <h2 class="text-2xl font-bold mb-6">Asset Detail</h2>
    <div v-if="asset">
      <div class="mb-4">
        <span class="font-semibold">Asset Tag:</span> {{ asset.assetTag }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Name:</span> {{ asset.name }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Category:</span> {{ asset.category }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Location:</span> {{ asset.location }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Branch:</span> {{ asset.branch }}
      </div>
      <div class="mb-4">
        <span class="font-semibold">Status:</span> {{ asset.status }}
      </div>
    </div>
    <div v-else>
      <p class="text-red-600">Asset not found.</p>
    </div>
    <button class="btn btn-outline-secondary mt-6" @click="$router.back()">Back</button>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();
const assetTag = route.params.assetId as string;

const allAssets = [
  { assetTag: 'AT-001', name: 'Laptop Lenovo', category: 'Electronics', location: 'Jakarta', branch: 'Jakarta', status: 'Active' },
  { assetTag: 'AT-002', name: 'Printer Epson', category: 'Electronics', location: 'Gudang A', branch: 'Jakarta', status: 'Warehouse' },
  { assetTag: 'AT-003', name: 'Meja Kerja', category: 'Furniture', location: 'Bandung', branch: 'Bandung', status: 'Active' },
  { assetTag: 'AT-004', name: 'Monitor LG', category: 'Electronics', location: 'Gudang B', branch: 'Surabaya', status: 'Warehouse' },
  { assetTag: 'AT-005', name: 'Kursi Kantor', category: 'Furniture', location: 'Medan', branch: 'Medan', status: 'Active' },
];

const asset = ref(allAssets.find(a => a.assetTag === assetTag));
</script>
