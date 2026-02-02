<template>
  <ApprovalTabs
    :items="prApprovals"
    :columns="approvalColumns"
    :tabs="tabs"
    :isSuccess="isSuccess"
    :isPending="isPending"
    :type="'purchase-request'"
    initialTab="Waiting Approval"
    @row-click="goToDetail"
  >
    <template #status="{ item }">
      <span
        :class="{
          'badge bg-info': normalizeStatus(item.status) === 'Waiting Approval',
          'badge bg-primary': normalizeStatus(item.status) === 'Proses',
          'badge bg-warning': normalizeStatus(item.status) === 'Revised',
          'badge bg-success': normalizeStatus(item.status) === 'Full Approved',
          'badge bg-danger': normalizeStatus(item.status) === 'Rejected'
        }"
      >
        {{ getIndoStatus(item.status) }}
      </span>
    </template>
  </ApprovalTabs>
</template>

<script lang="ts" setup>
import { ref, computed } from 'vue';
import ApprovalTabs from '@/components/ApprovalTabs.vue';
import { useMeta } from '@/composables/use-meta';
import { useGetApprovalList, useGetCabangList } from '@/services/queries';
import { useRouter } from 'vue-router';
import { useAppStore } from '@/stores/index';
import { getCabangList } from '@/services/api/masterService';

useMeta({ title: 'PR Approval' });

const store = useAppStore();
const username = computed(() => store.user?.username || '');

const tabs = [
  { label: 'Waiting Approval', value: 'Waiting Approval', indo: 'Menunggu Persetujuan' },
  { label: 'Proses', value: 'Other', indo: 'Proses' },
  { label: 'Revised', value: 'Revised', indo: 'Direvisi' },
  { label: 'Full Approved', value: 'Approved', indo: 'Disetujui' },
  { label: 'Rejected', value: 'Rejected', indo: 'Ditolak' },
];

// Data fetching
const {data: PrApprovalRef, isPending: isPending, isSuccess:isSuccess} = useGetApprovalList('purchase-request', username);
const { data: cabangList} = useGetCabangList();

console.log("PrApprovalRef", PrApprovalRef);

// Ambil data dari API approvalsList (berbentuk object status)
// Gabungkan semua array status menjadi satu array
const prApprovals = computed(() => {
  const data = PrApprovalRef.value.data;
//   data.value.forEach((item: any) => {
//     // Cari nama cabang berdasarkan cabang_id
//     const cabang = cabangList.value.find((cabang: any) => cabang.id === item.cabang_id);
//     item.cabang = cabang ? cabang.name : 'Unknown';
//   });
  return data || {};
});

const approvalColumns = [
  { key: 'request_time', title: 'Tanggal' },
  { key: 'pr_number', title: 'Nomor PR' },
  { key: 'created_by', title: 'Pemohon' },
  { key: 'nameLocation', title: 'Cabang' },
  { key: 'status', title: 'Status' },
];

const router = useRouter();
function goToDetail(item: any) {
  router.push({ path: `/apps/form/purchase-request/${item.pr_number}` , query: { from: 'approval' } });
}

// UTIL fungsi supaya badge/label selalu normal
function normalizeStatus(status: string): string {
  if (!status) return '';
  status = status.trim().toLowerCase();
  if (['waiting approval', 'waiting list', 'menunggu persetujuan'].includes(status)) return 'Waiting Approval';
  if (['other', 'proses'].includes(status)) return 'Proses';
  if (['revised', 'direvisi'].includes(status)) return 'Revised';
  if (['rejected', 'ditolak'].includes(status)) return 'Rejected';
  if (['full approved', 'disetujui'].includes(status)) return 'Full Approved';
  return status.charAt(0).toUpperCase() + status.slice(1);
}
function getIndoStatus(status: string): string {
  const norm = normalizeStatus(status);
  const tab = tabs.find(t => t.label === norm);
  return tab?.indo ?? norm;
}
</script>