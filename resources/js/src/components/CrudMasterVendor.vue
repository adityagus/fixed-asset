<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <form @submit.prevent="handleSubmit">
        <div class="mb-2">
          <input v-model="form.nama_vendor" type="text" class="form-input" placeholder="Nama Vendor" required />
        </div>
        <div class="mt-2 flex gap-3">
            <button type="submit" class="btn btn-primary">{{ editId ? 'Update' : 'Tambah' }}</button>
            <button v-if="editId" type="button" class="btn btn-secondary" @click="resetForm">Batal</button>
        </div>
      </form>
      <hr class="my-3" />
      <table class="table table-sm">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Vendor</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(vendor, index) in vendorListData" :key="vendor.id || index">
            <td>{{ index + 1 }}</td>
            <td>{{ vendor.nama }}</td>
            <td class='flex gap-3'>
              <button class="btn btn-xs btn-info" @click="edit(vendor)">Edit</button>
              <button class="btn btn-xs btn-danger" @click="hapus(vendor.id)">Hapus</button>
            </td>
          </tr>
          <tr v-if="vendorListData.length === 0">
            <td colspan="3" class="text-center">Belum ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useGetVendorList } from '@/services/queries';

interface Vendor { 
  id?: number; 
  nama_vendor?: string;
}

const { data: vendorList, isLoading, error } = useGetVendorList();

const form = ref<Vendor>({ nama_vendor: '' });
const editId = ref<number | null>(null);

const vendorListData = computed(() => {
  if (!vendorList.value) return [];
  if (Array.isArray(vendorList.value)) return vendorList.value;
  if (vendorList.value.data) return vendorList.value.data;
  return [];
});

function handleSubmit() {
  // TODO: Implement API call
  console.log('Submit:', form.value);
  resetForm();
}

function edit(vendor: Vendor) {
  form.value = { nama_vendor: vendor.nama_vendor };
  editId.value = vendor.id || null;
}

function hapus(id: number) {
  if (confirm('Yakin ingin menghapus data ini?')) {
    console.log('Delete:', id);
  }
}

function resetForm() {
  form.value = { nama_vendor: '' };
  editId.value = null;
}
</script>
