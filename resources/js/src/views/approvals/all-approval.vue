<template>
    <div>
        <!-- ALL APPROVAL SECTION -->
        <div class="panel mb-8">
            <h2 class="text-xl font-bold mb-4">All Approval (Approve Only)</h2>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Number</th>
                        <th>Title</th>
                        <th>Requester</th>
                        <th>Date</th>
                        <th>Approve</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in waitingApprovals" :key="item.type + '-' + item.number">
                        <td>{{ item.type }}</td>
                        <td>{{ item.number }}</td>
                        <td>{{ item.title }}</td>
                        <td>{{ item.requester }}</td>
                        <td>{{ item.date }}</td>
                        <td>
                            <button class="btn btn-success btn-sm" @click="approve(item)">Approve</button>
                        </td>
                    </tr>
                    <tr v-if="waitingApprovals.length === 0">
                        <td colspan="6" class="text-center py-4">No waiting approvals found</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { ref, computed } from 'vue';
// Dummy data, replace with import or API if needed
const prApprovals = ref([
    { id: 1, number: 'PR-2025-001', title: 'Pengadaan Laptop', requester: 'Alan Green', date: '2025-09-20', status: 'Waiting List' },
    { id: 2, number: 'PR-2025-002', title: 'Penambahan Monitor', requester: 'Linda Nelson', date: '2025-09-22', status: 'Revised' },
    { id: 3, number: 'PR-2025-003', title: 'Perangkat Meeting Room', requester: 'Susan', date: '2025-09-25', status: 'Rejected' },
    { id: 4, number: 'PR-2025-004', title: 'Pengadaan Printer', requester: 'Budi', date: '2025-09-28', status: 'Full Approved' },
    { id: 5, number: 'PR-2025-005', title: 'Pengadaan Scanner', requester: 'Rina', date: '2025-09-29', status: 'Waiting List' },
]);
const poApprovals = ref([
    { id: 1, number: 'PO-2025-001', title: 'Pengadaan Laptop', requester: 'Alan Green', date: '2025-09-30', status: 'Waiting List' },
    { id: 2, number: 'PO-2025-002', title: 'Penambahan Monitor', requester: 'Linda Nelson', date: '2025-10-01', status: 'Revised' },
    { id: 3, number: 'PO-2025-003', title: 'Perangkat Meeting Room', requester: 'Susan', date: '2025-10-02', status: 'Rejected' },
    { id: 4, number: 'PO-2025-004', title: 'Pengadaan Printer', requester: 'Budi', date: '2025-10-03', status: 'Full Approved' },
    { id: 5, number: 'PO-2025-005', title: 'Pengadaan Scanner', requester: 'Rina', date: '2025-10-04', status: 'Waiting List' },
]);
const arApprovals = ref([
    { id: 1, number: 'AR-2025-001', title: 'Registrasi Laptop', requester: 'Alan Green', date: '2025-10-05', status: 'Waiting List' },
    { id: 2, number: 'AR-2025-002', title: 'Registrasi Monitor', requester: 'Linda Nelson', date: '2025-10-06', status: 'Revised' },
    { id: 3, number: 'AR-2025-003', title: 'Registrasi Meeting Room', requester: 'Susan', date: '2025-10-07', status: 'Rejected' },
    { id: 4, number: 'AR-2025-004', title: 'Registrasi Printer', requester: 'Budi', date: '2025-10-08', status: 'Full Approved' },
    { id: 5, number: 'AR-2025-005', title: 'Registrasi Scanner', requester: 'Rina', date: '2025-10-09', status: 'Waiting List' },
]);

const waitingApprovals = computed(() => {
    return [
        ...prApprovals.value.filter(a => a.status === 'Waiting List').map(a => ({ ...a, type: 'PR' })),
        ...poApprovals.value.filter(a => a.status === 'Waiting List').map(a => ({ ...a, type: 'PO' })),
        ...arApprovals.value.filter(a => a.status === 'Waiting List').map(a => ({ ...a, type: 'AR' })),
    ];
});

function approve(item: any) {
    // Simulasi approve, bisa diganti dengan API call
    alert(`Approved ${item.type} ${item.number}`);
}
    import { onMounted } from 'vue';
    import { useMeta } from '@/composables/use-meta';
    useMeta({ title: 'Invoice Add' });

    const items: any = ref([]);
    const selectedFile = ref(null);
    const params = ref({
        title: '',
        invoiceNo: '',
        to: {
            name: '',
            email: '',
            address: '',
            phone: '',
        },

        invoiceDate: '',
        dueDate: '',
        bankInfo: {
            no: '',
            name: '',
            swiftCode: '',
            country: '',
            ibanNo: '',
        },
        notes: '',
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
    const paymentMethod = ref('');

    onMounted(() => {
        //set default data
        items.value.push({
            id: 1,
            title: '',
            description: '',
            rate: 0,
            quantity: 0,
            amount: 0,
        });
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
