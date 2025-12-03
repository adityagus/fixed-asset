<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <form @submit.prevent="handleSubmit">
        <div class="mb-2">
          <input v-model="form.nama_type" type="text" class="form-input" placeholder="Nama Tipe" required />
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
            <th>Nama Tipe</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(tipe, index) in tipeListData" :key="tipe.id || index">
            <td>{{ index + 1 }}</td>
            <td>{{ tipe.nama_tipebrg }}</td>
            <td class='flex gap-3'>
              <button class="btn btn-xs btn-info" @click="edit(tipe)">Edit</button>
              <button class="btn btn-xs btn-danger" @click="hapus(tipe.id)">Hapus</button>
            </td>
          </tr>
          <tr v-if="tipeListData.length === 0">
            <td colspan="3" class="text-center">Belum ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useGetTipeBarangList } from '@/services/queries';

interface Tipe { 
  id?: number; 
  nama_type?: string;
}

const { data: tipeList, isLoading, error } = useGetTipeBarangList();

const form = ref<Tipe>({ nama_type: '' });
const editId = ref<number | null>(null);

const tipeListData = computed(() => {
  if (!tipeList.value) return [];
  if (tipeList.value.data) return tipeList.value.data;
  if (Array.isArray(tipeList.value)) return tipeList.value;
  return [];
});

function handleSubmit() {
  // TODO: Implement API call
  console.log('Submit:', form.value);
  resetForm();
}

function edit(tipe: Tipe) {
  form.value = { nama_type: tipe.nama_type };
  editId.value = tipe.id || null;
}

function hapus(id: number) {
  if (confirm('Yakin ingin menghapus data ini?')) {
    console.log('Delete:', id);
  }
}

function resetForm() {
  form.value = { nama_type: '' };
  editId.value = null;
}
</script>
