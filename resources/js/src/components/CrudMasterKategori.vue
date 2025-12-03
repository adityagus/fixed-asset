<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <form @submit.prevent="handleSubmit">
        <div class="mb-2">
          <input v-model="form.kategori" type="text" class="form-input" placeholder="Nama Kategori" required />
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
            <th>Nama Kategori</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(kategori, index) in kategoriListData" :key="kategori.id_kategori || index">
            <td>{{ index + 1 }}</td>
            <td>{{ kategori.nama_katbrg }}</td>
            <td class='flex gap-3'>
              <button class="btn btn-xs btn-info" @click="edit(kategori)">Edit</button>
              <button class="btn btn-xs btn-danger" @click="hapus(kategori.id_kategori)">Hapus</button>
            </td>
          </tr>
          <tr v-if="kategoriListData.length === 0">
            <td colspan="3" class="text-center">Belum ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useGetKategoriList } from '@/services/queries';

interface Kategori { 
  id_kategori?: number; 
  kategori?: string;
}

const { data: kategoriList, isLoading, error } = useGetKategoriList();

const form = ref<Kategori>({ kategori: '' });
const editId = ref<number | null>(null);

const kategoriListData = computed(() => {
  if (!kategoriList.value) return [];
  if (kategoriList.value.data) return kategoriList.value.data;
  if (Array.isArray(kategoriList.value)) return kategoriList.value;
  return [];
});

function handleSubmit() {
  // TODO: Implement API call
  console.log('Submit:', form.value);
  resetForm();
}

function edit(kategori: Kategori) {
  form.value = { kategori: kategori.kategori };
  editId.value = kategori.id_kategori || null;
}

function hapus(id: number) {
  if (confirm('Yakin ingin menghapus data ini?')) {
    console.log('Delete:', id);
  }
}

function resetForm() {
  form.value = { kategori: '' };
  editId.value = null;
}
</script>
