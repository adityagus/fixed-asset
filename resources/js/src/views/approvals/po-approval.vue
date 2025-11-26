<template>
  <ApprovalTabs
    :items="poApprovals"
    :columns="approvalColumns"
    :tabs="tabs"
    :isSuccess="isSuccess"
    :isPending="isPending"
    initialTab="Waiting Approval"
    @row-click="goToDetail"
  >
  <template #created_by="{ item }">
  {{ item.purchase_request?.created_by || '-' }}
</template>
<template #cabang="{ item }">
  {{ item.purchase_request?.cabang || '-' }}
</template>
    <template #status="{ item }">
      <span
        :class="{
          'badge bg-info': normalizeStatus(item.status) === 'Waiting Approval',
          'badge bg-warning': normalizeStatus(item.status) === 'Revised',
          'badge bg-danger': normalizeStatus(item.status) === 'Rejected',
          'badge bg-success': normalizeStatus(item.status) === 'Full Approved',
          'badge bg-secondary': normalizeStatus(item.status) === 'Other'
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
useMeta({ title: 'PO Approval' });

const tabs = [
  { label: 'Waiting Approval', value: 'Waiting Approval', indo: 'Menunggu Persetujuan' },
  { label: 'Revised', value: 'Revised', indo: 'Direvisi' },
  { label: 'Rejected', value: 'Rejected', indo: 'Ditolak' },
  { label: 'Full Approved', value: 'Approved', indo: 'Disetujui' },
  { label: 'Other', value: 'Other', indo: 'Lainnya' },
];

const router = useRouter();
function goToDetail(item: any) {
  router.push(`/apps/form/purchase-order/${item.po_number}`);
}

// Data fetching
const {data: PoApprovalRef, isPending, isSucces: isSuccess} = useGetApprovalList('po');

const poApprovals = computed(() => {
  const data = PoApprovalRef.value?.data;
  return data || {};
});

const approvalColumns = [
  { key: 'created_at', title: 'Tanggal' },
  { key: 'po_number', title: 'Nomor PO' },
  { key: 'created_by', title: 'Pemohon' },
  { key: 'cabang', title: 'Cabang' },
  { key: 'status', title: 'Status' },
];

// UTIL fungsi supaya badge/label selalu normal
function normalizeStatus(status: string): string {
  if (!status) return '';
  status = status.trim().toLowerCase();
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