<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div class="mb-2">
        <input v-model="form.nama" type="text" class="form-input" placeholder="Nama Vendor" required />
      </div>
      <div class="mb-2">
        <input v-model="form.email" type="email" class="form-input" placeholder="Email Vendor" />
      </div>
      <div class="mb-2">
        <input v-model="form.telepon" type="text" class="form-input" placeholder="Telepon Vendor" />
      </div>
      <button type="submit" class="btn btn-primary">{{ editId ? 'Update' : 'Tambah' }}</button>
      <button v-if="editId" type="button" class="btn btn-secondary ml-2" @click="resetForm">Batal</button>
    </form>
    <hr class="my-3" />
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Telepon</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="vendor in list" :key="vendor.id">
          <td>{{ vendor.nama }}</td>
          <td>{{ vendor.email }}</td>
          <td>{{ vendor.telepon }}</td>
          <td>
            <button class="btn btn-xs btn-info mr-1" @click="edit(vendor)">Edit</button>
            <button class="btn btn-xs btn-danger" @click="hapus(vendor.id)">Hapus</button>
          </td>
        </tr>
        <tr v-if="list.length === 0">
          <td colspan="4" class="text-center">Belum ada data</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue';
interface Vendor { id: number; nama: string; email: string; telepon: string; }
const list = ref<Vendor[]>([
  { id: 1, nama: 'PT Sumber Makmur', email: 'info@sumbermakmur.com', telepon: '021-1234567' },
  { id: 2, nama: 'CV Berkah Jaya', email: 'berkah@jaya.com', telepon: '021-7654321' },
  { id: 3, nama: 'PT Teknologi Nusantara', email: 'admin@teknus.com', telepon: '0812-3456-7890' },
]);
const form = ref<Vendor>({ id: 0, nama: '', email: '', telepon: '' });
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
function edit(vendor: Vendor) {
  form.value = { ...vendor };
  editId.value = vendor.id;
}
function hapus(id: number) {
  list.value = list.value.filter(b => b.id !== id);
  if (editId.value === id) resetForm();
}
function resetForm() {
  form.value = { id: 0, nama: '', email: '', telepon: '' };
  editId.value = null;
}
</script>
