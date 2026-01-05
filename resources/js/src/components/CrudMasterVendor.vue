<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <!-- Header dengan button Create -->
      <div class="mb-4 flex justify-between items-center">
        <p class="text-lg font-semibold">Total: {{ vendorListData.length }} Vendor</p>
        <button 
          @click="showForm = !showForm; if(showForm) resetForm()" 
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow flex items-center gap-2"
        >
          <span v-if="!showForm">+ Tambah Vendor Baru</span>
          <span v-else>✕ Tutup Form</span>
        </button>
      </div>

      <!-- Form Create/Update -->
      <div v-if="showForm" class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-3 text-blue-800">
          {{ isEdit ? '✏️ Edit Vendor' : '➕ Tambah Vendor Baru' }}
        </h3>
        <form @submit.prevent="handleSubmit" class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor *</label>
              <input 
                v-model="form.nama" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Masukkan nama vendor"
                required 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
              <input 
                v-model="form.alamat" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Alamat vendor"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
              <input 
                v-model="form.kota" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Kota"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Telepon 1</label>
              <input 
                v-model="form.telp1" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="No. telepon"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Telepon 2</label>
              <input 
                v-model="form.telp2" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="No. telepon alternatif"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
              <input 
                v-model="form.nm_bank" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Nama bank"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening</label>
              <input 
                v-model="form.no_rek" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Nomor rekening"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
              <input 
                v-model="form.atas_nm" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Nama pemilik rekening"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
              <select v-model="form.status" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="1">Aktif</option>
                <option value="0">Non-Aktif</option>
              </select>
            </div>
          </div>
          <div class="flex gap-2 mt-4 pt-3 border-t border-gray-300">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow font-semibold"
            >
              {{ isEdit ? '💾 Update Vendor' : '✓ Simpan Vendor' }}
            </button>
            <button 
              type="button" 
              @click="resetForm" 
              class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded shadow"
            >
              ↺ Reset
            </button>
            <button 
              type="button" 
              @click="showForm = false; resetForm()" 
              class="bg-red-400 hover:bg-red-500 text-white px-6 py-2 rounded shadow"
            >
              ✕ Batal
            </button>
          </div>
        </form>
      </div>

      <!-- Table List Vendor -->
      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Vendor</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kota</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(vendor, index) in vendorListData" :key="vendor.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ vendor.nama }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ vendor.alamat || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ vendor.kota || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ vendor.telp1 || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <button 
                    @click="handleEdit(vendor)" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow text-xs font-semibold"
                  >
                    ✏️ Edit
                  </button>
                  <button 
                    @click="handleDelete(vendor.id)" 
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs font-semibold"
                  >
                    🗑️ Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="vendorListData.length === 0">
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                Belum ada data vendor
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useGetVendorList } from '@/services/queries';
import { useCreateVendor, useUpdateVendor, useDeleteVendor } from '@/services/mutations';
import Swal from 'sweetalert2';

interface Vendor { 
  id?: number; 
  nama?: string;
  alamat?: string;
  kota?: string;
  telp1?: string;
  telp2?: string;
  nm_bank?: string;
  no_rek?: string;
  atas_nm?: string;
  status?: string;
}

const { data: vendorList, isLoading, error } = useGetVendorList();
const createMutation = useCreateVendor();
const updateMutation = useUpdateVendor();
const deleteMutation = useDeleteVendor();

// Form state
const showForm = ref(false);
const form = ref<Vendor>({ 
  id: null,
  nama: '', 
  alamat: '', 
  kota: '', 
  telp1: '', 
  telp2: '', 
  nm_bank: '', 
  no_rek: '', 
  atas_nm: '', 
  status: '1' 
});
const isEdit = ref(false);

const vendorListData = computed(() => {
  if (!vendorList.value) return [];
  if (Array.isArray(vendorList.value)) return vendorList.value;
  if (vendorList.value.data) return vendorList.value.data;
  return [];
});

function resetForm() {
  form.value = {
    id: null,
    nama: '', 
    alamat: '', 
    kota: '', 
    telp1: '', 
    telp2: '', 
    nm_bank: '', 
    no_rek: '', 
    atas_nm: '', 
    status: '1'
  };
  isEdit.value = false;
}

function handleEdit(vendor: Vendor) {
  form.value = { ...vendor };
  isEdit.value = true;
  showForm.value = true;
}

function handleDelete(id: number) {
  Swal.fire({
    title: 'Yakin hapus vendor ini?',
    text: "Data yang dihapus tidak bisa dikembalikan!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteMutation.mutate(id, {
        onSuccess: resetForm
      });
    }
  });
}

function handleSubmit() {
  const payload = { 
    nama: form.value.nama || '',
    alamat: form.value.alamat || '',
    kota: form.value.kota || '',
    telp1: form.value.telp1 || '',
    telp2: form.value.telp2 || '',
    nm_bank: form.value.nm_bank || '',
    no_rek: form.value.no_rek || '',
    atas_nm: form.value.atas_nm || '',
    status: form.value.status || '1'
  };
  if (isEdit.value && form.value.id) {
    updateMutation.mutate({ id: form.value.id, data: payload }, {
      onSuccess: () => {
        resetForm();
        showForm.value = false;
      }
    });
  } else {
    createMutation.mutate(payload, {
      onSuccess: () => {
        resetForm();
        showForm.value = false;
      }
    });
  }
}
</script>
