<template>
  <div id="masterKategori">
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <!-- Header dengan button Create -->
      <div class="mb-4 flex justify-between items-center">
        <p class="text-lg font-semibold">Total: {{ kategoriListData.length }} Kategori</p>
        <button 
          @click="showForm = !showForm; if(showForm) resetForm()" 
          class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow flex items-center gap-2"
        >
          <span v-if="!showForm">+ Tambah Kategori Baru</span>
          <span v-else>✕ Tutup Form</span>
        </button>
      </div>

      <!-- Form Create/Update -->
      <div v-if="showForm" class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-3 text-blue-800">
          {{ isEdit ? '✏️ Edit Kategori' : '➕ Tambah Kategori Baru' }}
        </h3>
        <form @submit.prevent="handleSubmit" class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
              <input 
                v-model="form.nama_katbrg" 
                type="text" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Masukkan nama kategori"
                required 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Umur (bulan) *</label>
              <input 
                v-model.number="form.umur" 
                type="number" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Umur penyusutan"
                required 
              />
            </div>
          </div>
          <div class="flex gap-2 mt-4 pt-3 border-t border-gray-300">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow font-semibold"
            >
              {{ isEdit ? '💾 Update Kategori' : '✓ Simpan Kategori' }}
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

      <!-- Table List Kategori -->
      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(kategori, index) in kategoriListData" :key="kategori.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ kategori.nama_katbrg }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ kategori.umur }} bulan</td>
              <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                <div class="flex gap-2 justify-center">
                  <button @click="handleEdit(kategori)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                    ✏️ Edit
                  </button>
                  <button @click="handleDelete(kategori.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                    🗑️ Hapus
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="kategoriListData.length === 0">
              <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                Belum ada data kategori
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
import { useGetKategoriList } from '@/services/queries';
import { useCreateKategori, useUpdateKategori, useDeleteKategori } from '@/services/mutations';
import Swal from 'sweetalert2';

interface Kategori { 
  id?: number; 
  nama_katbrg?: string;
  umur?: number;
  status?: boolean;
}

const { data: kategoriList, isLoading, error } = useGetKategoriList();
const createMutation = useCreateKategori();
const updateMutation = useUpdateKategori();
const deleteMutation = useDeleteKategori();

// Form state
const showForm = ref(false);
const form = ref<Kategori>({
  id: null,
  nama_katbrg: '',
  umur: 0,
  status: true
});
const isEdit = ref(false);

const kategoriListData = computed(() => {
  if (!kategoriList.value) return [];
  if (kategoriList.value.data) return kategoriList.value.data;
  if (Array.isArray(kategoriList.value)) return kategoriList.value;
  return [];
});

function resetForm() {
  form.value = {
    id: null,
    nama_katbrg: '',
    umur: 0,
    status: true
  };
  isEdit.value = false;
}

function handleEdit(kategori: Kategori) {
  form.value = { ...kategori };
  isEdit.value = true;
  showForm.value = true;
  
  setTimeout(() => {
    const element = document.getElementById('masterKategori');
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
    title: 'Yakin hapus kategori ini?',
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
    nama_katbrg: form.value.nama_katbrg || '', 
    umur: form.value.umur || 0,
    status: form.value.status !== false,
    coa1: null,
    coa2: null,
    coa3: null,
    coa4: null
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
