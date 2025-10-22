<template>
        <ApprovalTabs
            :items="poApprovals"
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
    import { ref, onMounted, reactive } from 'vue';
    import { useMeta } from '@/composables/use-meta';
    useMeta({ title: 'Invoice Edit' });
    
    import ApprovalTabs from '@/components/ApprovalTabs.vue';
    const tabs = ['Waiting List', 'Revised', 'Rejected', 'Full Approved'];
    const poApprovals = ref([
        { id: 1, number: 'PO-2025-001', title: 'Pengadaan Laptop', requester: 'Alan Green', date: '2025-09-30', status: 'Waiting List' },
        { id: 2, number: 'PO-2025-002', title: 'Penambahan Monitor', requester: 'Linda Nelson', date: '2025-10-01', status: 'Revised' },
        { id: 3, number: 'PO-2025-003', title: 'Perangkat Meeting Room', requester: 'Susan', date: '2025-10-02', status: 'Rejected' },
        { id: 4, number: 'PO-2025-004', title: 'Pengadaan Printer', requester: 'Budi', date: '2025-10-03', status: 'Full Approved' },
        { id: 5, number: 'PO-2025-005', title: 'Pengadaan Scanner', requester: 'Rina', date: '2025-10-04', status: 'Waiting List' },
    ]);
    const approvalColumns = [
        { key: 'number', title: 'PO Number' },
        { key: 'title', title: 'Title' },
        { key: 'requester', title: 'Requester' },
        { key: 'date', title: 'Date' },
        { key: 'status', title: 'Status' },
    ];
    

    const items: any = ref([]);
    const selectedFile = ref(null);
    const params = reactive({
        title: 'Tailwind',
        invoiceNo: '#0001',
        to: {
            name: 'Jesse Cory',
            email: 'redq@company.com',
            address: '405 Mulberry Rd. Mc Grady, NC, 28649',
            phone: '(128) 666 070',
        },

        invoiceDate: new Date().toString(),
        dueDate: '',
        bankInfo: {
            no: '1234567890',
            name: 'Bank of America',
            swiftCode: 'VS70134',
            country: 'United States',
            ibanNo: 'K456G',
        },
        notes: 'It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!',
    });
    const currencyList = ref([
        'USD - US Dollar',
        'GBP - British Pound',
        'IDR - Indonesian Rupiah',
        'INR - Indian Rupee',
        'BRL - Brazilian Real',
        'EUR - Germany (Euro)',
        'TRY - Turkish Lira',
    ]);
    const selectedCurrency = ref('USD - US Dollar');
    const tax = ref<number>(0);
    const discount = ref<number>(0);
    const shippingCharge = ref<number>(0);
    const paymentMethod = ref('bank');

    onMounted(() => {
        //set default data
        items.value.push(
            {
                id: 1,
                title: 'Calendar App Customization',
                description: 'Make Calendar App Dynamic',
                quantity: 2,
                amount: 120,
                isTax: false,
            },
            {
                id: 2,
                title: 'Chat App Customization',
                description: 'Customized Chat Application to resolve some Bug Fixes',
                quantity: 1,
                amount: 25,
                isTax: false,
            }
        );

        let dt: Date = new Date();
        const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
        let date = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();
        params.invoiceDate = dt.getFullYear() + '-' + month + '-' + date;
        params.dueDate = dt.getFullYear() + '-' + month + '-' + date;
    });

    const addItem = () => {
        let maxId = 0;
        if (items.value && items.value.length) {
            maxId = items.value.reduce((max: number, character: any) => (character.id > max ? character.id : max), items.value[0].id);
        }
        items.value.push({
            id: maxId + 1,
            title: '',
            description: '',
            rate: 0,
            quantity: 0,
            amount: 0,
        });
    };

    const removeItem = (item: any = null) => {
        items.value = items.value.filter((d: any) => d.id != item.id);
    };
</script>
