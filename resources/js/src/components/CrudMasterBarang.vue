<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <table class="table table-sm">
        <thead>
          <tr>
            <th>No</th>
            <th>Kode Item</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Tipe</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, index) in barangListData" :key="item.id">
            <td>{{ index + 1 }}</td>
            <td>{{ item.brand?.nama_merkbrg || '-' }}</td>
            <td>{{ item.nama_brg }}</td>
            <td>{{ item.category?.nama_katbrg || '-' }}</td>
            <td>{{ item.type?.nama_tipebrg || '-' }}</td>
            <td class='flex gap-3'>
              <button class="btn btn-xs btn-info">Detail</button>
            </td>
          </tr>
          <tr v-if="barangListData.length === 0">
            <td colspan="6" class="text-center">Belum ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue';
import { useGetMasterBrg } from '@/services/queries';

const { data: barangList, isLoading, error } = useGetMasterBrg();

const barangListData = computed(() => {
  if (!barangList.value) return [];
  if (Array.isArray(barangList.value)) return barangList.value;
  if (barangList.value.data) return barangList.value.data;
  return [];
});
</script>
