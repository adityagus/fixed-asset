<template>
    <ApprovalTabs
      :items="prApprovals"
      :columns="approvalColumns"
      :tabs="tabs"
      initialTab="Waiting List"
    >
      <template #status="{ item }">
        <span :class="{
          'badge bg-warning': (item as any).status === 'Waiting List',
          'badge bg-info': (item as any).status === 'Revised',
          'badge bg-danger': (item as any).status === 'Rejected',
          'badge bg-success': (item as any).status === 'Full Approved'
        }">
          {{ (item as any).status }}
        </span>
      </template>
    </ApprovalTabs>
    <!-- End Approval List Tabs & Table -->
</template>
<script lang="ts" setup>
// ...existing code...
import { ref } from 'vue';
import ApprovalTabs from '@/components/ApprovalTabs.vue';
import { useMeta } from '@/composables/use-meta';
useMeta({ title: 'Invoice Add' });

const tabs = ['Waiting List', 'Revised', 'Rejected', 'Full Approved'];
const prApprovals = ref([
  { id: 1, number: 'PR-2025-001', type: 'Pengadaan Laptop', requester: 'Alan Green', date: '2025-09-20', status: 'Waiting List', cabang: 'Blok M' },
  { id: 2, number: 'PR-2025-002', type: 'Penambahan Monitor', requester: 'Linda Nelson', date: '2025-09-22', status: 'Revised', cabang: 'Kepri' },
  { id: 3, number: 'PR-2025-003', type: 'Perangkat Meeting Room', requester: 'Susan', date: '2025-09-25', status: 'Rejected', cabang: 'Bogor' },
  { id: 4, number: 'PR-2025-004', type: 'Pengadaan Printer', requester: 'Budi', date: '2025-09-28', status: 'Full Approved', cabang: 'Harapan Indah' },
  { id: 5, number: 'PR-2025-005', type: 'Pengadaan Scanner', requester: 'Rina', date: '2025-09-29', status: 'Waiting List', cabang: 'Ciluar' },
]);
const approvalColumns = [
  { key: 'date', title: 'Date' },
  { key: 'type', title: 'Type' },
  { key: 'number', title: 'PR Number' },
  { key: 'requester', title: 'Requester' },
  { key: 'cabang', title: 'Cabang' },
  { key: 'status', title: 'Status' },
];
</script>
