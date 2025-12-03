<template>
  <div>
    <div v-if="isLoading">Loading...</div>
    <div v-else-if="error">Error: {{ error.message }}</div>
    <div v-else>
      <form @submit.prevent="handleSubmit">
        <div class="mb-2">
          <input v-model="form.nama_merkbrg" type="text" class="form-input" placeholder="Nama Merek" required />
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
            <th>Nama Merek</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(merek, index) in merekListData" :key="merek.id_merkbrg || index">
            <td>{{ index + 1 }}</td>
            <td>{{ merek.nama_merkbrg }}</td>
            <td class='flex gap-3'>
              <button class="btn btn-xs btn-info" @click="edit(merek)">Edit</button>
              <button class="btn btn-xs btn-danger" @click="hapus(merek.id_merkbrg)">Hapus</button>
            </td>
          </tr>
          <tr v-if="merekListData.length === 0">
            <td colspan="3" class="text-center">Belum ada data</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useGetMerkList, useCreateMerk, useUpdateMerk, useDeleteMerk } from '@/services/queries';

interface Merek { 
  id_merkbrg?: number; 
  nama_merkbrg?: string;
}

const { data: merekList, isLoading, error } = useGetMerkList();
const createMutation = useCreateMerk();
const updateMutation = useUpdateMerk();
const deleteMutation = useDeleteMerk();

const form = ref<Merek>({ nama_merkbrg: '' });
const editId = ref<number | null>(null);

const merekListData = computed(() => {
  if (!merekList.value) return [];
  if (merekList.value.data) return merekList.value.data;
  if (Array.isArray(merekList.value)) return merekList.value;
  return [];
});

async function handleSubmit() {
  try {
    if (editId.value) {
      await updateMutation.mutateAsync({
        id: editId.value,
        data: { nama_merkbrg: form.value.nama_merkbrg || '' }
      });
      alert('Merek berhasil diupdate');
    } else {
      await createMutation.mutateAsync({ nama_merkbrg: form.value.nama_merkbrg || '' });
      alert('Merek berhasil ditambahkan');
    }
    resetForm();
  } catch (error) {
    console.error('Error:', error);
    alert('Terjadi kesalahan');
  }
}

function edit(merek: Merek) {
  form.value = { nama_merkbrg: merek.nama_merkbrg };
  editId.value = merek.id_merkbrg || null;
}

async function hapus(id: number) {
  if (confirm('Yakin ingin menghapus data ini?')) {
    try {
      await deleteMutation.mutateAsync(id);
      alert('Merek berhasil dihapus');
      if (editId.value === id) resetForm();
    } catch (error) {
      console.error('Error:', error);
      alert('Gagal menghapus data');
    }
  }
}

function resetForm() {
  form.value = { nama_merkbrg: '' };
  editId.value = null;
}
</script>
