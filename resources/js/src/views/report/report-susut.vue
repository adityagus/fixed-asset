<template>
  <div class="panel p-6 bg-white">
    <div class="flex justify-between items-center mb-4">
      <div>
        <h2 class="text-2xl font-bold mb-1">LAPORAN DETAIL ASET</h2>
        <div class="text-base">Head Office</div>
      </div>
      <button class="btn btn-primary" @click="printReport">
        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" class="inline mr-2">
          <path d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827" stroke="currentColor" stroke-width="1.5"/>
          <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path d="M18 14V16C18 18.8284 18 20.2426 17.1213 21.1213C16.2426 22 14.8284 22 12 22C9.17157 22 7.75736 22 6.87868 21.1213C6 20.2426 6 18.8284 6 16V14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path opacity="0.5" d="M17.9827 6C17.9359 4.44655 17.7626 3.51998 17.1213 2.87868C16.2427 2 14.8284 2 12 2C9.17158 2 7.75737 2 6.87869 2.87868C6.23739 3.51998 6.06414 4.44655 6.01733 6" stroke="currentColor" stroke-width="1.5"/>
          <circle opacity="0.5" cx="17" cy="10" r="1" fill="currentColor"/>
          <path opacity="0.5" d="M15 16.5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          <path opacity="0.5" d="M13 19H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        Print
      </button>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-300 text-xs" id="print-area">
        <thead>
          <tr class="bg-gray-100">
            <th class="border px-2 py-1">No</th>
            <th class="border px-2 py-1">No Transaksi</th>
            <th class="border px-2 py-1">Kategori</th>
            <th class="border px-2 py-1">Kode Aset</th>
            <th class="border px-2 py-1">Nama Aset</th>
            <th class="border px-2 py-1">Lokasi</th>
            <th class="border px-2 py-1">Tgl Perolehan</th>
            <th class="border px-2 py-1">Usia Ekonomis (Bulan)</th>
            <th class="border px-2 py-1">Nilai Perolehan</th>
            <th class="border px-2 py-1">Akm Peny per Bulan</th>
            <th class="border px-2 py-1">Total Akm Penyusutan</th>
            <th class="border px-2 py-1">Nilai Buku</th>
            <th class="border px-2 py-1">Tanggal Akhir Usia Aset</th>
            <th class="border px-2 py-1">Keterangan/PIC</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(item, idx) in assets" :key="item.id">
            <td class="border px-2 py-1 text-center">{{ idx + 1 }}</td>
            <td class="border px-2 py-1">{{ item.no_transaksi }}</td>
            <td class="border px-2 py-1">{{ item.kategori }}</td>
            <td class="border px-2 py-1">{{ item.kode_aset }}</td>
            <td class="border px-2 py-1">{{ item.nama_aset }}</td>
            <td class="border px-2 py-1">{{ item.lokasi }}</td>
            <td class="border px-2 py-1">{{ formatDate(item.tgl_perolehan) }}</td>
            <td class="border px-2 py-1 text-center">{{ item.usia_ekonomis }}</td>
            <td class="border px-2 py-1 text-right">{{ formatNumber(item.nilai_perolehan) }}</td>
            <td class="border px-2 py-1 text-right">{{ formatNumber(item.akm_peny_per_bulan) }}</td>
            <td class="border px-2 py-1 text-right">{{ formatNumber(item.total_akm_penyusutan) }}</td>
            <td class="border px-2 py-1 text-right">{{ formatNumber(item.nilai_buku) }}</td>
            <td class="border px-2 py-1">{{ formatDate(item.tgl_akhir_usia) }}</td>
            <td class="border px-2 py-1">{{ item.keterangan }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

// Dummy data, ganti dengan fetch API jika sudah ada endpoint
const assets = ref([
  {
    id: 1,
    no_transaksi: 'TRX-001',
    kategori: 'Elektronik',
    kode_aset: 'AST-001',
    nama_aset: 'Laptop Lenovo',
    lokasi: 'Jakarta',
    tgl_perolehan: '2022-01-10',
    usia_ekonomis: 36,
    nilai_perolehan: 12000000,
    akm_peny_per_bulan: 333333,
    total_akm_penyusutan: 4000000,
    nilai_buku: 8000000,
    tgl_akhir_usia: '2025-01-10',
    keterangan: 'IT Dept',
  },
  // ...tambahkan data lain sesuai kebutuhan...
]);

const formatNumber = (val: number) => {
  return val?.toLocaleString('id-ID');
};
const formatDate = (val: string) => {
  if (!val) return '';
  const d = new Date(val);
  return d.toLocaleDateString('id-ID');
};

const printReport = () => {
  const printContents = document.getElementById('print-area')?.outerHTML;
  const win = window.open('', '', 'width=1200,height=800');
  win?.document.write(`
    <html>
      <head>
        <title>Laporan Detail Aset</title>
        <style>
          body { font-family: Arial, sans-serif; color: #222; }
          table { border-collapse: collapse; width: 100%; }
          th, td { border: 1px solid #ccc; padding: 6px; font-size: 13px; }
          th { background: #f8f8f8; }
          tr:nth-child(2n-1) { background: #f7f7f7; }
        </style>
      </head>
      <body>
        <h2 style="text-align:center;">LAPORAN DETAIL ASET</h2>
        <div style="text-align:center;">Head Office</div>
        ${printContents}
      </body>
    </html>
  `);
  win?.document.close();
  win?.focus();
  win?.print();
};
</script>