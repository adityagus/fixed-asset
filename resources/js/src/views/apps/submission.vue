<template>
    <div class="bg-white p-2 rounded-md shadow-md dark:bg-black">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:invert">
                    <g clip-path="url(#clip0_1276_7761)">
                        <path
                            d="M19.7285 10.9288C20.4413 13.5978 19.7507 16.5635 17.6569 18.6573C15.1798 21.1344 11.4826 21.6475 8.5 20.1966M18.364 8.05071L17.6569 7.3436C14.5327 4.21941 9.46736 4.21941 6.34316 7.3436C3.42964 10.2571 3.23318 14.8588 5.75376 18M18.364 8.05071H14.1213M18.364 8.05071V3.80807"
                            class="stroke-[#1C274C]"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </g>
                    <defs>
                        <clipPath id="clip0_1276_7761">
                            <rect width="24" height="24" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search Submission"
                        class="form-input py-2 ltr:pr-11 rtl:pl-11 peer"
                        v-model="search"
                    />
                    <button type="button" class="absolute ltr:right-[11px] rtl:left-[11px] top-1/2 -translate-y-1/2 peer-focus:text-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="mb-5">
            <div class="mb-5">
                <div class="mt-3 flex flex-wrap border-b border-white-light dark:border-[#191e3a]">
                    <button
                        :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            activeTab === 'Purchase Requests' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]"
                        @click="activeTab = 'Purchase Requests'"
                    >
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                            <path
                                opacity="0.5"
                                d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                            <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>
                            Purchase <br /> Requests
                        </span>
                    </button>
                    <button
                        :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            activeTab === 'Purchase Orders' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]"
                        @click="activeTab = 'Purchase Orders'"
                    >
                        <svg width="20" height="20" class="h-5 w-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                            <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                        <span>
                            Purchase <br /> Orders
                        </span>
                    </button>
                    <button
                        :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            activeTab === 'Assets Registration' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]"
                        @click="activeTab = 'Assets Registration'"
                    >
                        <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.00659 6.93309C5.04956 5.7996 5.70084 4.77423 6.53785 3.93723C7.9308 2.54428 10.1532 2.73144 11.0376 4.31617L11.6866 5.4791C12.2723 6.52858 12.0372 7.90533 11.1147 8.8278M17.067 18.9934C18.2004 18.9505 19.2258 18.2992 20.0628 17.4622C21.4558 16.0692 21.2686 13.8468 19.6839 12.9624L18.5209 12.3134C17.4715 11.7277 16.0947 11.9628 15.1722 12.8853"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                            <path
                                opacity="0.5"
                                d="M5.00655 6.93311C4.93421 8.84124 5.41713 12.0817 8.6677 15.3323C11.9183 18.5829 15.1588 19.0658 17.0669 18.9935M15.1722 12.8853C15.1722 12.8853 14.0532 14.0042 12.0245 11.9755C9.99578 9.94676 11.1147 8.82782 11.1147 8.82782"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                        </svg>
                        <span>
                            Assets <br /> Registration
                        </span>
                    </button>
                </div>
                
                <!-- Tab Panels -->
                <div class="mt-5">
                    <!-- Purchase Requests Tab -->
                    <div v-if="activeTab === 'Purchase Requests'" class="panel p-0 border-0 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table-striped table-hover">
                                <!-- <thead>
                                    <tr>
                                        <th class="text-center">Action</th>
                                        <th>Request Date</th>
                                        <th>Status & PR Number</th>
                                        <th>Requested By</th>
                                        <th>Department</th>
                                    </tr>
                                </thead> -->
                                <tbody>
                                    <tr v-if="filteredItems.length === 0">
                                        <td colspan="5" class="text-center py-4">No Purchase Requests found</td>
                                    </tr>
                                    <tr v-for="pr in filteredItems" :key="pr.id">
                                        <td class="text-center">
                                            <template v-if="pr.status === 'Draft'">
                                                <svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="cursor-pointer hover:text-red-500"
                                                    @click="deletePurchaseRequest(pr.id)"
                                                >
                                                <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path
                                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                </svg>
                                            </template>
                                        </td>
                                        <td>{{ pr.requestDate }}</td>
                                        <td>
                                            <div class="grid grid-cols-[150px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer">
                                                <span
                                                    :class="`badge ${
                                                        pr.status === 'Waiting Approval' ? 'bg-primary' : 
                                                        pr.status === 'Approved' ? 'bg-success' : 
                                                        pr.status === 'Draft' ? 'bg-gray-400' : 
                                                        'bg-danger'
                                                    } w-36 text-center`"
                                                >
                                                    {{ pr.status }}
                                                </span>
                                                <strong>
                                                    <a
                                                        class="text-primary underline cursor-pointer"
                                                        @click="goToForm('purchaseRequest', pr.prNumber)"
                                                    >
                                                        {{ pr.prNumber }}
                                                    </a>
                                                </strong>
                                            </div>
                                        </td>
                                        <!-- <td>{{ pr.requestedBy }}</td>
                                        <td>{{ pr.department }}</td> -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Purchase Orders Tab -->
                    <div v-if="activeTab === 'Purchase Orders'" class="panel p-0 border-0 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table-striped table-hover">
                                <!-- <thead>
                                    <tr>
                                        <th class="text-center">Action</th>
                                        <th>Order Date</th>
                                        <th>PO Number</th>
                                        <th>Supplier</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead> -->
                                <tbody>
                                    <tr v-if="filteredOrders.length === 0">
                                        <td colspan="6" class="text-center py-4">No Purchase Orders found</td>
                                    </tr>
                                    <tr v-for="po in filteredOrders" :key="po.id">
                                        <td class="text-center">
                                            <template v-if="po.status === 'Draft'">
                                                <svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="cursor-pointer hover:text-red-500"
                                                    @click="deletePurchaseOrder(po.id)"
                                                >
                                                <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path
                                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                </svg>
                                            </template>
                                        </td>
                                        <td>{{ po.orderDate }}</td>
                                        <td class='grid grid-cols-[150px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer'>
                                          <span :class="`badge text-center ${po.status === 'Waiting Approval' ? 'bg-primary' : po.status === 'Approved' ? 'bg-success' : 'bg-danger'} w-36`">
                                                {{ po.status }}
                                            </span>
                                          <strong>
                                              <a
                                                  class="text-primary underline cursor-pointer"
                                                  @click="goToForm('purchaseOrder', po.poNumber)"
                                              >
                                                  {{ po.poNumber }}
                                              </a>
                                          </strong>
                                        </td>
                                        <td>{{ po.supplier }}</td>
                                        <td>{{ po.department }}</td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Assets Registration Tab -->
                    <div v-if="activeTab === 'Assets Registration'" class="panel p-0 border-0 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table-striped table-hover">
                                <!-- <thead>
                                    <tr>
                                        <th class="text-center">Action</th>
                                        <th>Registration Date</th>
                                        <th>Asset Number</th>
                                        <th>PO Number</th>
                                        <th>Asset Name</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead> -->
                                <tbody>
                                    <tr v-if="filteredAssets.length === 0">
                                        <td colspan="7" class="text-center py-4">No Asset Registrations found</td>
                                    </tr>
                                    <tr v-for="asset in filteredAssets" :key="asset.id">
                                        <td class="text-center">
                                            <template v-if="asset.status === 'Draft'">
                                                <svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="cursor-pointer hover:text-red-500"
                                                    @click="deleteAssetRegistration(asset.id)"
                                                >
                                                <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path
                                                    d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                                </svg>
                                            </template>
                                        </td>
                                        <td>{{ asset.registrationDate }}</td>
                                        <td class="grid grid-cols-[100px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer">
                                          <span :class="`badge text-center ${asset.status === 'Registered' ? 'bg-success' : 'bg-danger'}`">
                                                {{ asset.status }}
                                            </span>
                                          <strong>
                                              <a
                                                  class="text-primary underline cursor-pointer"
                                                  @click="goToForm('assetRegistration', asset.assetNumber)"
                                              >
                                                  {{ asset.assetNumber }}
                                              </a>
                                          </strong>
                                        </td>
                                        <td>{{ asset.poNumber }}</td>
                                        <td>{{ asset.assetName }}</td>
                                        <td>{{ asset.department }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
    import { ref, onMounted, computed, watch } from 'vue';
    import { useRouter } from 'vue-router';
    const router = useRouter();

    const goToForm = (type: string, number: string) => {
        router.push(`/apps/form-builder/${type}/${number}`);
    };
    import Swal from 'sweetalert2';
    import { useMeta } from '@/composables/use-meta';
    
    useMeta({ title: 'Submission' });

    const activeTab = ref('Purchase Requests');
    const addContactModal = ref(false);
    const search = ref('');

    const filteredRequests = computed(() => {
        return purchaseRequests.value.filter((request) =>
            request.prNumber.includes(search.value)
        );
    });

    const defaultParams = ref({
        id: null,
        name: '',
        email: '',
        phone: '',
        role: '',
        location: '',
    });

    const params = ref(JSON.parse(JSON.stringify(defaultParams.value)));

    const purchaseRequests = ref([
        {
            id: 1,
            prNumber: 'PR-2025-001',
            requestDate: '2025-09-20 09:15:00',
            requestedBy: 'Alan Green',
            department: 'IT Department',
            justification: 'Penggantian laptop lama yang sudah tidak mendukung software terbaru.',
            status: 'Waiting Approval',
            details: [
                {
                    itemId: 1,
                    description: 'Laptop Dell XPS 15',
                    qty: 5,
                    unitPrice: 2000,
                    totalPrice: 10000,
                },
                {
                    itemId: 2,
                    description: 'Mouse Wireless Logitech MX Master',
                    qty: 5,
                    unitPrice: 120,
                    totalPrice: 600,
                },
            ],
        },
        {
            id: 2,
            prNumber: 'PR-2025-002',
            requestDate: '2025-09-22 14:30:45',
            requestedBy: 'Linda Nelson',
            department: 'Design Department',
            justification: 'Penambahan monitor untuk tim desain agar lebih produktif.',
            status: 'Draft',
            details: [
                {
                    itemId: 1,
                    description: 'Monitor LG UltraWide 34 inch',
                    qty: 3,
                    unitPrice: 800,
                    totalPrice: 2400,
                },
                {
                    itemId: 2,
                    description: 'Pen Tablet Wacom Intuos Pro',
                    qty: 2,
                    unitPrice: 350,
                    totalPrice: 700,
                },
            ],
        },
        {
            id: 3,
            prNumber: 'PR-2025-003',
            requestDate: '2025-09-25 08:00:10',
            requestedBy: 'Susan',
            department: 'Project Management',
            justification: 'Kebutuhan perangkat tambahan untuk meeting room.',
            status: 'Rejected',
            details: [
                {
                    itemId: 1,
                    description: 'Projector Epson Full HD',
                    qty: 1,
                    unitPrice: 1500,
                    totalPrice: 1500,
                },
                {
                    itemId: 2,
                    description: 'Conference Speaker Jabra',
                    qty: 2,
                    unitPrice: 300,
                    totalPrice: 600,
                },
            ],
        },
    ]);

    
    const purchaseOrders = ref([
        {
            id: 1,
            poNumber: 'PO-2025-001',
            orderDate: '2025-09-30 10:12:03',
            supplier: 'PT. Komputer Indonesia',
            department: 'IT Department',
            status: 'Approved',
            items: [
                { itemId: 1, description: 'Laptop Dell XPS 15', qty: 5, unitPrice: 2000, totalPrice: 10000 },
                { itemId: 2, description: 'Mouse Wireless Logitech MX Master', qty: 5, unitPrice: 120, totalPrice: 600 },
            ],
        },
        {
            id: 2,
            poNumber: 'PO-2025-002',
            orderDate: '2025-09-30 11:00:00',
            supplier: 'PT. Monitor Nusantara',
            department: 'Design Department',
            status: 'Waiting Approval',
            items: [
                { itemId: 1, description: 'Monitor LG UltraWide 34 inch', qty: 3, unitPrice: 800, totalPrice: 2400 },
                { itemId: 2, description: 'Pen Tablet Wacom Intuos Pro', qty: 2, unitPrice: 350, totalPrice: 700 },
            ],
        },
    ]);

    const assetRegistrations = ref([
        {
            id: 1,
            assetNumber: 'AR-2025-001',
            poNumber: 'PO-2025-001',
            registrationDate: '2025-09-30 10:12:03',
            assetName: 'Laptop Dell XPS 15',
            department: 'IT Department',
            status: 'Registered',
        },
        {
            id: 2,
            assetNumber: 'AR-2025-002',
            poNumber: 'PO-2025-002',
            registrationDate: '2025-09-30 11:00:00',
            assetName: 'Monitor LG UltraWide 34 inch',
            department: 'Design Department',
            status: 'Registered',
        },
    ]);

    // Pastikan data terbaca dengan baik saat pertama kali component dimuat
    console.log('=== DATA INITIALIZED ===');
    console.log('purchaseRequests count:', purchaseRequests.value.length);
    console.log('purchaseOrders count:', purchaseOrders.value.length);
    console.log('assetRegistrations count:', assetRegistrations.value.length);
    console.log('========================');

    // Debug: Test if data is available
    console.log('Initial data check - Purchase Requests:', purchaseRequests.value.length);
    console.log('Initial data check - Purchase Orders:', purchaseOrders.value.length);
    console.log('Initial data check - Asset Registrations:', assetRegistrations.value.length);

    const filteredItems = computed(() => {
        const result = purchaseRequests.value.filter((item: any) => {
            return item.prNumber.toLowerCase().includes(search.value.toLowerCase());
        });
        console.log('Computed filteredItems:', result);
        return result;
    });

    const filteredOrders = computed(() => {
        const result = purchaseOrders.value.filter((order: any) => {
            return order.poNumber.toLowerCase().includes(search.value.toLowerCase());
        });
        console.log('Computed filteredOrders:', result);
        return result;
    });

    const filteredAssets = computed(() => {
        const result = assetRegistrations.value.filter((asset: any) => {
            return asset.assetNumber.toLowerCase().includes(search.value.toLowerCase());
        });
        console.log('Computed filteredAssets:', result);
        return result;
    });

    const saveUser = () => {
        if (!params.value.name) {
            showMessage('Name is required.', 'error');
            return true;
        }
        if (!params.value.email) {
            showMessage('Email is required.', 'error');
            return true;
        }
        if (!params.value.phone) {
            showMessage('Phone is required.', 'error');
            return true;
        }
        if (!params.value.role) {
            showMessage('Occupation is required.', 'error');
            return true;
        }

        if (params.value.id) {
            // Update user logic here if needed
        } else {
            // Add user logic here if needed
        }

        showMessage('User has been saved successfully.');
        addContactModal.value = false;
    };

    const editUser = (user: any = null) => {
        params.value = JSON.parse(JSON.stringify(defaultParams.value));
        if (user) {
            params.value = JSON.parse(JSON.stringify(user));
        }
        addContactModal.value = true;
    };

    const deleteUser = (user: any = null) => {
        // Delete user logic here if needed
        showMessage('User has been deleted successfully.');
    };

    const showMessage = (msg = '', type = 'success') => {
        const toast: any = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 3000,
            customClass: { container: 'toast' },
        });
        toast.fire({
            icon: type,
            title: msg,
            padding: '10px 20px',
        });
    };

    watch(search, () => {
        console.log('search', search.value);
        console.log('filteredItems', filteredItems.value);
        console.log('filteredOrders', filteredOrders.value);
        console.log('filteredAssets', filteredAssets.value);
    });

    // Fungsi delete untuk setiap tab
    const deletePurchaseRequest = (id: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the Purchase Request.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                purchaseRequests.value = purchaseRequests.value.filter(pr => pr.id !== id);
                showMessage('Purchase Request deleted successfully.');
            }
        });
    };

    const deletePurchaseOrder = (id: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the Purchase Order.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                purchaseOrders.value = purchaseOrders.value.filter(po => po.id !== id);
                showMessage('Purchase Order deleted successfully.');
            }
        });
    };

    const deleteAssetRegistration = (id: number) => {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the Asset Registration.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                assetRegistrations.value = assetRegistrations.value.filter(asset => asset.id !== id);
                showMessage('Asset Registration deleted successfully.');
            }
        });
    };

    // Debug log untuk memastikan data ada
    onMounted(() => {
        console.log('=== MOUNTED DEBUG ===');
        console.log('Purchase Requests:', purchaseRequests.value);
        console.log('Purchase Orders:', purchaseOrders.value);
        console.log('Asset Registrations:', assetRegistrations.value);
        console.log('Filtered Items:', filteredItems.value);
        console.log('Filtered Orders:', filteredOrders.value);
        console.log('Filtered Assets:', filteredAssets.value);
        console.log('Active Tab:', activeTab.value);
        console.log('===================');
    });
</script>
