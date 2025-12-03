<template>
    <ApprovalTabs :items="raApprovals" :columns="approvalColumns" :tabs="tabs" :isSuccess="isSuccess" :isPending="isPending" @row-click="goToDetail" initialTab="Waiting Approval">
        <template #created_by="{ item }">
            {{ item.purchase_order?.purchase_request?.created_by || '-' }}
        </template>
        <template #cabang="{ item }">
            {{ item.purchase_order?.purchase_request?.cabang || '-' }}
        </template>
        <template #status="{ item }">
            <span :class="{
                'badge bg-info': normalizeStatus(item.status) === 'Waiting Approval',
                'badge bg-primary': normalizeStatus(item.status) === 'Proses',
                'badge bg-warning': normalizeStatus(item.status) === 'Revised',
                'badge bg-success': normalizeStatus(item.status) === 'Approved',
                'badge bg-danger': normalizeStatus(item.status) === 'Rejected'
            }">
                {{ getIndoStatus(item.status) }}
            </span>
        </template>
    </ApprovalTabs>
</template>
<script lang="ts" setup>
import { ref, computed } from 'vue';
import { useMeta } from '@/composables/use-meta';
import ApprovalTabs from '@/components/ApprovalTabs.vue';
import { useGetApprovalList } from '@/services/queries';
import { useRouter } from 'vue-router';
useMeta({ title: 'RA Approval' });

const tabs = [
    { label: 'Waiting Approval', value: 'Waiting Approval', indo: 'Menunggu Persetujuan' },
    { label: 'Proses', value: 'Other', indo: 'Proses' },
    { label: 'Revised', value: 'Revised', indo: 'Direvisi' },
    { label: 'Approved', value: 'Approved', indo: 'Disetujui' },
    { label: 'Rejected', value: 'Rejected', indo: 'Ditolak' },
];

const router = useRouter();
function goToDetail(item: any) {
  router.push({
    path: `/apps/form/registration-asset/${item.ra_number}`,
    query: { from: 'approval' }
  });
}

// Data fetching
const { data: RaApprovalRef, isPending, isSuccess } = useGetApprovalList('registration-asset');

const raApprovals = computed(() => {
    const data = RaApprovalRef.value?.data;
    return data || {};
});

const approvalColumns = [
    { key: 'request_time', title: 'Tanggal' },
    { key: 'ra_number', title: 'Nomor RA' },
    { key: 'created_by', title: 'Pemohon' },
    { key: 'cabang', title: 'Cabang' },
    { key: 'status', title: 'Status' },
];

// UTIL fungsi supaya badge/label selalu normal
function normalizeStatus(status: string): string {
    if (!status) return '';
    status = status.trim().toLowerCase();
    if (['waiting approval', 'waiting list', 'menunggu persetujuan'].includes(status)) return 'Waiting Approval';
    if (['other', 'proses'].includes(status)) return 'Proses';
    if (['revised', 'direvisi'].includes(status)) return 'Revised';
    if (['approved', 'full approved', 'disetujui'].includes(status)) return 'Approved';
    if (['rejected', 'ditolak'].includes(status)) return 'Rejected';
    return status.charAt(0).toUpperCase() + status.slice(1);
}
function getIndoStatus(status: string): string {
    const norm = normalizeStatus(status);
    const tab = tabs.find(t => t.label === norm);
    return tab?.indo ?? norm;
}
</script>
