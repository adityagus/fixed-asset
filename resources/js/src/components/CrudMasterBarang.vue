<template>
  <div>
    <table class="table table-sm">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Kode</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="barang in list" :key="barang.id">
          <td>{{ barang.nama }}</td>
          <td>{{ barang.kode }}</td>
          <td>{{ barang.keterangan }}</td>
        </tr>
        <tr v-if="list.length === 0">
          <td colspan="3" class="text-center">Belum ada data</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script lang="ts" setup>
import { ref } from 'vue';
interface Barang { id: number; nama: string; kode: string; keterangan: string; }
const list = ref<Barang[]>([
  { id: 1, nama: 'Laptop Lenovo ThinkPad', kode: 'BRG001', keterangan: 'Laptop untuk staff IT' },
  { id: 2, nama: 'Monitor Samsung 24"', kode: 'BRG002', keterangan: 'Monitor untuk ruang meeting' },
  { id: 3, nama: 'Printer Epson L3110', kode: 'BRG003', keterangan: 'Printer untuk administrasi' },
]);
const form = ref<Barang>({ id: 0, nama: '', kode: '', keterangan: '' });
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
function edit(barang: Barang) {
  form.value = { ...barang };
  editId.value = barang.id;
}
function hapus(id: number) {
  list.value = list.value.filter(b => b.id !== id);
  if (editId.value === id) resetForm();
}
function resetForm() {
  form.value = { id: 0, nama: '', kode: '', keterangan: '' };
  editId.value = null;
}
</script>
