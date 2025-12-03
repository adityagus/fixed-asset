<template>
  <div class="bg-white p-2 rounded-md shadow-md dark:bg-black">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="dark:invert">
          <g clip-path="url(#clip0_1276_7761)">
            <path
              d="M19.7285 10.9288C20.4413 13.5978 19.7507 16.5635 17.6569 18.6573C15.1798 21.1344 11.4826 21.6475 8.5 20.1966M18.364 8.05071L17.6569 7.3436C14.5327 4.21941 9.46736 4.21941 6.34316 7.3436C3.42964 10.2571 3.23318 14.8588 5.75376 18M18.364 8.05071H14.1213M18.364 8.05071V3.80807"
              class="stroke-[#1C274C]" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
          <input type="text" placeholder="Cari Pengajuan" class="form-input py-2 ltr:pr-11 rtl:pl-11 peer" v-model="search" />
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
          <button :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'purchase-request' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]" @click="store.tabSubmission('purchase-request')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5 ltr:mr-2 rtl:ml-2">
              <path opacity="0.5"
                d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                stroke="currentColor" stroke-width="1.5" />
              <path d="M12 15L12 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <span>
              Permintaan <br /> Pembelian
            </span>
          </button>
          <button :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'purchase-order' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]" @click="store.tabSubmission('purchase-order')">
            <svg width="20" height="20" class="h-5 w-5 ltr:mr-2 rtl:ml-2" viewBox="0 0 24 24" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
              <ellipse opacity="0.5" cx="12" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5" />
            </svg>
            <span>
              Pesanan <br /> Pembelian
            </span>
          </button>
          <button :class="[
                            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'registration-asset' 
                                ? 'border-b !border-primary text-primary !outline-none' 
                                : ''
                        ]" @click="store.tabSubmission('registration-asset')">
            <svg class="ltr:mr-2 rtl:ml-2" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M5.00659 6.93309C5.04956 5.7996 5.70084 4.77423 6.53785 3.93723C7.9308 2.54428 10.1532 2.73144 11.0376 4.31617L11.6866 5.4791C12.2723 6.52858 12.0372 7.90533 11.1147 8.8278M17.067 18.9934C18.2004 18.9505 19.2258 18.2992 20.0628 17.4622C21.4558 16.0692 21.2686 13.8468 19.6839 12.9624L18.5209 12.3134C17.4715 11.7277 16.0947 11.9628 15.1722 12.8853"
                stroke="currentColor" stroke-width="1.5" />
              <path opacity="0.5"
                d="M5.00655 6.93311C4.93421 8.84124 5.41713 12.0817 8.6677 15.3323C11.9183 18.5829 15.1588 19.0658 17.0669 18.9935M15.1722 12.8853C15.1722 12.8853 14.0532 14.0042 12.0245 11.9755C9.99578 9.94676 11.1147 8.82782 11.1147 8.82782"
                stroke="currentColor" stroke-width="1.5" />
            </svg>
            <span>
              Pendaftaran <br /> Aset
            </span>
          </button>
        </div>

        <!-- Tab Panels -->
        <div class="mt-5">
          <!-- Purchase Requests Tab -->
          <div v-if="store.activeTab === 'purchase-request'" class="panel p-0 border-0 overflow-hidden">
            <div v-if="isLoading" class="flex justify-center items-center py-10">
              <span
                class="animate-spin border-4 border-yellow-400 border-l-transparent rounded-full w-10 h-10 inline-block align-middle"></span>
            </div>
            <div v-else class="table-responsive">
              <table class="table-striped table-hover">
                <tbody>
                  <tr v-if="filteredItems.length === 0">
                    <td colspan="5" class="text-center py-4 flex justify-center">Tidak ditemukan Permintaan Pembelian</td>
                  </tr>
                  <tr v-for="pr in filteredItems" :key="pr.id">
                    <td class="text-center">
                      <template v-if="pr.status === 'Draft'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          class="cursor-pointer hover:text-red-500" @click="handleDeleteSubmission('purchase-request', pr.pr_number)">
                          <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path
                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                            stroke="#1C274C" stroke-width="1.5" />
                          <path
                            d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                      </template>
                    </td>
                    <td>{{ pr.pr_date }}</td>
                    <td>
                      <div class="grid grid-cols-[150px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer">
                        <strong>
                          <a class="text-primary underline cursor-pointer" @click="goToForm('purchase-request', pr.pr_number)">
                            {{ pr.pr_number }}
                          </a>
                        </strong>
                        <span :class="`badge ${
                                pr.status === 'Draft' ? 'bg-gray-500' : pr.status === 'Waiting Approval' ? 'bg-primary' : 
                                pr.status === 'Full Approved' ? 'bg-success' : 
                                pr.status === 'Revised' ? 'bg-warning' :
                                'bg-danger'
                            } w-36 text-center`">
                          {{ statusLabel(pr.status) }} <span v-if="pr.status == 'Waiting Approval' || pr.status == 'Revised' || pr.status == 'Rejected'">oleh {{ pr.approvals[0]?.approver_by }}</span>
                        </span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Purchase Orders Tab -->
          <div v-if="store.activeTab === 'purchase-order'" class="panel p-0 border-0 overflow-hidden">
            <div v-if="isLoading" class="flex justify-center items-center py-10">
              <span
                class="animate-spin border-4 border-yellow-400 border-l-transparent rounded-full w-10 h-10 inline-block align-middle"></span>
            </div>
            <div v-else class="table-responsive">
              <table class="table-striped table-hover">
                <tbody>
                  <tr v-if="filteredOrders.length === 0">
                    <td colspan="6" class="text-center py-4 flex justify-center">Tidak ditemukan Pesanan Pembelian</td>
                  </tr>
                  <tr v-for="po in filteredOrders" :key="po.id">
                    <td class="text-center">
                      <template v-if="po.status === 'Draft'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          class="cursor-pointer hover:text-red-500" @click="handleDeleteSubmission('purchase-order', po.po_number)">
                          <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path
                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                            stroke="#1C274C" stroke-width="1.5" />
                          <path
                            d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                      </template>
                    </td>
                    <td>{{ po.date }}</td>
                    <td class='grid grid-cols-[150px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer'>
                      <strong>
                        <a class="text-primary underline cursor-pointer" @click="goToForm('purchase-order', po.po_number)">
                          {{ po.po_number }}
                        </a>
                      </strong>
                      <span
                        :class="`badge text-center ${po.status === 'Draft' ? 'bg-gray-500' : po.status === 'Waiting Approval' ? 'bg-primary' : po.status === 'Full Approved' ? 'bg-success' : po.status === 'Revised' ? 'bg-warning' : 'bg-danger'} w-36`">
                        {{ statusLabel(po.status) }} <span v-if="po.status == 'Waiting Approval' || po.status == 'Revised' || po.status == 'Rejected'">oleh {{ po.approvals[0]?.approver_by }}</span>
                      </span>
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
          <div v-if="store.activeTab === 'registration-asset'" class="panel p-0 border-0 overflow-hidden">
            <div v-if="isLoading" class="flex justify-center items-center py-10">
              <span
                class="animate-spin border-4 border-yellow-400 border-l-transparent rounded-full w-10 h-10 inline-block align-middle"></span>
            </div>
            <div v-else class="table-responsive">
              <table class="table-striped table-hover">
                <tbody>
                  <tr v-if="filteredAssets.length === 0">
                    <td colspan="7" class="text-center py-4 flex justify-center">Tidak ditemukan Pendaftaran Aset</td>
                  </tr>
                  <tr v-for="asset in filteredAssets" :key="asset.id">
                    <td class="text-center">
                      <template v-if="asset.status === 'Draft'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                          class="cursor-pointer hover:text-red-500" @click="handleDeleteSubmission('registration-asset', asset.ra_number)">
                          <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                          <path
                            d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                            stroke="#1C274C" stroke-width="1.5" />
                          <path
                            d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                      </template>
                    </td>
                    <td>{{ asset.date }}</td>
                    <td class="grid grid-cols-[100px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer">
                      <div class="grid grid-cols-[150px_minmax(50px,_1fr)_1px] items-center gap-2 cursor-pointer">
                        <strong>
                          <a class="text-primary underline cursor-pointer" @click="goToForm('registration-asset', asset.ra_number)">
                            {{ asset.ra_number }}
                          </a>
                        </strong>
                        <span :class="`badge ${
                                asset.status === 'Draft' ? 'bg-gray-500' : asset.status === 'Waiting Approval' ? 'bg-primary' : 
                                asset.status === 'Full Approved' ? 'bg-success' : 
                                asset.status === 'Revised' ? 'bg-warning' :
                                'bg-danger'
                            } w-36 text-center`">
                          {{ statusLabel(asset.status) }} <span v-if="asset.status == 'Waiting Approval' || asset.status == 'Revised' || asset.status == 'Rejected'">oleh {{ asset.approvals[0]?.approver_by }}</span>
                        </span>
                      </div>
                    </td>
                    <!-- <td>{{ asset.purchase_order.purchase_request.created_by }}</td> -->
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
import { ref, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useListSubmission } from '@/services/queries';
import { useDeleteSubmission } from '@/services/mutations';
import { useMeta } from '@/composables/use-meta';
import { showMessage } from '@/composables/showMessage';
import Swal from 'sweetalert2';
import { useAppStore } from '@/stores'

useMeta({ title: 'Submission' });

const router = useRouter();
const goToForm = (type: string, number: string) => {
    // Ubah path agar lebih natural dalam bahasa Indonesia jika perlu
    router.push({
        path: `/apps/form/${type}/${number}`,
        query: { from: 'submission' }
    });
};

const store = useAppStore();

const search = ref('');

// ✅ Pass activeTab sebagai computed agar reactive
const { data, isLoading, refetch } = useListSubmission(
  computed(() => store?.activeTab),
  store.user?.username,
);

// ATAU jika composable support ref langsung:
// const { data, isLoading, error, refetch } = useSubmissions(activeTab);

// Helper untuk get data by key
const apiList = (key: string) => {
    console.log('API data:', data.value);
    console.log('Active Tab:', store?.activeTab);
    
    if (!data.value) return [];

    // Sesuaikan dengan struktur response API Anda
    // // Contoh 1: API return { data: { pr: [...], po: [...], ra: [...] } }
    // if (data.value.data?.[key] && Array.isArray(data.value.data[key])) {
    //     return data.value.data[key];
    // }
    
    // Contoh 2: API return { pr: [...], po: [...], ra: [...] }
    if (data.value[key] && Array.isArray(data.value[key])) {
        return data.value[key];
    }
    
    // Contoh 3: API return array langsung sesuai type
    if (Array.isArray(data.value.data)) {
        return data.value.data;
    }

    return [];
};

// Data masing-masing tab
const purchaseRequests = computed(() => {
    return apiList('purchase-request');
});
const purchaseOrders = computed(() => {
    return apiList('purchase-order');
});
const registrationAssets = computed(() => {
    return apiList('registration-asset');
});

// Filter masing-masing tab
const filteredItems = computed(() => {
    const list = purchaseRequests.value;
    const q = search.value?.toLowerCase() ?? '';
    return list.filter((item: any) => {
        const pr_number = (item.pr_number || '').toString().toLowerCase();
        return pr_number.includes(q);
    });
});

const filteredOrders = computed(() => {
    const list = purchaseOrders.value;
    const q = search.value?.toLowerCase() ?? '';
    return list.filter((item: any) => {
        const po_number = (item.po_number || '').toString().toLowerCase();
        return po_number.includes(q);
    });
});

const filteredAssets = computed(() => {
    const list = registrationAssets.value;
    const q = search.value?.toLowerCase() ?? '';
    return list.filter((item: any) => {
        const ar_number = (item.ar_number || '').toString().toLowerCase();
        return ar_number.includes(q);
    });
});

const submissionDelete = useDeleteSubmission();

const handleDeleteSubmission = (type:string, number:string) => {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: `Ini akan menghapus permanen ${type === 'purchase-request' ? 'Permintaan Pembelian' : type === 'purchase-order' ? 'Pesanan Pembelian' : 'Registrasi Aset'}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) {
            submissionDelete.mutateAsync({ type, number });
        }
    });
};

// Capitalize helper setiap kata depan
const capitalize = (val: string) => val ? val.charAt(0).toUpperCase() + val.slice(1) : '';

// Mapping status ke label Bahasa Indonesia
const statusLabel = (status: string) => {
    switch ((status || '').toLowerCase()) {
        case 'draft':
            return 'Draf';
        case 'waiting approval':
            return 'Menunggu Persetujuan';
        case 'full approved':
            return 'Disetujui';
        case 'revised':
            return 'Direvisi';
        case 'rejected':
            return 'Ditolak';
        default:
            return capitalize(status) || '';
    }
};

const deletePurchaseOrder = async (id: number) => {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the Purchase Order.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    });
    
    if (result.isConfirmed) {
        try {
            // TODO: Call API delete
            // await axios.delete(`/api/purchase-orders/${id}`);
            
            await refetch();
            showMessage('Purchase Order deleted successfully.');
        } catch (err) {
            showMessage('Failed to delete Purchase Order.', 'error');
        }
    }
};

const deleteAssetRegistration = async (id: number) => {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'This will permanently delete the Asset Registration.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    });
    
    if (result.isConfirmed) {
        try {
            // TODO: Call API delete
            // await axios.delete(`/api/registration-assets/${id}`);
            
            await refetch();
            showMessage('Asset Registration deleted successfully.');
        } catch (err) {
            showMessage('Failed to delete Asset Registration.', 'error');
        }
    }
};

const appStore = useAppStore()
console.log('isLoggedIn:', appStore.isLoggedIn, 'user:', appStore.user)
</script>