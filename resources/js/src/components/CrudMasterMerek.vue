<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <!-- Header dengan button Create -->
      <div class="mb-4 flex justify-between items-center">
        <p class="text-lg font-semibold">Total: {{ merekListData.length }} Merek</p>
        <button 
          @click="showForm = !showForm; if(showForm) resetForm()" 
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow flex items-center gap-2"
        >
          <span v-if="!showForm">+ Tambah Merek Baru</span>
          <span v-else>✕ Tutup Form</span>
        </button>
      </div>

      <!-- Form Create/Update -->
      <div v-if="showForm" class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-3 text-blue-800">
          {{ isEdit ? '✏️ Edit Merek' : '➕ Tambah Merek Baru' }}
        </h3>
        <form @submit.prevent="handleSubmit" class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Merek *</label>
              <input 
                v-model="form.nama_merkbrg" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Masukkan nama merek"
                required 
              />
            </div>
          </div>
          <div class="flex gap-2 mt-4 pt-3 border-t border-gray-300">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow font-semibold"
            >
              {{ isEdit ? '💾 Update Merek' : '✓ Simpan Merek' }}
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

      <!-- Table List Merek -->
      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Merek</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(merek, index) in merekListData" :key="merek.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ merek.nama_merkbrg }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex gap-2 justify-center">
                  <button @click="handleEdit(merek)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    ✏️ Edit
                  </button>
                  <button @click="handleDelete(merek.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                    🗑️ Hapus
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="merekListData.length === 0">
              <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                Belum ada data merek
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
import { useGetMerkList } from '@/services/queries';
import { useCreateMerk, useUpdateMerk, useDeleteMerk } from '@/services/mutations';
import Swal from 'sweetalert2';

interface Merek { 
  id?: number; 
  nama_merkbrg?: string;
}

const { data: merekList, isLoading, error } = useGetMerkList();
const createMutation = useCreateMerk();
const updateMutation = useUpdateMerk();
const deleteMutation = useDeleteMerk();

// Form state
const showForm = ref(false);
const form = ref<Merek>({
  id: null,
  nama_merkbrg: ''
});
const isEdit = ref(false);

const merekListData = computed(() => {
  if (!merekList.value) return [];
  if (merekList.value.data) return merekList.value.data;
  if (Array.isArray(merekList.value)) return merekList.value;
  return [];
});

function resetForm() {
  form.value = {
    id: null,
    nama_merkbrg: ''
  };
  isEdit.value = false;
}

function handleEdit(merek: Merek) {
  form.value = { ...merek };
  isEdit.value = true;
  showForm.value = true;
}

function handleDelete(id: number) {
  Swal.fire({
    title: 'Yakin hapus merek ini?',
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
    nama_merkbrg: form.value.nama_merkbrg || ''
  };
  console.log('isi dari edit', isEdit.value , form.value.id);
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
