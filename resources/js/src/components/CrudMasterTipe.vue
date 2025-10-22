<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div class="mb-2">
        <input v-model="form.nama" type="text" class="form-input" placeholder="Nama Tipe" required />
      </div>
      <button type="submit" class="btn btn-primary">{{ editId ? 'Update' : 'Tambah' }}</button>
      <button v-if="editId" type="button" class="btn btn-secondary ml-2" @click="resetForm">Batal</button>
    </form>
    <hr class="my-3" />
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="tipe in list" :key="tipe.id">
          <td>{{ tipe.nama }}</td>
          <td>
            <button class="btn btn-xs btn-info mr-1" @click="edit(tipe)">Edit</button>
            <button class="btn btn-xs btn-danger" @click="hapus(tipe.id)">Hapus</button>
          </td>
        </tr>
        <tr v-if="list.length === 0">
          <td colspan="2" class="text-center">Belum ada data</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue';
interface Tipe { id: number; nama: string; }
const list = ref<Tipe[]>([]);
const form = ref<Tipe>({ id: 0, nama: '' });
const editId = ref<number | null>(null);
function handleSubmit() {
  if (editId.value) {
    const idx = list.value.findIndex(b => b.id === editId.value);
    if (idx !== -1) list.value[idx] = { ...form.value };
  } else {
    form.value.id = Date.now();
    list.value.push({ ...form.value });
  }
  resetForm();
}
function edit(tipe: Tipe) {
  form.value = { ...tipe };
  editId.value = tipe.id;
}
function hapus(id: number) {
  list.value = list.value.filter(b => b.id !== id);
  if (editId.value === id) resetForm();
}
function resetForm() {
  form.value = { id: 0, nama: '' };
  editId.value = null;
}
</script>
