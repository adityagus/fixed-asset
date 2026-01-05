<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <!-- Header dengan button Create -->
      <div class="mb-4 flex justify-between items-center">
        <p class="text-lg font-semibold">Total: {{ barangListData.length }} Barang</p>
        <button 
          @click="showForm = !showForm; if(showForm) resetForm()" 
          :class="[showForm ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600', 'text-white px-4 py-2 rounded shadow flex items-center gap-2']"
        >
          <span v-if="!showForm">+ Tambah Barang Baru</span>
          <span v-else>✕ Tutup Form</span>
        </button>
      </div>

      <!-- Form Create/Update -->
      <div v-if="showForm" class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg shadow-md">
        <h3 class="text-xl font-bold mb-3 text-blue-800">
          {{ isEdit ? '✏️ Edit Barang' : '➕ Tambah Barang Baru' }}
        </h3>
        <form @submit.prevent="handleSubmit" class="space-y-3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang *</label>
              <input 
                v-model="form.nama_brg" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Masukkan nama barang"
                required 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
              <select v-model="form.id_katbrg" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" disabled>Pilih Kategori</option>
                <option v-for="kat in kategoriListData" :key="kat.id" :value="kat.id">{{ kat.nama_katbrg }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
              <select v-model="form.id_tipebrg" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" disabled>Pilih Tipe</option>
                <option v-for="tipe in tipeListData" :key="tipe.id" :value="tipe.id">{{ tipe.nama_tipebrg }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Merek *</label>
              <select v-model="form.id_merkbrg" class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" disabled>Pilih Merek</option>
                <option v-for="merk in merekListData" :key="merk.id" :value="merk.id">{{ merk.nama_merkbrg }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
              <input 
                v-model="form.ket_brg" 
                class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                placeholder="Keterangan tambahan"
              />
            </div>
          </div>
          <div class="flex gap-2 mt-4 pt-3 border-t border-gray-300">
            <button 
              type="submit" 
              class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow font-semibold"
            >
              {{ isEdit ? '💾 Update Barang' : '✓ Simpan Barang' }}
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

      <!-- Table List Barang -->
      <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merek</th>
              <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(item, index) in barangListData" :key="item.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ item.nama_brg }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ item.category?.nama_katbrg || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ item.type?.nama_tipebrg || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">{{ item.brand?.nama_merkbrg || '-' }}</td>
              <td class="px-4 py-3 whitespace-nowrap text-sm text-center">
                <div class="flex items-center justify-center gap-2">
                  <button 
                    @click="handleEdit(item)" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow text-xs font-semibold"
                  >
                    ✏️ Edit
                  </button>
                  <button 
                    @click="handleDelete(item.id)" 
                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs font-semibold"
                  >
                    🗑️ Delete
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="barangListData.length === 0">
              <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                Belum ada data barang
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
import { useGetMasterBrg, useGetKategoriList, useGetTipeBarangList, useGetMerkList } from '@/services/queries';
import { useCreateMasterBarang, useUpdateMasterBarang, useDeleteMasterBarang } from '@/services/mutations';
import Swal from 'sweetalert2';

const { data: barangList, isLoading, error } = useGetMasterBrg();
const { data: kategoriList } = useGetKategoriList();
const { data: tipeList } = useGetTipeBarangList();
const { data: merekList } = useGetMerkList();

// Form state
const showForm = ref(false);
const form = ref({
  id: null,
  nama_brg: '',
  id_katbrg: '',
  id_tipebrg: '',
  id_merkbrg: '',
  ket_brg: '',
  status: 'active',
});
const isEdit = ref(false);

// Mutation hooks
const createBarang = useCreateMasterBarang();
const updateBarang = useUpdateMasterBarang();
const deleteBarang = useDeleteMasterBarang();

function resetForm() {
  form.value = {
    id: null,
    nama_brg: '',
    id_katbrg: '',
    id_tipebrg: '',
    id_merkbrg: '',
    ket_brg: '',
    status: 'active',
  };
  isEdit.value = false;
}

function handleEdit(item) {
  form.value = { ...item };
  isEdit.value = true;
  showForm.value = true;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function handleDelete(id) {
  Swal.fire({
    title: 'Yakin hapus barang ini?',
    text: "Data yang dihapus tidak bisa dikembalikan!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      deleteBarang.mutate(id, {
        onSuccess: resetForm
      });
    }
  });
}

function handleSubmit() {
  const payload = {
    nama_brg: form.value.nama_brg,
    id_katbrg: form.value.id_katbrg,
    id_tipebrg: form.value.id_tipebrg,
    id_merkbrg: form.value.id_merkbrg,
    ket_brg: form.value.ket_brg,
    status: form.value.status,
  };
  if (isEdit.value && form.value.id) {
    updateBarang.mutate({ id: form.value.id, data: payload }, {
      onSuccess: () => {
        resetForm();
        showForm.value = false;
      }
    });
  } else {
    createBarang.mutate(payload, {
      onSuccess: () => {
        resetForm();
        showForm.value = false;
      }
    });
  }
}

const barangListData = computed(() => {
  if (!barangList.value) return [];
  if (Array.isArray(barangList.value)) return barangList.value;
  if (barangList.value.data) return barangList.value.data;
  return [];
});

const kategoriListData = computed(() => {
  if (!kategoriList.value) return [];
  if (kategoriList.value.data) return kategoriList.value.data;
  if (Array.isArray(kategoriList.value)) return kategoriList.value;
  return [];
});

const tipeListData = computed(() => {
  if (!tipeList.value) return [];
  if (tipeList.value.data) return tipeList.value.data;
  if (Array.isArray(tipeList.value)) return tipeList.value;
  return [];
});

const merekListData = computed(() => {
  if (!merekList.value) return [];
  if (merekList.value.data) return merekList.value.data;
  if (Array.isArray(merekList.value)) return merekList.value;
  return [];
});
</script>
