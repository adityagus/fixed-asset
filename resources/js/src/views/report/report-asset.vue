<template>
    <div class="panel p-6">
        <h2 class="text-2xl font-bold mb-6">Laporan Aset</h2>
        <!-- Navigasi Tab -->
        <div class="mt-3 flex flex-wrap border-b border-white-light dark:border-[#191e3a] mb-6">
            <button :class="[
            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
            activeTab === 'asset' ? 'border-b !border-primary text-primary !outline-none' : ''
            ]" @click="activeTab = 'asset'">
            <!-- Icon: Asset/Box -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                <rect x="3" y="7" width="18" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <path d="M3 10h18" stroke="currentColor" stroke-width="1.5"/>
                <rect x="8" y="3" width="8" height="4" rx="1" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span>Asset</span>
            </button>
            <button :class="[
            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
            activeTab === 'branch' ? 'border-b !border-primary text-primary !outline-none' : ''
            ]" @click="activeTab = 'branch'">
            <!-- Icon: Branch/Network -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                <circle cx="12" cy="5" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="5" cy="19" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                <circle cx="19" cy="19" r="2.5" stroke="currentColor" stroke-width="1.5"/>
                <path d="M12 7.5V16M12 16L5 16M12 16L19 16" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span>Berdasarkan<br />Cabang</span>
            </button>
            <button :class="[
            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
            activeTab === 'warehouse' ? 'border-b !border-primary text-primary !outline-none' : ''
            ]" @click="activeTab = 'warehouse'">
            <!-- Icon: Warehouse/Building -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                <rect x="3" y="10" width="18" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/>
                <path d="M3 10L12 4L21 10" stroke="currentColor" stroke-width="1.5"/>
                <rect x="8" y="14" width="2" height="4" stroke="currentColor" stroke-width="1.5"/>
                <rect x="14" y="14" width="2" height="4" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span>Gudang</span>
            </button>
            <button :class="[
            'flex items-center border-transparent p-5 py-3 gap-2 hover:border-b hover:!border-primary hover:text-primary -mb-[1px]',
            activeTab === 'susut' ? 'border-b !border-primary text-primary !outline-none' : ''
            ]" @click="activeTab = 'susut'">
            <!-- Icon: Depreciation/Chart -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                <rect x="4" y="14" width="3" height="6" rx="1" stroke="currentColor" stroke-width="1.5"/>
                <rect x="10.5" y="10" width="3" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                <rect x="17" y="6" width="3" height="14" rx="1" stroke="currentColor" stroke-width="1.5"/>
            </svg>
            <span>Susut</span>
            </button>
        </div>
        <div class="flex items-center justify-between md:flex-row flex-col mb-4.5 gap-5">
            <div class="flex items-center mb-4">
                <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('txt')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path
                            d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z"
                            fill="currentColor" />
                        <path opacity="0.5" d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    CSV
                </button>
                <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('txt')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path
                            d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z"
                            fill="currentColor" />
                        <path opacity="0.5" d="M6 14.5H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M6 18H11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    TXT
                </button>
                <vue3-json-excel class="btn btn-primary btn-sm m-1 cursor-pointer" name="assets.xls" :fields="excelColumns()"
                    :json-data="excelItems()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path
                            d="M15.3929 4.05365L14.8912 4.61112L15.3929 4.05365ZM19.3517 7.61654L18.85 8.17402L19.3517 7.61654ZM21.654 10.1541L20.9689 10.4592V10.4592L21.654 10.1541ZM3.17157 20.8284L3.7019 20.2981H3.7019L3.17157 20.8284ZM20.8284 20.8284L20.2981 20.2981L20.2981 20.2981L20.8284 20.8284ZM14 21.25H10V22.75H14V21.25ZM2.75 14V10H1.25V14H2.75ZM21.25 13.5629V14H22.75V13.5629H21.25ZM14.8912 4.61112L18.85 8.17402L19.8534 7.05907L15.8947 3.49618L14.8912 4.61112ZM22.75 13.5629C22.75 11.8745 22.7651 10.8055 22.3391 9.84897L20.9689 10.4592C21.2349 11.0565 21.25 11.742 21.25 13.5629H22.75ZM18.85 8.17402C20.2034 9.3921 20.7029 9.86199 20.9689 10.4592L22.3391 9.84897C21.9131 8.89241 21.1084 8.18853 19.8534 7.05907L18.85 8.17402ZM10.0298 2.75C11.6116 2.75 12.2085 2.76158 12.7405 2.96573L13.2779 1.5653C12.4261 1.23842 11.498 1.25 10.0298 1.25V2.75ZM15.8947 3.49618C14.8087 2.51878 14.1297 1.89214 13.2779 1.5653L12.7405 2.96573C13.2727 3.16993 13.7215 3.55836 14.8912 4.61112L15.8947 3.49618ZM10 21.25C8.09318 21.25 6.73851 21.2484 5.71085 21.1102C4.70476 20.975 4.12511 20.7213 3.7019 20.2981L2.64124 21.3588C3.38961 22.1071 4.33855 22.4392 5.51098 22.5969C6.66182 22.7516 8.13558 22.75 10 22.75V21.25ZM1.25 14C1.25 15.8644 1.24841 17.3382 1.40313 18.489C1.56076 19.6614 1.89288 20.6104 2.64124 21.3588L3.7019 20.2981C3.27869 19.8749 3.02502 19.2952 2.88976 18.2892C2.75159 17.2615 2.75 15.9068 2.75 14H1.25ZM14 22.75C15.8644 22.75 17.3382 22.7516 18.489 22.5969C19.6614 22.4392 20.6104 22.1071 21.3588 21.3588L20.2981 20.2981C19.8749 20.7213 19.2952 20.975 18.2892 21.1102C17.2615 21.2484 15.9068 21.25 14 21.25V22.75ZM21.25 14C21.25 15.9068 21.2484 17.2615 21.1102 18.2892C20.975 19.2952 20.7213 19.8749 20.2981 20.2981L21.3588 21.3588C22.1071 20.6104 22.4392 19.6614 22.5969 18.489C22.7516 17.3382 22.75 15.8644 22.75 14H21.25ZM2.75 10C2.75 8.09318 2.75159 6.73851 2.88976 5.71085C3.02502 4.70476 3.27869 4.12511 3.7019 3.7019L2.64124 2.64124C1.89288 3.38961 1.56076 4.33855 1.40313 5.51098C1.24841 6.66182 1.25 8.13558 1.25 10H2.75ZM10.0298 1.25C8.15538 1.25 6.67442 1.24842 5.51887 1.40307C4.34232 1.56054 3.39019 1.8923 2.64124 2.64124L3.7019 3.7019C4.12453 3.27928 4.70596 3.02525 5.71785 2.88982C6.75075 2.75158 8.11311 2.75 10.0298 2.75V1.25Z"
                            fill="currentColor" />
                        <path opacity="0.5" d="M13 2.5V5C13 7.35702 13 8.53553 13.7322 9.26777C14.4645 10 15.643 10 18 10H22"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    EXCEL
                </vue3-json-excel>
                <button type="button" class="btn btn-primary btn-sm m-1" @click="exportTable('print')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <path
                            d="M6 17.9827C4.44655 17.9359 3.51998 17.7626 2.87868 17.1213C2 16.2426 2 14.8284 2 12C2 9.17157 2 7.75736 2.87868 6.87868C3.75736 6 5.17157 6 8 6H16C18.8284 6 20.2426 6 21.1213 6.87868C22 7.75736 22 9.17157 22 12C22 14.8284 22 16.2426 21.1213 17.1213C20.48 17.7626 19.5535 17.9359 18 17.9827"
                            stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5" d="M9 10H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M19 14L5 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M18 14V16C18 18.8284 18 20.2426 17.1213 21.1213C16.2426 22 14.8284 22 12 22C9.17157 22 7.75736 22 6.87868 21.1213C6 20.2426 6 18.8284 6 16V14"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5"
                            d="M17.9827 6C17.9359 4.44655 17.7626 3.51998 17.1213 2.87868C16.2427 2 14.8284 2 12 2C9.17158 2 7.75737 2 6.87869 2.87868C6.23739 3.51998 6.06414 4.44655 6.01733 6"
                            stroke="currentColor" stroke-width="1.5" />
                        <circle opacity="0.5" cx="17" cy="10" r="1" fill="currentColor" />
                        <path opacity="0.5" d="M15 16.5H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path opacity="0.5" d="M13 19H9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    PRINT
                </button>
            </div>
            <div class="flex gap-3">
                <button class="btn btn-outline-primary flex items-center gap-2" @click="showBarcodeScanner = true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:1;">
                        <path fill="none" d="M10 12V6h1m-1 6h1V6m-1 12v-3h1m0 0v3h-1M7 6v6m0 3v3m7-12v6m0 3v3m3-12v6m0 3v3M6 3H3v3m-1 6h20m-4-9h3v3M6 21H3v-3m15 3h3v-3"/>
                    </svg>
                    Scan Barcode Aset
                </button>
                <input v-if="activeTab === 'asset'" v-model="searchAsset" type="text" class="form-input w-auto"
                    placeholder="Cari aset..." />
                <input v-if="activeTab === 'branch'" v-model="searchBranch" type="text" class="form-input w-auto"
                    placeholder="Cari aset cabang..." />
                <input v-if="activeTab === 'warehouse'" v-model="searchWarehouse" type="text" class="form-input w-auto"
                    placeholder="Cari aset gudang..." />
                <input v-if="activeTab === 'susut'" v-model="searchSusut" type="text" class="form-input w-auto"
                    placeholder="Cari aset susut..." />
            </div>
        </div>
        <div v-if="activeTab === 'asset'">
            <h3 class="text-lg font-semibold mb-2">Daftar Asset</h3>
            <vue3-datatable :rows="assetRows" :columns="assetSimpleCols" :search="searchAsset" :totalRows="total" :pageSize="limit" :page='currentPage' @page-change="onPageChange" @page-size-change="onPageSizeChange" skin="whitespace-nowrap bh-table-hover">
                <template #detail="{ row }">
                    <span style="display:flex;justify-content:center;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" fill="none" />
                            <circle cx="12" cy="12" r="3.5" stroke="currentColor" fill="none" />
                        </svg>
                    </span>
                </template>
            </vue3-datatable>
        </div>
        <div v-if="activeTab === 'branch' && isCabangListSuccess">
            <h3 class="text-lg font-semibold mb-2">Aset Berdasarkan Cabang</h3>
            <div class="flex gap-4 mb-4">
              <select v-model="selectedBranch" class="form-select w-64">
                <option value="">Pilih Cabang</option>
                <option v-for="branch in cabangList" :key="branch.id_area" :value="branch.id_area">{{ branch.nm_area }}</option>
              </select>
              <select v-model="selectedKapitalis" class="form-select w-64">
                <option value="">Pilih Kapitalis</option>
                <option value="Kapitalis">Kapitalis</option>
                <option value="Non Kapitalis">Non Kapitalis</option>
              </select>
            </div>
            <vue3-datatable :rows="branchAssets" :columns="assetSimpleCols" :search="searchBranch"
              skin="whitespace-nowrap bh-table-hover" />
        </div>
        <div v-if="activeTab === 'warehouse'">
            <h3 class="text-lg font-semibold mb-2">Aset di Gudang</h3>
            <vue3-datatable :rows="warehouseAssets" :columns="assetSimpleCols" :search="searchWarehouse"
                skin="whitespace-nowrap bh-table-hover" />
        </div>
        <div v-if="activeTab === 'susut'">
            <h3 class="text-lg font-semibold mb-2">Laporan Susut Aset</h3>
            <vue3-datatable :rows="susutRows" :columns="susutCols" :search="searchSusut" skin="whitespace-nowrap bh-table-hover" />
        </div>
        <!-- Modal Kamera -->
        <div v-if="showCamera" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 relative w-full max-w-md">
                <button class="absolute top-2 right-2 text-gray-500" @click="closeCamera">&times;</button>
                <h3 class="text-lg font-semibold mb-4">Cek Aset dengan Kamera</h3>
                <div v-if="!capturedImage">
                    <video ref="videoRef" autoplay playsinline class="w-full rounded mb-4"></video>
                    <div class="flex justify-center gap-2">
                        <button class="btn btn-primary" @click="capturePhoto">Ambil Foto</button>
                        <button class="btn btn-outline-secondary" @click="closeCamera">Batal</button>
                    </div>
                </div>
                <div v-else>
                    <img :src="capturedImage" class="w-full rounded mb-4" />
                    <div class="flex justify-center gap-2">
                        <button class="btn btn-outline-primary" @click="retakePhoto">Ulangi</button>
                        <button class="btn btn-primary" @click="closeCamera">Selesai</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Scan Barcode -->
        <div v-if="showBarcodeScanner" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 relative w-full max-w-md">
                <button class="absolute top-2 right-2 text-gray-500" @click="closeBarcodeScanner">&times;</button>
                <h3 class="text-lg font-semibold mb-4">Scan Barcode Aset</h3>
                <div id="barcode-scanner" class="w-full rounded mb-4"></div>
                <div class="flex justify-center gap-2">
                    <button class="btn btn-outline-secondary" @click="closeBarcodeScanner">Batal</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
// Komponen Laporan Aset Lengkap
import { ref, computed, watch, nextTick, watchEffect } from 'vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import Vue3JsonExcel from 'vue3-json-excel';
import { Html5Qrcode } from 'html5-qrcode';
import { useRouter } from 'vue-router';
import { useGetAssetByCabangPaginated, useGetAssetReport, useGetAssetReportPaginated, useGetCabangList } from '@/services/queries';
// State
const limit = ref(10);
const offset = ref(0);
const total = ref(0);
const currentPage = computed(() => Math.floor(offset.value / limit.value) + 1);
function onPageChange(page) {
  offset.value = (page - 1) * limit.value;
}

function onPageSizeChange(newLimit) {
  limit.value = newLimit;
  offset.value = 0; // reset ke halaman pertama
}
const assetRows = computed(() => allAssets.value); // Untuk tab asset
const selectedKapitalis = ref('');
// const branchAssets = computed(() => {
//   let filtered = allAssets.value;
//   if (selectedBranch.value) {
//     filtered = filtered.filter(a => a.location === selectedBranch.value);
//   }
//   if (selectedKapitalis.value) {
//     // Asumsi field kapitalis di data asset: a.kapitalis
//     if (selectedKapitalis.value === 'Kapitalis') {
//       filtered = filtered.filter(a => a.is_asset == 'Kapitalis');
//     } else if (selectedKapitalis.value === 'Non Kapitalis') {
//       filtered = filtered.filter(a => a.is_asset == 'Non Kapitalis');
//     }
//   }
//   return filtered;
// });
const router = useRouter();
const activeTab = ref('asset');
const searchAsset = ref('');
const searchBranch = ref('');
const searchWarehouse = ref('');
const searchSusut = ref('');
const selectedBranch = ref('');
const showCamera = ref(false);
const showBarcodeScanner = ref(false);
const videoRef = ref<HTMLVideoElement | null>(null);
const capturedImage = ref<string | null>(null);
    
let stream: MediaStream | null = null;
let barcodeScanner: Html5Qrcode | null = null;
// const branches = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Bali'];

// queries
const { data: cabangList, isSuccess: isCabangListSuccess } = useGetCabangList();
// const { data: assetReport } = useGetAssetReport();
const { data: assetReport } = useGetAssetReportPaginated(limit, offset);
const { data: branchAssets } = useGetAssetByCabangPaginated(selectedBranch, selectedKapitalis, limit, offset);
console.log('branchAssets:', branchAssets);
// Kolom untuk tab Asset (seperti gambar)
const assetSimpleCols = [
  { title: 'Kode Aset', field: 'asset_number', sortable: true },
  { title: 'Nama Barang', field: 'item.nama_brg', sortable: true },
  { title: 'Cabang', field: 'nameLocation', sortable: true },
{
    title: 'Detail',
    field: 'detail',
    sortable: false,
}
];

// Kolom untuk tab Susut (seperti sebelumnya)
const susutCols = [
  { title: 'No Transaksi', field: 'registration_asset_number', sortable: true },
  { title: 'Kategori', field: 'item.category.nama_katbrg', sortable: true },
  { title: 'Kode Aset', field: 'asset_number', sortable: true },
  { title: 'Nama Aset', field: 'item.nama_brg', sortable: true },
  { title: 'Lokasi', field: 'nameLocation', sortable: true },
  { title: 'Tgl Perolehan', field: 'ra_date', sortable: true },
  { title: 'Usia Ekonomis (Bulan)', field: 'item.category.umur', sortable: true },
  { title: 'Nilai Perolehan', field: 'nilai_perolehan', sortable: true },
  { title: 'Akm Peny per Bulan', field: 'nom_susut', sortable: true },
  { title: 'Total Akm Penyusutan', field: 'total_akm_penyusutan', sortable: true },
  { title: 'Nilai Buku', field: 'nilai_buku', sortable: true },
  { title: 'Tanggal Akhir Usia Aset', field: 'tgl_akhir', sortable: true },
  { title: 'Keterangan/PIC', field: 'assigned_to', sortable: true },
];

const allAssets = computed(() => {
    // Pastikan assetReport dan cabangList sudah ada
    // Map setiap aset, tambahkan nameLocation dari cabangList
    return assetReport.value.data.map((asset: any) => {
        // Cari cabang yang sesuai dengan asset.location
        const cabang = cabangList.value.find((c: { id_area: any }) => c.id_area === asset.location);
        return {
            ...asset,
            nameLocation: cabang ? cabang.nm_area : asset.location
        };
    });
});


const warehouseAssets = computed(() => allAssets.value.filter(a => a.status === 'Gudang'));
const susutRows = computed(() => allAssets.value); // Untuk tab susut

// Watch effect untuk menambahkan data custom di assetReport
watchEffect(() => {
  if (assetReport.value && Array.isArray(assetReport.value.data)) {
    assetReport.value.data.forEach((item: any) => {
      if (!item.custom_field) {
        item.custom_field = 'Custom Value';
      }
      // Contoh: hitung nilai buku jika belum ada
      if (item.susut && typeof item.susut.total_umur === 'number' && typeof item.susut.nom_susut === 'number') {
        
        // Contoh: tambahkan properti custom 'custom_field'
      item.nilai_perolehan = formatRupiah(item.susut.total_umur * item.susut.nom_susut);
      item.total_akm_penyusutan = formatRupiah((item.susut.total_umur - item.susut.sisa_umur) * item.susut.nom_susut);
      item.nilai_buku = formatRupiah(item.susut.sisa_umur * item.susut.nom_susut);
      item.nom_susut = formatRupiah(item.susut.nom_susut);  
      
      
      
      // Konversi kode PHP ke JavaScript:
      // $fd = explode('-',$a->tglref);
      // $tglreg = $fd[2].'-'.$fd[1].'-'.$fd[0];
      // $tglakhir = date('d-m-Y',mktime(0,0,0,($a->total_umur + (int)$fd[1]),(int)$fd[2],(int)$fd[0]));

      // ?>
      
      }
    });
  }
  
  console.log('assetReport updated:', assetReport.value);
});

function formatRupiah(val) {
  const num = Number(val);
  if (val === null || val === undefined || val === '' || isNaN(num)) return '-';
  return num.toLocaleString('id-ID');
}

// Fungsi ekspor/print table
const exportTable = (type: string) => {
  let columns = susutCols.map((d) => d.field);
  let records: any[] = [];
  if (activeTab.value === 'asset') records = assetRows.value;
  else if (activeTab.value === 'susut') records = susutRows.value;
  let filename = 'assets';
  let newVariable: any = window.navigator;
  // Util capitalize
  const capitalize = (text: string) => text.replace('_', ' ').replace('-', ' ').toLowerCase()
    .split(' ').map(s => s.charAt(0).toUpperCase() + s.substring(1)).join(' ');

  if (type == 'csv' || type == 'txt') {
    let coldelimiter = type == 'csv' ? ';' : ',';
    let linedelimiter = '\n';
    let result = columns.map((d) => capitalize(d)).join(coldelimiter);
    result += linedelimiter;
    records.forEach((item) => {
      columns.forEach((d: any, index) => {
        if (index > 0) result += coldelimiter;
        let val = getNested(item, d);
        result += val;
      });
      result += linedelimiter;
    });
    if (result == null) return;
    let mime = type == 'csv' ? 'application/csv' : 'application/txt';
    if (!result.match(/^data:text\/(csv|txt)/i) && !newVariable.msSaveOrOpenBlob) {
      var data = `data:${mime};charset=utf-8,` + encodeURIComponent(result);
      var link = document.createElement('a');
      link.setAttribute('href', data);
      link.setAttribute('download', filename + (type == 'csv' ? '.csv' : '.txt'));
      link.click();
    } else {
      var blob = new Blob([result]);
      if (newVariable.msSaveOrOpenBlob) newVariable.msSaveBlob(blob, filename + (type == 'csv' ? '.csv' : '.txt'));
    }
  } else if (type == 'print') {
    var rowhtml = '<p>' + filename + '</p>';
    rowhtml += '<table style="width: 100%; " cellpadding="0" cellcpasing="0"><thead><tr style="color: #515365; background: #eff5ff; -webkit-print-color-adjust: exact; print-color-adjust: exact; "> ';
    columns.forEach((d) => { rowhtml += '<th>' + capitalize(d) + '</th>'; });
    rowhtml += '</tr></thead><tbody>';
    records.forEach((item) => {
      rowhtml += '<tr>';
      columns.forEach((d: any) => {
        let val = getNested(item, d);
        rowhtml += '<td>' + val + '</td>';
      });
      rowhtml += '</tr>';
    });
    rowhtml += '<style>body {font-family:Arial; color:#495057;}p{text-align:center;font-size:18px;font-weight:bold;margin:15px;}table{ border-collapse: collapse; border-spacing: 0; }th,td{font-size:12px;text-align:left;padding: 4px;}th{padding:8px 4px;}tr:nth-child(2n-1){background:#f7f7f7; }</style>';
    rowhtml += '</tbody></table>';
    var winPrint: any = window.open('', '', 'left=0,top=0,width=1000,height=600,toolbar=0,scrollbars=0,status=0');
    winPrint.document.write('<title>Print</title>' + rowhtml);
    winPrint.document.close();
    winPrint.focus();
    winPrint.print();
  }
};

// Kolom untuk ekspor Excel
const excelColumns = () => {
  return {
    'No Transaksi': 'register_asset_number',
    'Kategori': 'item.category.nama_katbrg',
    'Kode Aset': 'asset_number',
    'Nama Aset': 'item.nama_brg',
    'Lokasi': 'location',
    'Tgl Perolehan': 'registrationAsset.ra_date',
    'Usia Ekonomis (Bulan)': 'item.category.umur',
    'Nilai Perolehan': 'nilai_perolehan',
    'Akm Peny per Bulan': 'susut.nom_susut',
    'Total Akm Penyusutan': 'total_depreciation',
    'Nilai Buku': 'book_value',
    'Tanggal Akhir Usia Aset': 'end_of_life_date',
    'Keterangan/PIC': 'pic',
  };
};
// Data untuk ekspor Excel
const excelItems = () => {
  if (activeTab.value === 'asset') return assetRows.value;
  else if (activeTab.value === 'susut') return susutRows.value;
  return [];
};

// const { data:cabang } = useGetCabangList();



// Fungsi getNested: untuk export/print, tanpa pemisah ribuan
function getNested(obj: any, path: string) {
  if (path === 'nilai_perolehan') {
    if (typeof obj.nilai_perolehan === 'number') {
      return obj.nilai_perolehan; // tanpa .toLocaleString
    }
    const susut = obj.susut;
    if (susut && typeof susut.total_umur === 'number' && typeof susut.nom_susut === 'number') {
      return susut.total_umur * susut.nom_susut;
    }
    return '-';
  }
  if (path === 'total_akm_penyusutan') {
    if (typeof obj.total_akm_penyusutan === 'number') {
      return obj.total_akm_penyusutan;
    }
    return '-';
  }
  if (path === 'nilai_buku') {
    if (typeof obj.nilai_buku === 'number') {
      return obj.nilai_buku;
    }
    return '-';
  }
  // Nested biasa (dot notation)
  return path.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : ''), obj);
}

// Kamera, Barcode, dsb (boleh diabaikan jika fokus laporan)
const openCamera = async () => {
  if (videoRef.value) {
    try {
      stream = await navigator.mediaDevices.getUserMedia({ video: true });
      videoRef.value.srcObject = stream;
    } catch (err) {
      alert('Tidak dapat mengakses kamera');
      showCamera.value = false;
    }
  }
};
const closeCamera = () => {
  showCamera.value = false;
  capturedImage.value = null;
  if (stream) { stream.getTracks().forEach(track => track.stop()); stream = null; }
};
const capturePhoto = () => {
  if (!videoRef.value) return;
  const canvas = document.createElement('canvas');
  canvas.width = videoRef.value.videoWidth;
  canvas.height = videoRef.value.videoHeight;
  const ctx = canvas.getContext('2d');
  if (ctx) {
    ctx.drawImage(videoRef.value, 0, 0, canvas.width, canvas.height);
    capturedImage.value = canvas.toDataURL('image/png');
  }
};
const retakePhoto = () => { capturedImage.value = null; };
const closeBarcodeScanner = async () => {
  showBarcodeScanner.value = false;
  const videoElems = document.querySelectorAll('#barcode-scanner video') as NodeListOf<HTMLVideoElement>;
  videoElems.forEach((video) => { if (video.srcObject) { const tracks = (video.srcObject as MediaStream).getTracks(); tracks.forEach((track) => track.stop()); video.srcObject = null; } });
  const allVideos = document.querySelectorAll('video') as NodeListOf<HTMLVideoElement>;
  allVideos.forEach((video) => { if (video.srcObject) { const tracks = (video.srcObject as MediaStream).getTracks(); tracks.forEach((track) => track.stop()); video.srcObject = null; } });
  if (barcodeScanner) { if (barcodeScanner.isScanning) { try { await barcodeScanner.stop(); } catch (e) {} } barcodeScanner.clear(); barcodeScanner = null; }
};

watch(showCamera, (val) => { if (val) { setTimeout(openCamera, 300); } else { closeCamera(); } });
watch(showBarcodeScanner, (val) => {
  if (val) {
    nextTick(() => {
      if (!barcodeScanner) {
        barcodeScanner = new Html5Qrcode("barcode-scanner");
        barcodeScanner.start(
          { facingMode: "environment" },
          { fps: 10, qrbox: 250 },
          (decodedText: string) => {
            closeBarcodeScanner();
            router.push(`/asset/detail/${decodedText}`);
          },
          () => {}
        ).catch((err) => {
          if (String(err).includes('already under transition')) {
            barcodeScanner!.stop().then(() => {
              barcodeScanner!.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText: string) => {
                  closeBarcodeScanner();
                  router.push(`/asset/detail/${decodedText}`);
                },
                () => {}
              );
            });
          } else {
            alert('Tidak dapat mengakses kamera untuk pemindaian barcode');
            closeBarcodeScanner();
          }
        });
      }
    });
  } else {
    closeBarcodeScanner();
  }
});
</script>