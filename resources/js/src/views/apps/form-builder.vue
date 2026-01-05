<template>
    <div v-if="isPending">
        <div class="flex flex-col items-center justify-center py-16">
            <svg class="animate-spin h-10 w-10 text-blue-500 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">Memuat data formulir...</p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-md shadow-md dark:bg-black" v-else>
        <!-- Header Section -->
        <div class="border-b border-gray-200 pb-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><span class="text-gray-600">Buat: </span>{{ formTitle
                        }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-medium">
                        {{ formNumberLabel }} :
                        {{ formData.formNumber || formNumberPlaceholder }}
                    </p>
                </div>
                <div class="flex gap-2 items-center">
                    <button @click="$router.back()" class="btn btn-outline-secondary flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali
                    </button>
                    <!-- <button
                @click="saveDraft"
                class="btn btn-outline-primary"
                v-if="isDraft"
                >
                Simpan sebagai Draf
                </button> -->
                    <button @click="submitForm" class="btn btn-primary flex items-center gap-2" v-if="isDraft">
                        Kirim
                        <!-- <svg class="w-4 h-4 rotate-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg> -->
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 486.736 486.736">
                            <g>
                                <path d="M481.883,61.238l-474.3,171.4c-8.8,3.2-10.3,15-2.6,20.2l70.9,48.4l321.8-169.7l-272.4,203.4v82.4c0,5.6,6.3,9,11,5.9
                                l60-39.8l59.1,40.3c5.4,3.7,12.8,2.1,16.3-3.5l214.5-353.7C487.983,63.638,485.083,60.038,481.883,61.238z"/>
                            </g>
                        </svg>
                    </button>
                    <!-- Cancel Button: hanya tampil jika status Draft dan layer 1 belum approve -->
                    <button
                        v-if="canShowCancelButton"
                        @click="cancelSubmission"
                        class="btn btn-outline-danger flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Ubah Pengajuan
                    </button>
                    <!-- Response Button (dropdown) -->
                    <div v-if="canShowResponseButton" class="relative ml-2">
                        <button @click="toggleResponseMenu" class="btn btn-outline-primary flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Respon
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div v-if="showResponseMenu"
                            class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded shadow-lg z-10">
                            <ul>
                                <li>
                                    <button @click="handleResponse('Approved')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Disetujui
                                    </button>
                                </li>
                                <li>
                                    <button @click="handleResponse('Revised')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        Direvisi
                                    </button>
                                </li>
                                <li>
                                    <button @click="handleResponse('Rejected')"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Ditolak
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Details Header -->
        <div class="flex items-center justify-end mb-6">
            <div class="status-label">
                <span :class="[
                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                    formData.status === 'Draft'
                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
                        : formData.status === 'Waiting Approval'
                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                            : formData.status === 'Full Approved'
                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                : formData.status === 'Rejected' || formData.status === 'Canceled'
                                    ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                    : '',
                ]">
                    {{ statusLabel(formData.status) }}
                </span>
            </div>
        </div>

        <!-- Approval Layer Section -->
        <div class="mb-8" v-if="(!isDraft || formData.status =='Revised') && formData.status != 'Canceled'">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Layer Approval</h2>
            <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                <table class="min-w-full">
                    <thead>
                        <tr class="text-gray-700 dark:text-gray-200">
                            <th class="px-4 py-2 text-left">Layer</th>
                            <th class="px-4 py-2 text-left">Nama Approval</th>
                            <th class="px-4 py-2 !text-center">Status</th>
                            <th class="px-4 py-2 !text-center">Catatan</th>
                            <th class="px-4 py-2 !text-center">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(layer, idx) in approvalLayers" :key="idx"
                            class="bg-white dark:bg-gray-900 border-b dark:border-gray-700">
                            <td class="px-4 py-2">{{ layer.layer }}</td>
                            <td class="px-4 py-2">{{ layer.email }}</td>
                            <td class="px-4 py-2 !text-center">
                                <span :class="[
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold text-center',
                                    layer.approval_status === 'Approved'
                                        ? 'bg-blue-100 text-green-800 dark:bg-green-900 dark:text-green-200'
                                        : layer.approval_status === 'In Progress'
                                            ? 'bg-blue-300 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
                                                : layer.approval_status === 'Rejected' || layer.approval_status === 'Canceled'
                                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                : layer.approval_status === 'Revised'
                                                    ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200'
                                                        : '',
                                ]">
                                    {{ statusLabel(layer.approval_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 !text-center">{{ layer.note }}</td>
                            <td class="px-4 py-2 !text-center">{{ layer.approval_date }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="formulir">
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">Formulir {{ formTitle }}</h1>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <!-- Select PR (for PO and ra only) -->
            <div v-if="formType === 'purchase-order'" class="col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pilih Permintaan Pembelian (PR)</label>
                <input type="text" v-model="selectedPR" @input="onPRSelect" class="form-input disabled" :disabled="!isDraft"
                    v-if="!isDraft" />
                <select v-if="isDraft" v-model="selectedPR" @change="onPRSelect" class="form-select" :disabled="!isDraft">
                    <option value="">Pilih PR</option>
                    <option v-for="pr in availablePRs" :key="pr.pr_number" :value="pr.pr_number">{{ pr.pr_number }} - {{ pr.cabang }}</option>
                </select>
            </div>

            <div v-if="formType === 'registration-asset'" class="md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Referensi Nomor PO</label>
                <input type="text" v-model="selectedPO" @input="onPOSelect" class="form-input disabled" :disabled="true"/>

                <!-- <select v-model="selectedPO" @change="onPOSelect" :class="['form-select', !isDraft ? 'disabled' : '']"
                    :disabled="!isDraft" v-else>
                    <option value="">Pilih Nomor PO</option>
                    <option v-for="po in availablePOs" :key="po.po_number" :value="po.po_number">{{ po.po_number }} - {{ po.purchase_request.cabang }}</option>
                </select> -->
            </div>

            <!-- BEGIN: Grouped Form Fields for PO/RA -->
            <template v-if="(formType === 'purchase-order' && selectedPR) || (formType === 'registration-asset' && selectedPO) || formType === 'purchase-request'">
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permintaan Oleh</label>
                    <input v-model="formData.requestedBy" type="text" class="form-input disabled" placeholder="Masukkan nama pemohon"
                        :readonly="true" :disabled="true" />
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cabang</label>
                    <!-- <input class="form-input disabled" v-model="formData.cabang" type="text" placeholder="Masukan Nama Cabang" v-if="store.user?.idgrup != 'JBT-018' || !isDraft"
                        :disabled="true" :readonly="true" /> -->
                     <select v-model="formData.cabang" :class="['form-select mb-4 w-64', store?.user?.idgrup != 'JBT-018' || !isDraft ? 'disabled text-black' : '']"
                         type="text"
                     :disabled="store?.user?.idgrup != 'JBT-018' || !isDraft">
                        <option value="9999">HO</option>
                        <option v-for="branch in cabangList" :key="branch.id_area" :value="branch.id_area">{{ branch.nm_area }}
                        </option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Permintaan</label>
                    <input type="text" class="form-input disabled" v-model="formData.jenisPermintaan" :readonly="true" :disabled="true"
                        placeholder="Masukan Jenis Permintaan" v-if="!isDraft || formType !== 'purchase-request'" />
                    <select v-if="isDraft && formType === 'purchase-request'"
                        :class="['form-input', !isDraft || formType !== 'purchase-request' ? 'disabled text-black' : '']"
                        v-model="formData.jenisPermintaan" type="text" :disabled="formType !== 'purchase-request' || !isDraft">
                        <option value="" selected disabled>Pilih Jenis Permintaan</option>
                        <option value="GA">GA</option>
                        <option value="IT">IT</option>
                    </select>
                </div>
                <div v-if="formType !== 'registration-asset'" class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alasan</label>
                    <textarea v-model="formData.justification"
                        :class="['form-textarea', !isDraft || formType == 'purchase-order' ? 'disabled' : '']" rows="3"
                        placeholder="Masukkan alasan untuk permintaan pembelian ini..." :readonly="!isDraft"
                        :disabled="!isDraft"></textarea>
                </div>
                <div v-if="formType === 'purchase-order' || formType === 'registration-asset'"
                    :class="[formType === 'purchase-order' ? 'md:col-span-2' : 'md:col-span-1']">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Vendor</label>
                    <select v-model="formData.vendor_id"
                        :class="['form-select', !isDraft || formType == 'registration-asset' ? 'disabled' : '']"
                        :disabled="!isDraft || formType == 'registration-asset'">
                        <option value="">Pilih Vendor</option>
                        <option v-for="vendor in vendorList" :key="vendor.id" :value="vendor.id">
                            {{ vendor.nama }}
                        </option>
                    </select>
                </div>
                <div v-if="formType === 'purchase-order'" class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal PO</label>
                    <input type="date" :min="formData.prDate" v-model="formData.requestDate"
                        :class="['form-input', !isDraft ? 'disabled' : '']" :readonly="!isDraft" :disabled="!isDraft" />
                </div>
            </template>
            <!-- END: Grouped Form Fields for PO/RA -->

            <!-- Tanggal RA untuk registration-asset -->
            <div v-if="formType === 'registration-asset'" class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Pendaftaran Asset</label>
                <input type="date" :class="['form-input', !isDraft ? 'disabled' : '']" :min="formData.poDate"
                    v-model="formData.requestDate" class="form-input" :readonly="!isDraft" :disabled="!isDraft" />
            </div>
            <div v-if="formType === 'registration-asset'" class="md:col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Invoice</label>
                <input type="date" :class="['form-input', !isDraft ? 'disabled' : '']" :min="formData.poDate"
                    v-model="formData.invoiceDate" class="form-input" :readonly="!isDraft" :disabled="!isDraft" />
            </div>
        </div>

        <!-- Items Section -->
        <div class="mb-6" v-if="formData.jenisPermintaan">
            <div class="flex items-center justify-between mb-4" v-if="formType === 'purchase-request'">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ itemsSectionTitle }}
                </h3>
                <button @click="addItem" class="btn btn-outline-primary btn-sm" v-if="isDraft">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-4 py-2 text-left">Nama Barang</th>
                            <th class="px-4 py-2 text-center">Pengajuan</th>
                            <th class="px-4 py-2 text-center">Kuantitas</th>
                            <th class="px-4 py-2 text-right">Harga Satuan</th>
                            <th class="px-4 py-2 text-right" v-if="formType === 'registration-asset'">Aset</th>
                            <th class="px-4 py-2 text-right">Total Harga</th>
                            <!-- <th class="px-4 py-2 text-center" v-if="formType === 'registration-asset'">Asset Tag</th>
              <th class="px-4 py-2 text-center" v-if="formType === 'registration-asset'">Location</th> -->
                            <th class="px-4 py-2 text-center" v-if="isDraft">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                        <td v-for="item in formData.items">{{ item }}</td>
                      </tr> -->
                        <tr v-for="(item, index) in formData.items" :key="index" class="border-b">
                            <td class="px-4 py-2">
                                <!-- <input 
                                    v-model="item.item_name" 
                                    type="text" 
                                    class="form-input w-full" 
                                    placeholder="Enter item item_name"
                                    v-if='formType == "purchase-request"'
                                    :readonly="!isDraft"
                                /> -->
                                <select name="" id=""
                                    :class="['form-input', !isDraft || formType != 'purchase-request' ? 'disabled' : '']"
                                    v-model="item.item_id" @change="onItemSelect(index, item.item_id)" :key="item.item_id"
                                    :disabled="!isDraft || formType != 'purchase-request'">
                                    <option value="">Pilih Barang</option>
                                    <option v-for="barang in filteredBrgData" :value="barang.id">
                                        {{ barang.nama_brg }} <span v-if="barang.ket_brg">-</span>
                                        {{ barang.ket_brg }}
                                    </option>
                                </select>

                                <!-- <select name="itemCategory" class="form-input w-full" id="" v-if='formType !== "purchase-request"' v-model="item.item_id" :disabled="!isDraft">
                                    <option value="">Select Category</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Stationery">Stationery</option>
                                    <option value="Software">Software</option>
                                    <option value="Other">Other</option>
                                </select> -->
                            </td>
                            <td class="px-4 py-2">
                                <!-- <input type="text" v-model="item.category" class="form-input w-full disabled" :readonly="isDraft"
                                    :disabled="isDraft" /> -->
                                <!-- pengajuan select -->
                                <select name="pengajuan" id="" v-model="item.pengajuan"
                                    :class="['form-input !w-36', !isDraft || formType != 'purchase-request' ? 'disabled' : '']" :disabled="!isDraft || formType != 'purchase-request'">
                                    <option value="" disabled>Pilih Pengajuan</option>
                                    <option value="Barang Baru">Barang Baru</option>
                                    <option value="Penggantian">Penggantian</option>
                                </select>
                                <!-- <select name="itemCategory" id="" v-model='item.id_kategori' :class="['form-input', !isDraft ? 'disabled' : '']">
                  <option value=""></option>
                  <option v-for="item in masterBrgData" v-value="item.nama_katbrg">{{ item.nama_katbrg }}</option>
                </select> -->
                            </td>
                            <td class="px-4 py-2">
                                <input v-model.number="item.quantity" type="number"
                                    :class="['form-input', '!w-20', 'mx-auto', !isDraft || formType != 'purchase-request'  ? 'disabled' : '']"
                                    min="1" @input="calculateTotal(index)" :readonly="!isDraft || formType != 'purchase-request'"
                                    :disabled="!isDraft || formType != 'purchase-request'" />
                            </td>
                            <td class="px-4 py-2">
                                <input
                                    :value="item.unit_price_display"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                    :class="['form-input !w-36', 'ml-auto', !isDraft ? 'disabled' : '']"
                                    @input="onUnitPriceInput($event, item, index)"
                                    :readonly="!isDraft"
                                    :disabled="!isDraft"
                                    autocomplete="off"
                                />
                            </td>
                            <td class="px-4 py-2 text-right" v-if="formType == 'registration-asset'">
                                <!-- <select name="is_asset" id="">
                                    <option value="1">Kapitalis</option>
                                    <option value="0">Non Kapitalis</option>
                                </select> -->
                                <input type="hidden" v-model="item.is_asset" class="form-input !w-24 ml-auto disabled" :readonly="true"
                                    :disabled="true"
                                    v-value='item.is_asset = item.unit_price >= 2500000 ? "Kapitalis" : "Non Kapitalis"' />

                                <!-- green for kapitalis -->
                                <span v-if="item.is_asset === 'Kapitalis'" class="text-green-600 font-semibold">Kapitalis</span>
                                <span v-else class="text-red-600 font-semibold">Non Kapitalis</span>
                            </td>
                            <td class="px-4 py-2 text-right font-semibold">Rp. {{ formatCurrency(item.total_price || 0) }}</td>
                            <!-- <td class="px-4 py-2" v-if="formType === 'registration-asset'">
                <input v-model="item.purchase_request_number" type="text"
                  :class="['form-input', 'w-32', 'mx-auto', !isDraft ? 'disabled' : '']" placeholder="AT-001" :readonly="!isDraft"
                  :disabled="!isDraft" />
              </td>
              <td class="px-4 py-2" v-if="formType === 'registration-asset'">
                <input v-model="item.location" type="text" :class="['form-input', 'w-32', 'mx-auto', !isDraft ? 'disabled' : '']"
                  placeholder="Room 101" :readonly="!isDraft" :disabled="!isDraft" />
              </td> -->
                            <td class="px-4 py-2" style="text-align: center !important" v-if="isDraft">
                                <button @click="removeItem(index)" class="text-red-600 hover:text-red-800" :disabled="!isDraft">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                            <td :colspan="formType === 'registration-asset' ? 5 : 4" class="px-4 py-2 text-right">Jumlah Total:</td>
                            <td class="px-4 py-2 text-right text-lg whitespace-nowrap">Rp {{ formatCurrency(totalAmount) }}</td>
                            <td v-if="isDraft"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer Section: Upload Notes and Attachments -->
        <div class="border-t border-gray-200 pt-6" v-if="formData.jenisPermintaan">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dokumen Lampiran</label>
                    <label for="file-upload" class="cursor-pointer">
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4" v-if="isDraft">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Unggah berkas</span>
                                    <input id="file-upload" name="file-upload" type="file" :class="!isDraft ? 'disabled' : ''"
                                        class="sr-only" multiple @change="handleFileUpload" accept=".pdf" :disabled="!isDraft" />
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, JPG, PNG hingga 10MB
                                        masing-masing</p>
                                </div>
                            </div>
                        </div>
                        <div v-else
                            class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg text-left text-sm text-gray-500 dark:text-gray-400"
                            id="see-attachment">
                            <div>
                                <span v-if="uploadedFiles.length === 0" class="text-gray-400">Tidak ada berkas terunggah.</span>
                                <ul v-else class="space-y-1">
                                    <li v-for="(file, idx) in uploadedFiles" :key="file.id || idx">
                                        <a  class="text-blue-600 hover:underline" @click="openFile(file.url_file)" target="_blank"
                                            rel="noopener">
                                            {{ file.file_name || file.name }}
                                        </a>
                                        <span class="text-xs text-gray-500 ml-2">({{ formatFileSize(file.size) }})</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </label>

                    <!-- Uploaded Files List -->
                </div>
                <div v-if="uploadedFiles.length > 0 && isDraft" class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Berkas yang Diunggah:</h4>
                    <ul class="space-y-2">
                        <li v-for="(file, index) in uploadedFiles" :key="index"
                            class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ file.file_name }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ formatFileSize(file.size) }})</span>
                            </div>
                            <button @click="removeFile(file.id)" class="text-red-600 hover:text-red-800" :disabled="!isDraft">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Notes Section -->
            <!-- <div class="w-full">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keterangan</label>
                    <div class="flex gap-2 mb-2" v-if='canWriteNote'>
                        <textarea v-model="newNote" :class="['form-textarea']" class="form-textarea flex-1" rows="2"
                            placeholder="Ketik keterangan..." :disabled="!canWriteNote"></textarea>
                        <button @click="sendNote" class="btn btn-primary" :disabled="!canWriteNote">
                            Kirim
                        </button>
                    </div>
                    <textarea v-model="formData.notes" :class="['form-textarea']" class="form-textarea" rows="4"
                        placeholder="Masukkan keterangan atau komentar tambahan..." :readonly="!isDraft" :disabled="!isDraft"
                        style="display: none"></textarea>

                    <div class="mt-4 space-y-3">
                        <div v-for="(note, idx) in notesHistory" :key="idx" class="flex items-start gap-2" :class="{
                            'justify-end': note.sender === currentUser!.username,
                            'justify-start': note.sender !== currentUser!.username,
                        }">
                            <div :class="[
                                'rounded-lg px-4 py-2 max-w-lg',
                                note.sender === currentUser!.username
                                    ? 'bg-blue-50 dark:bg-blue-900 text-right ml-auto'
                                    : 'bg-gray-100 dark:bg-gray-700 text-left mr-auto',
                            ]">
                                <div class="flex items-center gap-2 mb-1" :class="note.sender === currentUser!.username
                                        ? 'justify-end'
                                        : 'justify-start'
                                    ">
                                    <span class="font-semibold" :class="note.sender === currentUser!.username
                                            ? 'text-blue-700 dark:text-blue-200'
                                            : 'text-gray-700 dark:text-gray-200'
                                        ">
                                        {{ note.sender }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{
                                        note.time
                                        }}</span>
                                </div>
                                <div :class="note.sender === currentUser!.username
                                        ? 'text-blue-900 dark:text-blue-100'
                                        : 'text-gray-800 dark:text-gray-200'
                                    ">
                                    {{ note.text }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <!-- Chat Notes Section (after submit) -->
        <div v-if="showNotesChat" class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Riwayat keterangan</h3>
            <div class="space-y-3">
                <div v-for="(note, idx) in notesHistory" :key="idx" class="flex items-start gap-2">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2">
                        <div class="text-sm text-gray-800 dark:text-gray-200">
                            {{ note.text }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ note.time }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambahkan di bawah judul form atau di bagian mana saja untuk menampilkan hasil purchaseOrderData -->
        <!-- <div v-if="purchaseOrderData && formType === 'purchase-order'" class="mb-4 p-3 bg-blue-50 rounded">
            <strong>Info Pesanan Pembelian:</strong><br />
            Nomor PO: {{ purchaseOrderData.formNumber }}<br />
            Vendor: {{ purchaseOrderData.vendorName }}<br />
            PR Terkunci: <span v-if="purchaseOrderData.prLocked">Ya</span><span v-else>Tidak</span>
        </div> -->
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import { useMeta } from '@/composables/use-meta';
import { useAppStore } from '@/stores/index';

import { useCheckUser, useDetailSubmission, useGetFileList, useGetListApproved, useGetMasterBrg, useGetVendorList } from '@/services/queries';
import { useSaveDraftPurchaseRequest, useSendNote, useSetApprovalStatus, useSubmitPurchaseOrder, useSubmitPurchaseRequest, useSubmitRegistrationAsset, useUploadFileSubmission, useEditSubmission } from '@/services/mutations';
import { useFileDelete, useFileUpload } from '@/composables/useFileUpload';
import { usePurchaseOrder } from '@/composables/usePurchaseOrder';
import { useSubmissionForm } from '@/composables/useSubmissionForm';
import { useGetNotes } from '@/services/queries';
import { onNominalChange, separateNominal, noSeparateNominal } from '@/composables/separate';
import { useGetAssetReport, useGetCabangList } from '@/services/queries';


// Cancel Button logic
const canShowCancelButton = computed(() => {
    // Tampilkan jika status Draft dan approvalLayers ada, dan layer 1 (index 0) belum approve
    return (approvalLayers?.value[0]?.approval_status == "In Progress" && formData.value.status !== 'Draft' && formData.value.status != 'Canceled' && isFromSubmission.value);
});

const cancelSubmission = async () => {
    const data = {
        type: formType.value,
        number: formNumber.value,
        requestedBy: formData.value.requestedBy,
    };
    const confirm = await Swal.fire({
        title: 'Ubah Pengajuan?',
        text: 'Apakah Anda yakin ingin mengubah pengajuan ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
    });
    if (confirm.isConfirmed) {
            submissionEdit.mutateAsync(data);
    }
};

// Routing & Form Type
const route = useRoute();
const router = useRouter();
const formType = ref((route.params.formType as string) || (route.query.type as string) || 'purchase-request');
const formNumber = ref((route.params.formNumber as string) || '');

// Form Config
const formConfig = computed(() => {
    switch (formType.value) {
        case 'purchase-request':
            return {
                title: 'Permintaan Pembelian',
                item_name: 'Buat permintaan pembelian barang untuk kebutuhan departemen Anda',
                numberLabel: 'Nomor PR',
                numberPrefix: 'PR',
                itemsTitle: 'Daftar Barang Diminta',
            };
        case 'purchase-order':
            return {
                title: 'Pesanan Pembelian',
                item_name: 'Buat pesanan pembelian untuk pengadaan barang dari supplier',
                numberLabel: 'Nomor PO',
                numberPrefix: 'PO',
                itemsTitle: 'Daftar Barang Dibeli',
            };
        case 'registration-asset':
            return {
                title: 'Pendaftaran Asset',
                item_name: 'Pendaftaran asset baru untuk keperluan inventaris',
                numberLabel: 'Nomor Asset',
                numberPrefix: 'ASSET',
                itemsTitle: 'Daftar Asset',
            };
        default:
            return {
                title: 'Form Builder',
                item_name: 'Buat formulir baru',
                numberLabel: 'Nomor Formulir',
                numberPrefix: 'F',
                itemsTitle: 'Daftar Barang',
            };
    }
});

const formTitle = computed(() => formConfig.value.title);
const formNumberLabel = computed(() => formConfig.value.numberLabel);
const formNumberPlaceholder = computed(() => `${formConfig.value.numberPrefix}-${new Date().getFullYear()}-001`);
const itemsSectionTitle = computed(() => formConfig.value.itemsTitle);

useMeta({ title: 'Formulir' });

const isEditMode = computed(() => !!formNumber.value);

// Draft status
const isDraft = ref<boolean>(true);
const isFieldSecret = ref<boolean>(false);
const openFile = (url: string) => {
    const storage = window.location.origin + '/storage/' + url;
    window.open(storage, "MsgWindow", "width=800,height=600,left=200,top=100,resizable=yes,scrollbars=yes");
};
// formData.items some
// check if unit_price 2.500.000

const filteredBrgData = computed(() => {
    if (!masterBrgData.value || !formData.value.jenisPermintaan) return [];

    // if (formType.value === 'purchase-request' && isDraft.value) {
    //     formData.value.items = defaultFormData().items;
    // }

    return masterBrgData.value.filter((barang: any) => barang.category.type_katbrg === formData.value.jenisPermintaan);
});

const capitalize = (val: string) => (val ? val.charAt(0).toUpperCase() + val.slice(1) : '');

// Form Data
const defaultFormData = () => ({
    formNumber: '',
    cabang: '',
    jenisPermintaan: '',
    requestDate: new Date().toISOString().slice(0, 16),
    requestedBy: '',
    department: '',
    vendor_id: '',
    prDate: '',
    poDate: '',
    poReference: '',
    prReference: '',
    raReference: '',
    justification: '',
    notes: '',
    assigned_to: '',
    invoiceDate: '',
    status: 'Draft',
    items: [
        {
            item_id: '',
            category: '',
            quantity: 1,
            unit_price: 0,
            unit_price_display: '0',
            total_price: 0,
            is_asset: '',
            formNumber: '',
            // additional_information: '',
            pengajuan: 'Barang Baru',
        },
    ],
});
const formData = ref(defaultFormData());
const approvalLayers = ref<any[]>([]);

// File & Notes
const newNote = ref('');
const notesHistory = ref<{ text: string; time: string; sender: string }[]>([]);
const showNotesChat = ref(false);
const showNotesModal = ref(false);

// PR Data
const selectedPR = ref('');
const limitTable = ref(null);

// PO Data
const selectedPO = ref('');

// Queries
const { data: submissionRef, isPending, isSuccess } = useDetailSubmission(formType.value, formNumber.value);
const { data: masterBrgData } = useGetMasterBrg();
const { data: fileListRef } = useGetFileList(formNumber.value);
const { data: availablePRs } = useGetListApproved(formType.value);
const { data: availablePOs } = useGetListApproved(formType.value);
const { data: cabangList } = useGetCabangList();
const { data: vendorList } = useGetVendorList();
const { data: userDataRef, isLoading, error, refetch } = useCheckUser();
const { data: notedDataRef } = useGetNotes(formType.value, formNumber.value);

// mutation
const mutationDelete = useFileDelete();
const mutateSubmitPR = useSubmitPurchaseRequest();
const mutateSubmitPO = useSubmitPurchaseOrder();
const mutateSubmitRA = useSubmitRegistrationAsset();
// const mutateSaveDraft = useSaveDraftPurchaseRequest(); // save as draft
const mutateSetStatus = useSetApprovalStatus();
const submissionEdit = useEditSubmission();


const onItemSelect = (index: number, id_brg: string) => {
    console.log('id_brg', id_brg);
    const selectedItem = filteredBrgData.value.find((item: any) => item.id === id_brg);

    if (selectedItem) {
        console.log('stroke-linejoin selectedItem', selectedItem);
        formData.value.items[index].category = selectedItem.category.nama_katbrg;
        console.log('formData.value.items[index].category', formData.value.items[index].category);
    } else {
        console.log('opopo');
        formData.value.items[index].category = '';
    }
};

const store = useAppStore();
const currentUser = computed(() => store.user ?? null);

const isFromSubmission = computed(() => {
    console.log('route.query.from', route.query);
    return route.query.from === 'submission' || !(route.query.from === 'approval');
});

const canShowResponseButton = computed(() => {
    if (isFromSubmission.value) return false;
    if (formData.value.status !== 'Waiting Approval') return false;
    // Cek apakah ada approval layer milik user login yang statusnya "In Progress"
    return approvalLayers.value.some((layer) => layer.approval_status === 'In Progress' && layer.approver_by === currentUser.value?.username);
});

// computed langsung dari query
const uploadedFiles = computed(() => fileListRef.value ?? []);
// Pastikan ini dipanggil sebagai function jika dari composable
const purchaseOrderData = usePurchaseOrder(formType, formData, vendorList);

console.log('uploadedFiles', uploadedFiles.value);

// Watch submission data (gunakan composable)
useSubmissionForm({
    submissionRef,
    formType,
    formData,
    defaultFormData,
    isDraft,
    approvalLayers,
    selectedPR,
    selectedPO,
    currentUser,
    userDataRef,
    notedDataRef,
    notesHistory,
});

// PR selection
const onPRSelect = () => {
    // PR sudah lock jika bukan draft
    if (!isDraft.value) return;
    const pr = availablePRs.value.find((pr) => pr.pr_number === selectedPR.value);
    console.log('Selected PR:', pr);
    if (pr) {
        limitTable.value = pr.purchase_request_items.length;
        // reset items dulu
        formData.value.items = [];
        formData.value.cabang = pr.cabang;
        formData.value.jenisPermintaan = pr.jenis_permintaan;
        formData.value.requestedBy = pr.created_by;
        formData.value.justification = pr.justification;
        formData.value.department = pr.department;
        formData.value.prDate = pr.created_at.slice(0, 10);
        formData.value.prReference = selectedPR.value;
        formData.value.items = pr.purchase_request_items.map((item: any) => ({
            item_id: item.item_id,
            quantity: item.quantity,
            pengajuan: item.pengajuan,
            category: item.item_master.category.nama_katbrg,
            additional_information: item.item_master.ket_brg,
            unit_price: item.unit_price,
            unit_price_display: separateNominal(item.unit_price),
            total_price: item.total_price,
            purchase_request_number: item.purchase_request_number,
        }));
        console.log('formData PR', formData.value);
    }
};

// PR selection
const onPOSelect = () => {
    // PR sudah lock jika bukan draft
    if (!isDraft.value) return;
    const po = availablePOs.value.find((po) => po.po_number === selectedPO.value);
    console.log('Selected PO:', po);
    if (po) {
        limitTable.value = po.purchase_order_items.length;
        // reset items dulu
        formData.value.items = [];
        formData.value.cabang = po.purchase_request.cabang;
        formData.value.requestedBy = po.purchase_request.created_by;
        formData.value.justification = po.purchase_request.justification;
        formData.value.jenisPermintaan = po.purchase_request.jenis_permintaan;
        formData.value.department = po.purchase_request.department;
        formData.value.vendor_id = po.vendor_id;
        // created date sementara
        formData.value.poDate = po.created_at.slice(0, 10);
        formData.value.poReference = selectedPO.value;
        formData.value.assigned_to = po.purchase_request.created_by;
        formData.value.prReference = po.purchase_request.pr_number;
        // Penjabaran item sesuai quantity
        const expandedItems: any[] = [];
        po.purchase_order_items.forEach((item: any) => {
            for (let i = 0; i < item.quantity; i++) {
                expandedItems.push({
                    item_id: item.item_id,
                    category: item.item_master.category.nama_katbrg,
                    pengajuan: item.pengajuan,
                    additional_information: item.item_master.ket_brg,
                    quantity: 1,
                    unit_price: item.unit_price,
                    unit_price_display: separateNominal(item.unit_price),
                    total_price: item.unit_price,
                    purchase_order_number: item.purchase_request_number,
                    // tambahkan property lain jika perlu
                });
            }
        });
        formData.value.items = expandedItems;
        console.log('formData PR', formData.value);
    }
};

watch(
    () => formData.value.items,
    (newItems) => {
        newItems.some((item) => {
            if (item.unit_price >= 2500000 && formType.value === 'registration-asset') {
                isFieldSecret.value = true;
                return true; // stop iterasi
            }
            isFieldSecret.value = false;
            return false;
        });
    },
    { deep: true },
);

// Inisialisasi mutation
const mutateSendNote = useSendNote();

// Generate form number
const generateFormNumber = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000)
        .toString()
        .padStart(3, '0');
    const newFormNumber = `${formConfig.value.numberPrefix}-${year}${month}${day}-${random}`;
    formData.value.formNumber = newFormNumber;
    router.replace(`/apps/form-builder/${formType.value}/${newFormNumber}`);
};

// Load form data (stub)
const loadFormData = (formNum: string) => {
    // TODO: Load from API/database if needed
};

// On mount/init
onMounted(() => {
    if (formNumber.value) {
        formData.value.formNumber = formNumber.value;
        loadFormData(formNumber.value);
    } else {
        Object.assign(formData.value, defaultFormData());
        generateFormNumber();
    }
});

// Watch route changes
watch(
    () => route.params,
    (newParams) => {
        formType.value = (newParams.formType as string) || 'purchase-request';
        formNumber.value = (newParams.formNumber as string) || '';
        if (formNumber.value) {
            formData.value.formNumber = formNumber.value;
            loadFormData(formNumber.value);
        } else {
            Object.assign(formData.value, defaultFormData());
            generateFormNumber();
        }
    },
);

// Item management
const addItem = () => {
    const newItem: any = defaultFormData().items[0];
    if (formType.value === 'registration-asset') {
        newItem.purchase_request_number = '';
        newItem.location = '';
    }
    if (limitTable.value && formData.value.items.length >= limitTable.value) {
        showMessage(`Maksimum item yang dapat ditambahkan adalah ${limitTable.value}.`, 'error');
        return;
    }
    formData.value.items.push(newItem);
};
const removeItem = (index: number) => {
    if (formData.value.items.length > 1) formData.value.items.splice(index, 1);
};
const calculateTotal = (index: number) => {
    const item = formData.value.items[index];
    item.total_price = (item.quantity || 0) * (item.unit_price || 0);
};
const totalAmount = computed(() => formData.value.items.reduce((sum, item) => sum + (item.total_price || 0), 0));
const mutateFileUpload = useFileUpload();

const files = ref([]);

// File handling
const handleFileUpload = async (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        // Batasi total file yang diupload maksimal 5
        const currentCount = uploadedFiles.value.length;
        const filesToUpload = Array.from(target.files);
        if (currentCount + filesToUpload.length > 5) {
            showMessage('Maksimal 5 file dapat diunggah.', 'error');
            target.value = '';
            return;
        }
        for (const file of filesToUpload) {
            if (file.size <= 10 * 1024 * 1024) {
                uploadedFiles.value.push(file);

                const dataUpload = {
                    formNumber: formData.value.formNumber,
                    formType: formType.value,
                    uploaded_by: formData.value.requestedBy,
                    // type: formType.value,
                };
                console.log('dataUpload', dataUpload);

                // panggil mutateAsync saja, jangan useFileUpload ulang!
                await mutateFileUpload.mutateAsync({ data: dataUpload, file: file });
            } else {
                showMessage(`File ${file.name} terlalu besar. Ukuran maksimum adalah 10MB.`, 'error');
            }
        }
        target.value = '';
    }
};
const removeFile = (id: number) => {
    mutationDelete.mutateAsync(id);
    // uploadedFiles.value.splice(index, 1);
    // TODO: Call API to delete file from server
    // mutationDelete.mutateAsync({
    //     formNumber: formData.value.formNumber,
    //     fileId: uploadedFiles?.value[index].id
    // });
};

// Notes
// Modifikasi fungsi sendNote
const sendNote = async () => {
    if (!newNote.value.trim()) return;

    // Kirim ke backend
    try {

        await mutateSendNote.mutateAsync({
            formNumber: formData.value.formNumber,
            formType: formType.value,
            text: newNote.value,
            sender: currentUser.value?.username || formData.value.requestedBy || 'Me',
            time: new Date().toISOString(),
        });

        // Tambahkan ke local history (opsional, jika ingin langsung tampil)
        notesHistory.value.push({
            text: newNote.value,
            time: new Date().toLocaleString(),
            sender: currentUser.value?.username || formData.value.requestedBy || 'Me',
        });
        isNotes.value = true;
        newNote.value = '';
    } catch (err) {
        console.error('Error sending note:', err);
        showMessage('Gagal mengirim keterangan. Silakan coba lagi.', 'error');
        return;
    }
};

// Dummy approval layer data
const approvalSet = ref();

// Response dropdown state
import { onClickOutside } from '@vueuse/core';
import { ref as vueRef } from 'vue';
import MasterBarang from '../barang/master-barang.vue';
import { UserData } from '@/types/user';
import { AlertType } from '@/types/master';

const showResponseMenu = ref(false);
const responseMenuRef = vueRef(null);
const isNotes = ref(false);

function toggleResponseMenu() {
    showResponseMenu.value = !showResponseMenu.value;
}
async function handleResponse(action: string) {
    showResponseMenu.value = false;

    // Prompt for note (required for all actions)
    const { value: noteText } = await Swal.fire({
        title: `Konfirmasi Response: ${action.charAt(0).toUpperCase() + action.slice(1)}`,
        input: 'textarea',
        inputLabel: 'Silakan isi keterangan',
        inputPlaceholder: 'Tulis keterangan di sini...',
        inputAttributes: {
            'aria-label': 'Tulis keterangan di sini',
        },
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        inputValidator: (value: string) =>
            !value || value.trim() === '' ? "Keterangan wajib diisi!" : null
    });

    if (!noteText) {
        showMessage(`Silakan isi keterangan sebelum mengirim ${action}`, 'error');
        return;
    }

    // Kirim note ke backend
    await mutateSendNote.mutateAsync({
        formNumber: formData.value.formNumber,
        formType: formType.value,
        text: noteText,
        sender: currentUser.value?.username || formData.value.requestedBy,
        time: new Date().toISOString(),
    });
    notesHistory.value.push({
        text: noteText,
        time: new Date().toLocaleString(),
        sender: currentUser.value?.username || formData.value.requestedBy,
    });
    isNotes.value = true;

    approvalSet.value = {
        formNumber: formData.value.formNumber,
        layer: approvalLayers.value.find((layer: any) => layer.approval_status === 'In Progress')?.layer || '',
        status: action,
        type: formType.value,
        usernameApprover: currentUser.value?.username || '',
    };

    await mutateSetStatus.mutateAsync(approvalSet.value);
    showMessage(`Response "${action}" berhasil dikirim!`, 'success');
    formData.value.status = action;
}
// Tutup dropdown jika klik di luar
onClickOutside(responseMenuRef, () => {
    showResponseMenu.value = false;
});

// Fungsi mapping status DB ke label Indonesia
const statusLabel = (status: string) => {
    switch ((status || '').toLowerCase()) {
        case 'draft':
            return 'Draf';
        case 'waiting approval':
            return 'Menunggu Persetujuan';
        case 'approved':
            return 'Disetujui';
        case 'full approved':
            return 'Disetujui';
        case 'revised':
            return 'Revisi';
        case 'rejected':
            return 'Tolak';
        case 'in progress':
            return 'Sedang Diproses';
        case 'canceled':
            return 'Dibatalkan';
        default:
            return capitalize(status) || '';
    }
};

const canWriteNote = computed(() => {
    // User login
    const user = currentUser.value;
    if (!user) return false;

    // Draft atau Revised, hanya creator yang bisa menulis
    if ((formData.value.status === 'Draft' || formData.value.status === 'Revised') && formData.value.requestedBy === user.username) {
        return true;
    }

    // Waiting Approval, hanya approval yang sedang in progress
    if (formData.value.status === 'Waiting Approval') {
        return approvalLayers.value.some((layer) => layer.approval_status === 'In Progress' && layer.approver_by === user.username);
    }

    return false;
});


// Handler input untuk separate dan sync value
function onUnitPriceInput(event: Event, item: any, index: number) {
    let val = (event.target as HTMLInputElement).value;
    // Ambil hanya digit
    let clean = noSeparateNominal(val);
    // Jika tidak ada angka, default 0
    if (!clean) {
        item.unit_price_display = '0';
        item.unit_price = 0;
    } else {
        // Format ribuan
        item.unit_price_display = separateNominal(clean);
        item.unit_price = Number(clean);
    }
    // Set value input agar selalu terformat (force update input value)
    (event.target as HTMLInputElement).value = item.unit_price_display;
    calculateTotal(index);
}

// Utility
const formatCurrency = (amount: number) => new Intl.NumberFormat('id-ID').format(amount);
const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024,
        sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const showMessage = (msg = '', type: AlertType = 'success') => {
    Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        customClass: { container: 'toast' },
    }).fire({ icon: type, title: msg, padding: '10px 20px' });
};

// Form actions
// const saveDraft = () => {
//     if (!formData.value.requestedBy || !formData.value.department) {
//         showMessage('Silakan lengkapi kolom yang wajib diisi (Permintaan Oleh, Departemen)', 'error');
//         return;
//     }

//     mutateSaveDraft.mutateAsync(formData.value);

//     // TODO: Save to API/database
//     showMessage(`${formTitle.value} (${formData.value.formNumber}) berhasil disimpan sebagai draf!`);
// };

const submitForm = () => {
    if (!formData.value.requestedBy || !formData.value.department) {
        showMessage('Silakan lengkapi kolom yang wajib diisi (Permintaan Oleh, Departemen)', 'error');
        return;
    }

    const hasValidItems = formData.value.items.some((item) => item.item_id !== '');
    if (!hasValidItems) {
        showMessage('Silakan tambahkan setidaknya satu item dengan deskripsi', 'error');
        return;
    }

    if (formType.value === 'purchase-order' && !formData.value.vendor_id) {
        showMessage('Silakan pilih vendor untuk pesanan pembelian', 'error');
        return;
    }
    if (formType.value === 'registration-asset' && !formData.value.poReference) {
        showMessage('Silakan pilih referensi PO untuk registrasi aset', 'error');
        return;
    }

    if (formType.value === 'registration-asset') {
        // tanggal RA dan tanggal invoice
        if (!formData.value.requestDate) {
            showMessage('Silakan lengkapi tanggal RA untuk registrasi aset', 'error');
            return;
        }
        if (!formData.value.invoiceDate) {
            showMessage('Silakan lengkapi tanggal invoice untuk registrasi aset', 'error');
            return;
        }
    }
    if (uploadedFiles.value.length === 0) {
        showMessage('Silakan unggah minimal satu berkas lampiran!', 'error');
        return;
    }
    // Submit ke API sesuai tipe form
    if (formType.value === 'purchase-request') {
        console.log('Submitting PR with data:', formData.value);
        mutateSubmitPR.mutateAsync(formData.value);
    } else if (formType.value === 'purchase-order') {
        console.log('Submitting PO with data:', formData.value);
        mutateSubmitPO.mutateAsync(formData.value);
    } else if (formType.value === 'registration-asset') {
        console.log('Submitting RA with data:', formData.value);
        mutateSubmitRA.mutateAsync(formData.value);
    }
};

// computed: purchaseOrderData
// const purchaseOrderData = computed(() => {
//   if (
//     formType.value === 'purchase-order' &&
//     formData.value.status &&
//     formData.value.status.toLowerCase() !== 'draft'
//   ) {
//     // Data PO sudah fix, PR sudah lock
//     return {
//       ...formData.value,
//       vendorName: vendorList.value?.find(v => v.id === formData.value.vendor_id)?.nama || '',
//       prLocked: true,
//     };
//   }
//   return null;
// });

// --- Cara menggunakan purchaseOrderData di template ---
// Contoh: tampilkan info PO jika bukan draft
/*
<div v-if="purchaseOrderData">
  <div class="mb-4 p-3 bg-blue-50 rounded">
    <strong>Info Purchase Order:</strong><br>
    Nomor PO: {{ purchaseOrderData.formNumber }}<br>
    Vendor: {{ purchaseOrderData.vendorName }}<br>
    PR Terkunci: <span v-if="purchaseOrderData.prLocked">Ya</span><span v-else>Tidak</span>
  </div>
</div>
*/
</script>

<style scoped>
.btn {
    @apply px-4 py-2 rounded-md font-medium transition duration-200;
}

.disabled {
    @apply bg-gray-200 text-gray-500 cursor-not-allowed;
}

.btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700;
}

.btn-outline-primary {
    @apply border border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white;
}

.btn-outline-secondary {
    @apply border border-gray-300 text-gray-700 hover:bg-gray-50;
}

.btn-sm {
    @apply px-3 py-1 text-sm;
}

.form-input,
.form-select,
.form-textarea {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white;
}

.table-auto {
    @apply min-w-full border-collapse;
}

/* checkbox checked disabled */
input[type='checkbox']:checked:disabled {
    @apply bg-blue-700 border-blue-300;
}

.table-auto th,
.table-auto td {
    @apply border border-gray-200 dark:border-gray-600;
}
</style>
