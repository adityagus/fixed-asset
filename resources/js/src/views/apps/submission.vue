<template>
    <div class="bg-white p-2 rounded-md shadow-md dark:bg-black">
        <div class="flex items-center justify-between flex-wrap gap-4 px-4 py-2">
            <div class="">
                <svg
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="dark:invert cursor-pointer transition-transform duration-500"
                    :class="{ 'animate-spin': isRefreshing }"
                    @click="refreshData"
                >
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
                    <input type="text" placeholder="Cari Pengajuan" class="form-input py-2 ltr:pr-11 rtl:pl-11 peer"
                        v-model="search" />
                    <button type="button"
                        class="absolute ltr:right-[11px] rtl:left-[11px] top-1/2 -translate-y-1/2 peer-focus:text-primary">
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
                    <!-- Permintaan Pembelian Tab -->
                    <button
                        :class="[
                            'flex items-center border-transparent px-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'purchase-request'
                                ? 'border-b !border-primary text-primary !outline-none'
                                : ''
                        ]"
                        @click="store.tabSubmission('purchase-request')"
                        type="button"
                    >
                        <!-- Icon: Clipboard List -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 ltr:mr-2 rtl:ml-2"
                        >
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5" />
                            <path d="M9 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M9 12H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M9 16H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>
                            Permintaan <br /> Pembelian
                        </span>
                    </button>

                    <!-- Pesanan Pembelian Tab -->
                    <button
                        v-if="store.user?.idgrup == 'JBT-018'"
                        :class="[
                            'flex items-center border-transparent px-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'purchase-order'
                                ? 'border-b !border-primary text-primary !outline-none'
                                : ''
                        ]"
                        @click="store.tabSubmission('purchase-order')"
                        type="button"
                    >
                        <!-- Icon: Invoice Check (centang lebih besar & panjang, warna hijau) -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 ltr:mr-2 rtl:ml-2"
                        >
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M8 8H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M8 12H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <!-- Centang lebih besar dan panjang -->
                            <polyline points="11 15 14 21 24 7" stroke="#22c55e" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span>
                            Pesanan <br /> Pembelian
                        </span>
                    </button>

                    <!-- Pendaftaran Aset Tab -->
                    <button
                        v-if="store.user?.idgrup == 'JBT-018'"
                        :class="[
                            'flex items-center border-transparent px-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
                            store.activeTab === 'registration-asset'
                                ? 'border-b !border-primary text-primary !outline-none'
                                : ''
                        ]"
                        @click="store.tabSubmission('registration-asset')"
                        type="button"
                    >
                        <!-- Icon: Tag -->
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 ltr:mr-2 rtl:ml-2"
                        >
                            <path d="M3 11V7a4 4 0 0 1 4-4h4a2 2 0 0 1 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A2 2 0 0 1 3 11Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="8.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.5" fill="currentColor"/>
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
                                <thead>
                                    <tr v-if="filteredItems.length > 0">
                                        <th>Tanggal Pengajuan</th>
                                        <th class="text-center">Nomor Pengajuan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="filteredItems.length === 0">
                                        <td colspan="5" class="text-center py-4 flex justify-center">Tidak ditemukan Permintaan
                                            Pembelian</td>
                                    </tr>
                                    <tr v-for="pr in filteredItems" :key="pr.id">
                                        <td class='text-center'>{{ pr.date }}</td>
                                        <td class="text-center">
                                            <strong>
                                                <a class="text-primary underline cursor-pointer"
                                                    @click="goToForm('purchase-request', pr.pr_number)">
                                                    {{ pr.pr_number }}
                                                </a>
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            <span :class="`badge ${pr.status === 'Draft' ? 'bg-gray-500' : pr.status === 'Waiting Approval' ? 'bg-primary' :
                                                pr.status === 'Full Approved' ? 'bg-success' :
                                                    pr.status === 'Revised' ? 'bg-warning' :
                                                        'bg-danger'
                                                } text-center`">
                                                {{ statusLabel(pr.status) }} <span
                                                    v-if="pr.status == 'Waiting Approval' || pr.status == 'Revised' || pr.status == 'Rejected'">oleh
                                                    {{ pr.approvals[0]?.approver_by }}</span>
                                            </span>
                                        </td>
                                        <td class="flex justify-center">
                                            <template v-if="pr.status === 'Draft'" class="text-center">
                                                <!-- <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="cursor-pointer hover:text-red-500 border border-red-500 rounded"
                                                    style="box-sizing: content-box;"
                                                    @click="handleDeleteSubmission('purchase-request', pr.pr_number)">
                                                    <path d="M20.5001 6H3.5" stroke="#1C274C" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M9.5 11L10 16" stroke="#1C274C" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M14.5 11L14 16" stroke="#1C274C" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path
                                                        d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6"
                                                        stroke="#1C274C" stroke-width="1.5" />
                                                    <path
                                                        d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5"
                                                        stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                </svg> -->
                                                <svg width='24' height='24' xmlns="http://www.w3.org/2000/svg"
                                                 @click="handleDeleteSubmission('purchase-request', pr.pr_number)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 cursor-pointer hover:stroke-red-400 stroke-red-500 rounded">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>

                                            </template>
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
                                <thead>
                                    <tr v-if="filteredOrders.length > 0">
                                        <th>Tanggal Pesanan</th>
                                        <th class="text-center">Nomor Pesanan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Supplier</th>
                                        <th class="text-center w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="filteredOrders.length === 0">
                                        <td colspan="6" class="text-center py-4 flex justify-center">Tidak ditemukan Pesanan Pembelian
                                        </td>
                                    </tr>
                                    <tr v-for="po in filteredOrders" :key="po.id">
                                        <td class='text-center'>{{ po.po_date }}</td>
                                        <td class='text-center'>
                                            <strong>
                                                <a class="text-primary underline cursor-pointer"
                                                    @click="goToForm('purchase-order', po.po_number)">
                                                    {{ po.po_number }}
                                                </a>
                                            </strong>
                                        </td>
                                        <td class='text-center'>                                                                                   <span
                                                :class="`badge text-center ${po.status === 'Draft' ? 'bg-gray-500' : po.status === 'Waiting Approval' ? 'bg-primary' : po.status === 'Full Approved' ? 'bg-success' : po.status === 'Revised' ? 'bg-warning' : 'bg-danger'} w-36`">
                                                {{ statusLabel(po.status) }} <span
                                                    v-if="po.status == 'Waiting Approval' || po.status == 'Revised' || po.status == 'Rejected'">oleh
                                                    {{
                                                    po.approvals[0]?.approver_by }}</span>
                                            </span>
                                        </td>
                                        <td class="text-center">{{ po?.vendor?.nama }}</td>

                                        <td class="flex justify-center">
                                            <template v-if="po.status === 'Draft'">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                @click="handleDeleteSubmission('purchase-order', po.po_number)"
                                                    xmlns="http://www.w3.org/2000/svg" stroke-width="1.5" class="size-6 cursor-pointer hover:stroke-red-400 stroke-red-500 rounded">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>
                                            </template>
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
                                <thead>
                                    <tr v-if="filteredAssets.length > 0">
                                        <th>Tanggal Pendaftaran</th>
                                        <th class="text-center">Nomor Pendaftaran</th>
                                        <th class="text-center">Status</th>
                                        <!-- <th class="text-center">Diajukan Oleh</th> -->
                                        <th class="text-center w-36">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="filteredAssets.length === 0">
                                        <td colspan="7" class="text-center py-4 flex justify-center">Tidak ditemukan Pendaftaran Aset
                                        </td>
                                    </tr>
                                    <tr v-for="asset in filteredAssets" :key="asset.id">
                                        <td class='text-center'>{{ asset.date }}</td>
                                        <td class="text-center cursor-pointer">
                                                <strong>
                                                    <a class="text-primary underline cursor-pointer"
                                                        @click="goToForm('registration-asset', asset.ra_number)">
                                                        {{ asset.ra_number }}
                                                    </a>
                                                </strong>
                                        </td>
                                        <td class="text-center">
                                            <span :class="`badge ${asset.status === 'Draft' ? 'bg-gray-500' : asset.status === 'Waiting Approval' ? 'bg-primary' :
                                                    asset.status === 'Full Approved' ? 'bg-success' :
                                                        asset.status === 'Revised' ? 'bg-warning' :
                                                            'bg-danger'
                                                    } w-36 text-center`">
                                                    {{ statusLabel(asset.status) }} <span
                                                        v-if="asset.status == 'Waiting Approval' || asset.status == 'Revised' || asset.status == 'Rejected'">oleh
                                                        {{ asset.approvals[0]?.approver_by }}</span>
                                                </span>
                                        </td>
                                                                                <td class="text-center">
                                            <template v-if="asset.status === 'Draft'">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                    fill="currentColor"
                                                    class="size-6 cursor-pointer hover:text-red-500 fill-green-500">
                                                    <path fill-rule="evenodd"
                                                        d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                                        clip-rule="evenodd" />
                                                </svg>


                                                <!--                         
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
                        </svg> -->
                                            </template>
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
    computed(() => store?.activeTab) ?? 'purchase-request',
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
    // return list.filter((item: any) => {
    //     // const pr_number = (item.pr_number || '').toString().toLowerCase();
    //     return pr_number.includes(q);
    // });

    // Filter berdasarkan pr_number atau nama barang di purchase_request_item
    return list.filter((item: any) => {
        // const pr_number = (item.pr_number || '').toString().toLowerCase();
        // Gabungkan semua nama barang dari purchase_request_item (jika ada)
        console.log('Filtering item:', item);
        const items = Array.isArray(item.purchase_request_items)
            ? item.purchase_request_items.map((i: any) => (i.item_master.nama_brg || '')).join(' ').toLowerCase()
            : '';
        console.log('Combined items string:', items);
        return items.includes(q);
    });
    
});

const filteredOrders = computed(() => {
    const list = purchaseOrders.value;
    const q = search.value?.toLowerCase() ?? '';
    // return list.filter((item: any) => {
    //     const po_number = (item.po_number || '').toString().toLowerCase();
    //     return po_number.includes(q);
    // });
    return list.filter((item: any) => {
        // const po_number = (item.po_number || '').toString().toLowerCase();
        // Gabungkan semua nama barang dari purchase_order_item (jika ada)
        console.log('Filtering order:', item);
        const items = Array.isArray(item.purchase_order_items)
            ? item.purchase_order_items.map((i: any) => (i.item_master.nama_brg || '')).join(' ').toLowerCase()
            : '';
        return items.includes(q);
        
    });
});

const filteredAssets = computed(() => {
    const list = registrationAssets.value;
    const q = search.value?.toLowerCase() ?? '';
    // return list.filter((item: any) => {
    //     const ar_number = (item.ar_number || '').toString().toLowerCase();
    //     return ar_number.includes(q);
    // });
    // Filter berdasarkan ra_number atau nama barang di registration_asset_items
    return list.filter((item: any) => {
        // const ra_number = (item.ra_number || '').toString().toLowerCase();
        // Gabungkan semua nama barang dari registration_asset_items (jika ada)
        console.log('Filtering asset:', item);
        const items = Array.isArray(item.registration_asset_items)
            ? item.registration_asset_items.map((i: any) => (i.item_master.nama_brg || '')).join(' ').toLowerCase()
            : '';
        return items.includes(q);
    });
    
});

const submissionDelete = useDeleteSubmission();

const handleDeleteSubmission = (type: string, number: string) => {
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
        case 'canceled':
            return 'Dibatalkan';
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

const isRefreshing = ref(false);
const refreshData = async () => {
    isRefreshing.value = true;
    try {
        let type = store.activeTab;
        // Panggil API tergantung tipe data
        if (type === 'purchase-request' || type === 'purchase-order' || type === 'registration-asset') {
            await refetch();
            showMessage('Data ' + statusLabel(type.replace('-', ' ')) + ' berhasil diperbarui.');
        } else {
            showMessage('Tipe data tidak dikenali.', 'error');
        }
    } catch (err) {
        showMessage('Gagal memperbarui data.', 'error');
    } finally {
        isRefreshing.value = false;
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