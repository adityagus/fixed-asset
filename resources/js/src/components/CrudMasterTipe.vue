<template>
  <div id='masterTipe'>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <!-- Header dengan button Create -->
      <div class="mb-4 flex justify-between items-center">
        <p class="text-lg font-semibold">Total: {{ tipeListData.length }} Tipe Barang</p>
        <button 
          @click="showForm = !showForm; if(showForm) resetForm()" 
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow flex items-center gap-2"
        >
          <span v-if="!showForm">+ Tambah Tipe Baru</span>
          <span v-else>✕ Tutup Form</span>
        </button>
      </div>

      <!-- Form Create/Update -->
      <div v-if="showForm" class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-3 text-blue-800">
          {{ isEdit ? '✏️ Edit Tipe' : '➕ Tambah Tipe Baru' }}
        </h3>
        <form @submit.prevent="handleSubmit" class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tipe *</label>
              <input 
                v-model="form.nama_tipebrg" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Masukkan nama tipe"
                required 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
              <input 
                v-model="form.kode" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Kode tipe (opsional)"
              />
            </div>
          </div>
          <div class="flex gap-2 mt-4 pt-3 border-t border-gray-300">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow font-semibold"
            >
              {{ isEdit ? '💾 Update Tipe' : '✓ Simpan Tipe' }}
            </button>
            <!-- <button 
              type="button" 
              @click="resetForm" 
              class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded shadow"
            >
              ↺ Reset
            </button> -->
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

      <!-- Table List Tipe -->
      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tipe</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(tipe, index) in tipeListData" :key="tipe.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ tipe.nama_tipebrg }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ tipe.kode || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex gap-2 justify-center">
                  <button @click="handleEdit(tipe)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    ✏️ Edit
                  </button>
                  <button @click="handleDelete(tipe.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                    🗑️ Hapus
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="tipeListData.length === 0">
              <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                Belum ada data tipe barang
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
import { useGetTipeBarangList } from '@/services/queries';
import { useCreateTipeBarang, useDeleteTipeBarang, useUpdateTipeBarang } from '@/services/mutations';
import Swal from 'sweetalert2';

interface Tipe { 
  id?: number; 
  nama_tipebrg?: string;
  kode?: string;
  status?: boolean;
}

const { data: tipeList, isLoading, error } = useGetTipeBarangList();
const createMutation = useCreateTipeBarang();
const updateMutation = useUpdateTipeBarang();
const deleteMutation = useDeleteTipeBarang();

// Form state
const showForm = ref(false);
const form = ref<Tipe>({
  id: null,
  nama_tipebrg: '',
  kode: '',
  status: true
});
const isEdit = ref(false);

const tipeListData = computed(() => {
  if (!tipeList.value) return [];
  if (tipeList.value.data) return tipeList.value.data;
  if (Array.isArray(tipeList.value)) return tipeList.value;
  return [];
});

function resetForm() {
  form.value = {
    id: null,
    nama_tipebrg: '',
    kode: '',
    status: true
  };
  isEdit.value = false;
}

function handleEdit(tipe: Tipe) {
    form.value = { ...tipe };
    isEdit.value = true;
    showForm.value = true;
    
    // Scroll to form with smooth animation
    setTimeout(() => {
        const element = document.getElementById('masterTipe');
        if (element) {
            const elementPosition = element.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - 100;
            
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }, 100);
}

function handleDelete(id: number) {
  Swal.fire({
    title: 'Yakin hapus tipe ini?',
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
    nama_tipebrg: form.value.nama_tipebrg || '',
    kode: form.value.kode || '',
    status: form.value.status !== false
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
