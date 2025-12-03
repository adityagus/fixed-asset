<template>
  <div class="panel p-6">
    <h2 class="text-2xl font-bold mb-6">Laporan Aset</h2>
    <!-- Navigasi Tab -->
    <div class="mt-3 flex flex-wrap border-b border-white-light dark:border-[#191e3a] mb-6">
      <button
        :class="[
          'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
          activeTab === 'all' ? 'border-b !border-primary text-primary !outline-none' : ''
        ]"
        @click="activeTab = 'all'"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" /><path d="M8 12h8M12 8v8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
        <span>Semua<br />Aset</span>
      </button>
      <button
        :class="[
          'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
          activeTab === 'branch' ? 'border-b !border-primary text-primary !outline-none' : ''
        ]"
        @click="activeTab = 'branch'"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2"><rect x="4" y="4" width="16" height="16" rx="4" stroke="currentColor" stroke-width="1.5"/><path d="M8 8h8v8H8z" stroke="currentColor" stroke-width="1.5"/></svg>
        <span>Berdasarkan<br />Cabang</span>
      </button>
      <button
        :class="[
          'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
          activeTab === 'warehouse' ? 'border-b !border-primary text-primary !outline-none' : ''
        ]"
        @click="activeTab = 'warehouse'"
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2"><rect x="3" y="7" width="18" height="10" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 7V5a5 5 0 0110 0v2" stroke="currentColor" stroke-width="1.5"/></svg>
        <span>Gudang</span>
      </button>
    </div>
    <div class="flex items-center justify-between md:flex-row flex-col mb-4.5 gap-5">
      <div class="flex items-center mb-4">
        <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('csv')">CSV</button>
        <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('txt')">TXT</button>
        <vue3-json-excel class="btn btn-primary btn-sm m-1 cursor-pointer" name="assets.xls" :fields="excelColumns()" :json-data="excelItems()">EXCEL</vue3-json-excel>
        <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('print')">CETAK</button>
      </div>
      <div class="flex gap-3">
        <button class="btn btn-outline-primary" @click="showBarcodeScanner = true">Scan Barcode Aset</button>
        <input v-if="activeTab === 'all'" v-model="searchAll" type="text" class="form-input w-auto" placeholder="Cari aset..." />
        <input v-if="activeTab === 'branch'" v-model="searchBranch" type="text" class="form-input w-auto" placeholder="Cari aset cabang..." />
        <input v-if="activeTab === 'warehouse'" v-model="searchWarehouse" type="text" class="form-input w-auto" placeholder="Cari aset gudang..." />
      </div>
    </div>
    <div v-if="activeTab === 'all'">
      <h3 class="text-lg font-semibold mb-2">Semua Aset</h3>
      <vue3-datatable :rows="allAssets" :columns="assetCols" :search="searchAll" skin="whitespace-nowrap bh-table-hover" />
    </div>
    <div v-if="activeTab === 'branch'">
      <h3 class="text-lg font-semibold mb-2">Aset Berdasarkan Cabang</h3>
      <select v-model="selectedBranch" class="form-select mb-4 w-64">
        <option value="">Pilih Cabang</option>
        <option v-for="branch in branches" :key="branch" :value="branch">{{ branch }}</option>
      </select>
      <vue3-datatable :rows="branchAssets" :columns="assetCols" :search="searchBranch" skin="whitespace-nowrap bh-table-hover" />
    </div>
    <div v-if="activeTab === 'warehouse'">
      <h3 class="text-lg font-semibold mb-2">Aset di Gudang</h3>
      <vue3-datatable :rows="warehouseAssets" :columns="assetCols" :search="searchWarehouse" skin="whitespace-nowrap bh-table-hover" />
    </div>
    <!-- Modal Kamera -->
    <div v-if="showCamera" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 relative w-full max-w-md">
        <button class="absolute top-2 right-2 text-gray-500" @click="closeCamera">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Cek Aset dengan Kamera</h3>
        <div v-if="!capturedImage">
          <video ref="videoRef" autoplay playsinline class="w-full rounded mb-4"></video>
          <div class="flex justify-center gap-2">
            <button class="btn btn-primary" @click="capturePhoto">Ambil Foto</button>
            <button class="btn btn-outline-secondary" @click="closeCamera">Batal</button>
          </div>
        </div>
        <div v-else>
          <img :src="capturedImage" class="w-full rounded mb-4" />
          <div class="flex justify-center gap-2">
            <button class="btn btn-outline-primary" @click="retakePhoto">Ulangi</button>
            <button class="btn btn-primary" @click="closeCamera">Selesai</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Scan Barcode -->
    <div v-if="showBarcodeScanner" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 relative w-full max-w-md">
        <button class="absolute top-2 right-2 text-gray-500" @click="closeBarcodeScanner">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Scan Barcode Aset</h3>
        <div id="barcode-scanner" class="w-full rounded mb-4"></div>
        <div class="flex justify-center gap-2">
          <button class="btn btn-outline-secondary" @click="closeBarcodeScanner">Batal</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
// Komponen Laporan Aset Lengkap
import { ref, computed, watch, nextTick, watchEffect } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import Vue3JsonExcel from 'vue3-json-excel';
import { Html5Qrcode } from 'html5-qrcode';
import { useRouter } from 'vue-router';
import { useGetAssetReport } from '@/services/queries';
// State
const router = useRouter();
const activeTab = ref('all');
const searchAll = ref('');
const searchBranch = ref('');
const searchWarehouse = ref('');
const selectedBranch = ref('');
const showCamera = ref(false);
const showBarcodeScanner = ref(false);
const videoRef = ref<HTMLVideoElement | null>(null);
const capturedImage = ref<string | null>(null);
let stream: MediaStream | null = null;
let barcodeScanner: Html5Qrcode | null = null;
const branches = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Bali'];

// Kolom Table (lihat custom fields)
const assetCols = [
  { title: 'No Transaksi', field: 'register_asset_number', sortable: true },
  { title: 'Kategori', field: 'item.category.nama_katbrg', sortable: true },
  { title: 'Kode Aset', field: 'asset_number', sortable: true },
  { title: 'Nama Aset', field: 'item.nama_brg', sortable: true },
  { title: 'Lokasi', field: 'location', sortable: true },
  { title: 'Tgl Perolehan', field: 'registration_asset.ra_date', sortable: true },
  { title: 'Usia Ekonomis (Bulan)', field: 'item.category.umur', sortable: true },
  {
    title: 'Nilai Perolehan',
    field: 'nilai_perolehan',
    sortable: true,
    render: (row: any) => {
      // Tampilkan dengan pemisah ribuan di tabel
      const val = typeof row.nilai_perolehan === 'number'
        ? row.nilai_perolehan
        : (row.susut && typeof row.susut.total_umur === 'number' && typeof row.susut.nom_susut === 'number'
          ? row.susut.total_umur * row.susut.nom_susut //nilai perolehan
          : null);
      return val !== null ? val.toLocaleString('id-ID') : '-';
    }
  },
  { title: 'Akm Peny per Bulan', field: 'susut.nom_susut', sortable: true },
  {
    title: 'Total Akm Penyusutan',
    field: 'total_akm_penyusutan',
    sortable: true,
    render: (row: any) => {
      const val = typeof row.total_akm_penyusutan === 'number'
        ? row.total_akm_penyusutan
        : null;
      return val !== null ? val.toLocaleString('id-ID') : '-';
    }
  },
  {
    title: 'Nilai Buku',
    field: 'nilai_buku',
    sortable: true,
    render: (row: any) => {
      const val = typeof row.nilai_buku === 'number'
        ? row.nilai_buku
        : null;
      return val !== null ? val.toLocaleString('id-ID') : '-';
    }
  },
  { title: 'Tanggal Akhir Usia Aset', field: 'tgl_akhir', sortable: true },
  { title: 'Keterangan/PIC', field: 'assigned_to', sortable: true },
];

// Ambil data aset dari API
const { data: assetReport } = useGetAssetReport();

// Watch effect untuk menambahkan data custom di assetReport
watchEffect(() => {
  if (assetReport.value && Array.isArray(assetReport.value.data)) {
    assetReport.value.data.forEach((item: any) => {
      if (!item.custom_field) {
        item.custom_field = 'Custom Value';
      }
      // Contoh: hitung nilai buku jika belum ada
      if (item.susut && typeof item.susut.total_umur === 'number' && typeof item.susut.nom_susut === 'number') {
        
        // Contoh: tambahkan properti custom 'custom_field'
      item.nilai_perolehan = item.susut.total_umur * item.susut.nom_susut;
      item.total_akm_penyusutan = (item.susut.total_umur - item.susut.sisa_umur) * item.susut.nom_susut;
      item.nilai_buku = item.susut.sisa_umur * item.susut.nom_susut;
      
      
      
      // Konversi kode PHP ke JavaScript:
      // $fd = explode('-',$a->tglref);
      // $tglreg = $fd[2].'-'.$fd[1].'-'.$fd[0];
      // $tglakhir = date('d-m-Y',mktime(0,0,0,($a->total_umur + (int)$fd[1]),(int)$fd[2],(int)$fd[0]));

      // ?>
      
      }
    });
  }
  
  console.log('assetReport updated:', assetReport.value);
});


const allAssets = computed(() => assetReport.value?.data ?? []);
const branchAssets = computed(() => {
  if (!selectedBranch.value) return [];
  return allAssets.value.filter(a => a.location?.toLowerCase() === selectedBranch.value.toLowerCase());
});
const warehouseAssets = computed(() => allAssets.value.filter(a => a.status === 'Gudang'));

// Fungsi ekspor/print table
const exportTable = (type: string) => {
  let columns = assetCols.map((d) => d.field);
  let records: any[] = [];
  if (activeTab.value === 'all') records = allAssets.value;
  else if (activeTab.value === 'branch') records = branchAssets.value;
  else if (activeTab.value === 'warehouse') records = warehouseAssets.value;
  let filename = 'assets';
  let newVariable: any = window.navigator;
  // Util capitalize
  const capitalize = (text: string) => text.replace('_', ' ').replace('-', ' ').toLowerCase()
    .split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ');

  if (type == 'csv' || type == 'txt') {
    let coldelimiter = type == 'csv' ? ';' : ',';
    let linedelimiter = '\n';
    let result = columns.map((d) => capitalize(d)).join(coldelimiter);
    result += linedelimiter;
    records.forEach((item) => {
      columns.forEach((d: any, index) => {
        if (index > 0) result += coldelimiter;
        let val = getNested(item, d);
        result += val;
      });
      result += linedelimiter;
    });
    if (result == null) return;
    let mime = type == 'csv' ? 'application/csv' : 'application/txt';
    if (!result.match(/^data:text\/(csv|txt)/i) && !newVariable.msSaveOrOpenBlob) {
      var data = `data:${mime};charset=utf-8,` + encodeURIComponent(result);
      var link = document.createElement('a');
      link.setAttribute('href', data);
      link.setAttribute('download', filename + (type == 'csv' ? '.csv' : '.txt'));
      link.click();
    } else {
      var blob = new Blob([result]);
      if (newVariable.msSaveOrOpenBlob) newVariable.msSaveBlob(blob, filename + (type == 'csv' ? '.csv' : '.txt'));
    }
  } else if (type == 'print') {
    var rowhtml = '<p>' + filename + '</p>';
    rowhtml += '<table style="width: 100%; " cellpadding="0" cellcpasing="0"><thead><tr style="color: #515365; background: #eff5ff; -webkit-print-color-adjust: exact; print-color-adjust: exact; "> ';
    columns.forEach((d) => { rowhtml += '<th>' + capitalize(d) + '</th>'; });
    rowhtml += '</tr></thead><tbody>';
    records.forEach((item) => {
      rowhtml += '<tr>';
      columns.forEach((d: any) => {
        let val = getNested(item, d);
        rowhtml += '<td>' + val + '</td>';
      });
      rowhtml += '</tr>';
    });
    rowhtml += '<style>body {font-family:Arial; color:#495057;}p{text-align:center;font-size:18px;font-weight:bold;margin:15px;}table{ border-collapse: collapse; border-spacing: 0; }th,td{font-size:12px;text-align:left;padding: 4px;}th{padding:8px 4px;}tr:nth-child(2n-1){background:#f7f7f7; }</style>';
    rowhtml += '</tbody></table>';
    var winPrint: any = window.open('', '', 'left=0,top=0,width=1000,height=600,toolbar=0,scrollbars=0,status=0');
    winPrint.document.write('<title>Print</title>' + rowhtml);
    winPrint.document.close();
    winPrint.focus();
    winPrint.print();
  }
};

// Kolom untuk ekspor Excel
const excelColumns = () => {
  return {
    'No Transaksi': 'register_asset_number',
    'Kategori': 'item.category.nama_katbrg',
    'Kode Aset': 'asset_number',
    'Nama Aset': 'item.nama_brg',
    'Lokasi': 'location',
    'Tgl Perolehan': 'registrationAsset.ra_date',
    'Usia Ekonomis (Bulan)': 'item.category.umur',
    'Nilai Perolehan': 'nilai_perolehan',
    'Akm Peny per Bulan': 'susut.nom_susut',
    'Total Akm Penyusutan': 'total_depreciation',
    'Nilai Buku': 'book_value',
    'Tanggal Akhir Usia Aset': 'end_of_life_date',
    'Keterangan/PIC': 'pic',
  };
};
// Data untuk ekspor Excel
const excelItems = () => {
  if (activeTab.value === 'all') return allAssets.value;
  else if (activeTab.value === 'branch') return branchAssets.value;
  else if (activeTab.value === 'warehouse') return warehouseAssets.value;
  return [];
};

// Fungsi getNested: untuk export/print, tanpa pemisah ribuan
function getNested(obj: any, path: string) {
  if (path === 'nilai_perolehan') {
    if (typeof obj.nilai_perolehan === 'number') {
      return obj.nilai_perolehan; // tanpa .toLocaleString
    }
    const susut = obj.susut;
    if (susut && typeof susut.total_umur === 'number' && typeof susut.nom_susut === 'number') {
      return susut.total_umur * susut.nom_susut;
    }
    return '-';
  }
  if (path === 'total_akm_penyusutan') {
    if (typeof obj.total_akm_penyusutan === 'number') {
      return obj.total_akm_penyusutan;
    }
    return '-';
  }
  if (path === 'nilai_buku') {
    if (typeof obj.nilai_buku === 'number') {
      return obj.nilai_buku;
    }
    return '-';
  }
  // Nested biasa (dot notation)
  return path.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : ''), obj);
}

// Kamera, Barcode, dsb (boleh diabaikan jika fokus laporan)
const openCamera = async () => {
  if (videoRef.value) {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      videoRef.value.srcObject = stream;
    } catch (err) {
      alert('Tidak dapat mengakses kamera');
      showCamera.value = false;
    }
  }
};
const closeCamera = () => {
  showCamera.value = false;
  capturedImage.value = null;
  if (stream) { stream.getTracks().forEach(track => track.stop()); stream = null; }
};
const capturePhoto = () => {
  if (!videoRef.value) return;
  const canvas = document.createElement('canvas');
  canvas.width = videoRef.value.videoWidth;
  canvas.height = videoRef.value.videoHeight;
  const ctx = canvas.getContext('2d');
  if (ctx) {
    ctx.drawImage(videoRef.value, 0, 0, canvas.width, canvas.height);
    capturedImage.value = canvas.toDataURL('image/png');
  }
};
const retakePhoto = () => { capturedImage.value = null; };
const closeBarcodeScanner = async () => {
  showBarcodeScanner.value = false;
  const videoElems = document.querySelectorAll('#barcode-scanner video') as NodeListOf<HTMLVideoElement>;
  videoElems.forEach((video) => { if (video.srcObject) { const tracks = (video.srcObject as MediaStream).getTracks(); tracks.forEach((track) => track.stop()); video.srcObject = null; } });
  const allVideos = document.querySelectorAll('video') as NodeListOf<HTMLVideoElement>;
  allVideos.forEach((video) => { if (video.srcObject) { const tracks = (video.srcObject as MediaStream).getTracks(); tracks.forEach((track) => track.stop()); video.srcObject = null; } });
  if (barcodeScanner) { if (barcodeScanner.isScanning) { try { await barcodeScanner.stop(); } catch (e) {} } barcodeScanner.clear(); barcodeScanner = null; }
};

watch(showCamera, (val) => { if (val) { setTimeout(openCamera, 300); } else { closeCamera(); } });
watch(showBarcodeScanner, (val) => {
  if (val) {
    nextTick(() => {
      if (!barcodeScanner) {
        barcodeScanner = new Html5Qrcode("barcode-scanner");
        barcodeScanner.start(
          { facingMode: "environment" },
          { fps: 10, qrbox: 250 },
          (decodedText: string) => {
            closeBarcodeScanner();
            router.push(`/asset/detail/${decodedText}`);
          },
          () => {}
        ).catch((err) => {
          if (String(err).includes('already under transition')) {
            barcodeScanner!.stop().then(() => {
              barcodeScanner!.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText: string) => {
                  closeBarcodeScanner();
                  router.push(`/asset/detail/${decodedText}`);
                },
                () => {}
              );
            });
          } else {
            alert('Tidak dapat mengakses kamera untuk pemindaian barcode');
            closeBarcodeScanner();
          }
        });
      }
    });
  } else {
    closeBarcodeScanner();
  }
});
</script>