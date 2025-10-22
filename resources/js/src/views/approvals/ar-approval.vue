<template>
        <ApprovalTabs
            :items="arApprovals"
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
</template>
<script lang="ts" setup>
    import { ref } from 'vue';
    import Vue3Datatable from '@bhplugin/vue3-datatable';
    import { useMeta } from '@/composables/use-meta';
    useMeta({ title: 'Invoice List' });

    import ApprovalTabs from '@/components/ApprovalTabs.vue';
    const tabs = ['Waiting List', 'Revised', 'Rejected', 'Full Approved'];
    const arApprovals = ref([
      { id: 1, number: 'AR-2025-001', title: 'Registrasi Laptop', requester: 'Alan Green', date: '2025-10-05', status: 'Waiting List' },
      { id: 2, number: 'AR-2025-002', title: 'Registrasi Monitor', requester: 'Linda Nelson', date: '2025-10-06', status: 'Revised' },
      { id: 3, number: 'AR-2025-003', title: 'Registrasi Meeting Room', requester: 'Susan', date: '2025-10-07', status: 'Rejected' },
      { id: 4, number: 'AR-2025-004', title: 'Registrasi Printer', requester: 'Budi', date: '2025-10-08', status: 'Full Approved' },
      { id: 5, number: 'AR-2025-005', title: 'Registrasi Scanner', requester: 'Rina', date: '2025-10-09', status: 'Waiting List' },
    ]);
    const approvalColumns = [
      { key: 'number', title: 'AR Number' },
      { key: 'title', title: 'Title' },
      { key: 'requester', title: 'Requester' },
      { key: 'date', title: 'Date' },
      { key: 'status', title: 'Status' },
    ];
    
    const datatable: any = ref(null);
    const search = ref('');
    const cols = ref([
        { field: 'invoice', title: 'Invoice' },
        { field: 'name', title: 'Name' },
        { field: 'email', title: 'Email' },
        { field: 'date', title: 'Date' },
        { field: 'amount', title: 'Amount', headerClass: 'justify-end' },
        { field: 'status', title: 'Status' },
        { field: 'actions', title: 'Actions', sort: false, headerClass: 'justify-center' },
    ]);
    const items = ref([
        {
            id: 1,
            invoice: '081451',
            name: 'Laurie Fox',
            email: 'lauriefox@company.com',
            date: '15 Dec 2020',
            amount: '2275.45',
            status: 'Paid',
        },
        {
            id: 2,
            invoice: '081452',
            name: 'Alexander Gray',
            email: 'alexGray3188@gmail.com',
            date: '20 Dec 2020',
            amount: '1044.00',
            status: 'Paid',
        },
        {
            id: 3,
            invoice: '081681',
            name: 'James Taylor',
            email: 'jamestaylor468@gmail.com',
            date: '27 Dec 2020',
            amount: '20.00',
            status: 'Pending',
        },
        {
            id: 4,
            invoice: '082693',
            name: 'Grace Roberts',
            email: 'graceRoberts@company.com',
            date: '31 Dec 2020',
            amount: '344.00',
            status: 'Paid',
        },
        {
            id: 5,
            invoice: '084743',
            name: 'Donna Rogers',
            email: 'donnaRogers@hotmail.com',
            date: '03 Jan 2021',
            amount: '405.15',
            status: 'Paid',
        },
        {
            id: 6,
            invoice: '086643',
            name: 'Amy Diaz',
            email: 'amy968@gmail.com',
            date: '14 Jan 2020',
            amount: '100.00',
            status: 'Paid',
        },
        {
            id: 7,
            invoice: '086773',
            name: 'Nia Hillyer',
            email: 'niahillyer666@comapny.com',
            date: '20 Jan 2021',
            amount: '59.21',
            status: 'Pending',
        },
        {
            id: 8,
            invoice: '087916',
            name: 'Mary McDonald',
            email: 'maryDonald007@gamil.com',
            date: '25 Jan 2021',
            amount: '79.00',
            status: 'Pending',
        },
        {
            id: 9,
            invoice: '089472',
            name: 'Andy King',
            email: 'kingandy07@company.com',
            date: '28 Jan 2021',
            amount: '149.00',
            status: 'Paid',
        },
        {
            id: 10,
            invoice: '091768',
            name: 'Vincent Carpenter',
            email: 'vincentcarpenter@gmail.com',
            date: '30 Jan 2021',
            amount: '400',
            status: 'Paid',
        },
        {
            id: 11,
            invoice: '095841',
            name: 'Kelly Young',
            email: 'youngkelly@hotmail.com',
            date: '06 Feb 2021',
            amount: '49.00',
            status: 'Pending',
        },
        {
            id: 12,
            invoice: '098424',
            name: 'Alma Clarke',
            email: 'alma.clarke@gmail.com',
            date: '10 Feb 2021',
            amount: '234.40',
            status: 'Paid',
        },
    ]);
    const searchText = ref('');
    const columns = ref(['id', 'invoice', 'name', 'email', 'date', 'amount', 'status', 'actions']);
    const tableOption = ref({
        headings: {
            id: (h: any, row: any, index: number) => {
                return '#';
            },
        },
        perPage: 10,
        perPageValues: [10, 20, 30, 50, 100],
        skin: 'table-hover',
        columnsClasses: { actions: 'actions !text-center w-1' },
        pagination: { show: true, nav: 'scroll', chunk: 10 },
        texts: {
            count: 'Showing {from} to {to} of {count} entries',
            filter: '',
            filterPlaceholder: 'Search...',
            limit: '',
        },
        resizableColumns: false,
        sortable: ['invoice', 'name', 'email', 'date', 'amount', 'status'],
        sortIcon: {
            base: 'sort-icon-none',
            up: 'sort-icon-asc',
            down: 'sort-icon-desc',
        },
    });

    const deleteRow = (item: any = null) => {
        if (confirm('Are you sure want to delete selected row ?')) {
            if (item) {
                items.value = items.value.filter((d) => d.id != item);
                datatable.value.clearSelectedRows();
            } else {
                let selectedRows = datatable.value.getSelectedRows();
                const ids = selectedRows.map((d) => {
                    return d.id;
                });
                items.value = items.value.filter((d) => !ids.includes(d.id as never));
                datatable.value.clearSelectedRows();
            }
        }
    };
</script>
