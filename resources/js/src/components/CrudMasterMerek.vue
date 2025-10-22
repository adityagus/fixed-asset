<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div class="mb-2">
        <input v-model="form.nama" type="text" class="form-input" placeholder="Nama Merek" required />
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
        <tr v-for="merek in list" :key="merek.id">
          <td>{{ merek.nama }}</td>
          <td>
            <button class="btn btn-xs btn-info mr-1" @click="edit(merek)">Edit</button>
            <button class="btn btn-xs btn-danger" @click="hapus(merek.id)">Hapus</button>
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
interface Merek { id: number; nama: string; }
const list = ref<Merek[]>([]);
const form = ref<Merek>({ id: 0, nama: '' });
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
function edit(merek: Merek) {
  form.value = { ...merek };
  editId.value = merek.id;
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
