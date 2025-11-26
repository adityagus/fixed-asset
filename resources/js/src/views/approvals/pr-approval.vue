<template>
  <ApprovalTabs
    :items="prApprovals"
    :columns="approvalColumns"
    :tabs="tabs"
    :isSuccess="isSuccess"
    :isPending="isPending"
    initialTab="Waiting Approval"
    @row-click="goToDetail"
  >
    <template #status="{ item }">
      <span
        :class="{
          'badge bg-info': normalizeStatus(item.status) === 'Waiting Approval',
          'badge bg-warning': normalizeStatus(item.status) === 'Revised',
          'badge bg-danger': normalizeStatus(item.status) === 'Rejected',
          'badge bg-success': normalizeStatus(item.status) === 'Full Approved'
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
import { useGetApprovalList } from '@/services/queries';
import { useRouter } from 'vue-router';
useMeta({ title: 'PR Approval' });

const tabs = [
  { label: 'Waiting Approval', value: 'Waiting Approval', indo: 'Menunggu Persetujuan' },
  { label: 'Revised', value: 'Revised', indo: 'Direvisi' },
  { label: 'Rejected', value: 'Rejected', indo: 'Ditolak' },
  { label: 'Full Approved', value: 'Approved', indo: 'Disetujui' },
  { label: 'Other', value: 'Other', indo: 'Lainnya' },
];

// Data fetching
const {data: PrApprovalRef, isPending: isPending, isSucces:isSuccess} = useGetApprovalList('pr');

console.log("PrApprovalRef", PrApprovalRef);

// Ambil data dari API approvalsList (berbentuk object status)
// Gabungkan semua array status menjadi satu array
const prApprovals = computed(() => {
  const data = PrApprovalRef.value?.data;
  return data || {};
});

const approvalColumns = [
  { key: 'pr_date', title: 'Tanggal' },
  { key: 'pr_number', title: 'Nomor PR' },
  { key: 'created_by', title: 'Pemohon' },
  { key: 'cabang', title: 'Cabang' },
  { key: 'status', title: 'Status' },
];

const router = useRouter();
function goToDetail(item: any) {
  router.push(`/apps/form/purchase-request/${item.pr_number}`);
}

// UTIL fungsi supaya badge/label selalu normal
function normalizeStatus(status: string): string {
  if (!status) return '';
  status = status.trim().toLowerCase();
  // Support variasi penulisan
  if (['waiting approval', 'waiting list', 'menunggu persetujuan'].includes(status)) return 'Waiting Approval';
  if (['revised', 'direvisi'].includes(status)) return 'Revised';
  if (['rejected', 'ditolak'].includes(status)) return 'Rejected';
  if (['full approved', 'disetujui'].includes(status)) return 'Full Approved';
  if (['other', 'lainnya'].includes(status)) return 'Other';
  return status.charAt(0).toUpperCase() + status.slice(1);
}
function getIndoStatus(status: string): string {
  const norm = normalizeStatus(status);
  const tab = tabs.find(t => t.label === norm);
  return tab?.indo ?? norm;
}
</script>