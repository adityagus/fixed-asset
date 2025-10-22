<template>
    <div class="bg-white p-6 rounded-md shadow-md dark:bg-black">
        <!-- Header Section -->
        <div class="border-b border-gray-200 pb-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><span class='text-gray-600'>Create: </span>{{ formTitle }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 font-medium">
                      {{ formNumberLabel }} : {{ formData.formNumber || formNumberPlaceholder }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <button @click="$router.back()" class="btn btn-outline-secondary" v-if="isDraft">Back</button>
                    <button @click="saveDraft" class="btn btn-outline-primary" v-if="isDraft">Save as Draft</button>
                    <button @click="submitForm" class="btn btn-primary" v-if="isDraft">Submit</button>
                </div>
            </div>
        </div>

        <!-- Form Details Header -->
         <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ formTitle }} Form</h1>

        

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
          <!-- Select PR (for PO and AR only) -->
        <div v-if="formType === 'purchaseOrder' || formType === 'assetRegistration'" class="col-span-3">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Purchase Request (PR)</label>
            <select v-model="selectedPR" @change="onPRSelect" class="form-select">
                <option value="">Select PR</option>
                <option v-for="pr in availablePRs" :key="pr.formNumber" :value="pr.formNumber">
                    {{ pr.formNumber }}
                </option>
            </select>
        </div>
          
          <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Requested By</label>
                <input 
                    v-model="formData.requestedBy" 
                    type="text" 
                    class="form-input disabled" 
                    placeholder="Enter requester name"
                    :readonly="!isDraft"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Regional</label>
                <input 
                    class='form-input read-only:bg-gray-50/5 read-only:text-gray-500 dark:read-only:bg-gray-700'
                    v-model="formData.cabangUser" 
                    v-value='"cabang"'
                    type="text" 
                    disabled
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                <select v-model="formData.department" class="form-select disabled" :disabled="!isDraft">
                    <option value="">Select Department</option>
                    <option value="IT Department">IT Department</option>
                    <option value="HR Department">HR Department</option>
                    <option value="Finance Department">Finance Department</option>
                    <option value="Operations Department">Operations Department</option>
                    <option value="Marketing Department">Marketing Department</option>
                </select>
            </div>
            <!-- Justification (only for Purchase Request) -->
            <div v-if="formType === 'purchaseRequest'" class="col-span-12 md:col-span-3">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Justification</label>
                <textarea 
                    v-model="formData.justification" 
                    class="form-textarea" 
                    rows="3"
                    placeholder="Enter the reason for this purchase request..."
                    :readonly="!isDraft"
                ></textarea>
            </div>
            
            <div v-if="formType === 'purchaseOrder'" class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Supplier</label>
                <select v-model="formData.supplier" class="form-select" :disabled="!isDraft">
                    <option value="">Select Supplier</option>
                    <option value="PT. Komputer Indonesia">PT. Komputer Indonesia</option>
                    <option value="PT. Monitor Nusantara">PT. Monitor Nusantara</option>
                    <option value="PT. Office Supplies">PT. Office Supplies</option>
                </select>
            </div>
            <div v-if="formType === 'assetRegistration'" class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PO Number Reference</label>
                <select v-model="formData.poReference" class="form-select" :disabled="!isDraft">
                    <option value="">Select PO Number</option>
                    <option value="PO-2025-001">PO-2025-001</option>
                    <option value="PO-2025-002">PO-2025-002</option>
                </select>
            </div>
        </div>

        <!-- Items Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ itemsSectionTitle }}</h3>
                <button @click="addItem" class="btn btn-outline-primary btn-sm" v-if="isDraft">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="px-4 py-2 text-left">Item Description</th>
                            <th class="px-4 py-2 text-center">Quantity</th>
                            <th class="px-4 py-2 text-right" v-if="formType !== 'assetRegistration'">Unit Price</th>
                            <th class="px-4 py-2 text-right" v-if="formType !== 'assetRegistration'">Total Price</th>
                            <th class="px-4 py-2 text-center" v-if="formType === 'assetRegistration'">Asset Tag</th>
                            <th class="px-4 py-2 text-center" v-if="formType === 'assetRegistration'">Location</th>
                            <th class="px-4 py-2 text-center" v-if="isDraft">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in formData.items" :key="index" class="border-b">
                            <td class="px-4 py-2">
                                <input 
                                    v-model="item.description" 
                                    type="text" 
                                    class="form-input w-full" 
                                    placeholder="Enter item description"
                                    v-if='formType == "purchaseRequest"'
                                    :readonly="!isDraft"
                                />
                                <select name="itemCategory" class="form-input w-full" id="" v-if='formType !== "purchaseRequest"' v-model="item.description" :disabled="!isDraft">
                                    <option value="">Select Category</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Stationery">Stationery</option>
                                    <option value="Software">Software</option>
                                    <option value="Other">Other</option>
                                </select>
                            </td>
                            <td class="px-4 py-2">
                                <input 
                                    v-model.number="item.qty" 
                                    type="number" 
                                    class="form-input w-20 mx-auto" 
                                    min="1"
                                    @input="calculateTotal(index)"
                                    :readonly="!isDraft"
                                />
                            </td>
                            <td class="px-4 py-2" v-if="formType !== 'assetRegistration'">
                                <input 
                                    v-model.number="item.unitPrice" 
                                    type="number" 
                                    class="form-input w-24 ml-auto" 
                                    min="0"
                                    step="0.01"
                                    @input="calculateTotal(index)"
                                    :readonly="!isDraft"
                                />
                            </td>
                            <td class="px-4 py-2 text-right font-semibold" v-if="formType !== 'assetRegistration'">
                                Rp. {{ formatCurrency(item.totalPrice || 0) }}
                            </td>
                            <td class="px-4 py-2" v-if="formType === 'assetRegistration'">
                                <input 
                                    v-model="item.assetTag" 
                                    type="text" 
                                    class="form-input w-32 mx-auto" 
                                    placeholder="AT-001"
                                    :readonly="!isDraft"
                                />
                            </td>
                            <td class="px-4 py-2" v-if="formType === 'assetRegistration'">
                                <input 
                                    v-model="item.location" 
                                    type="text" 
                                    class="form-input w-32 mx-auto" 
                                    placeholder="Room 101"
                                    :readonly="!isDraft"
                                />
                            </td>
                            <td class="px-4 py-2" style='text-align: center !important;' v-if="isDraft">
                                <button @click="removeItem(index)" class="text-red-600 hover:text-red-800" :disabled="!isDraft">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot v-if="formType !== 'assetRegistration'">
                        <tr class="bg-gray-50 dark:bg-gray-800 font-semibold">
                            <td colspan="3" class="px-4 py-2 text-right">Total Amount:</td>
                            <td class="px-4 py-2 text-right text-lg">Rp {{ formatCurrency(totalAmount) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Footer Section: Upload Notes and Attachments -->
        <div class="border-t border-gray-200 pt-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Notes Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes</label>
                    <div class="flex gap-2 mb-2">
                        <textarea 
                            v-model="newNote"
                            class="form-textarea flex-1"
                            rows="2"
                            placeholder="Type a note..."
                            :readonly="!isDraft"
                        ></textarea>
                        <button @click="sendNote" class="btn btn-primary" :disabled="!isDraft || !newNote.trim()">Kirim</button>
                    </div>
                    <textarea 
                        v-model="formData.notes" 
                        class="form-textarea" 
                        rows="4"
                        placeholder="Enter any additional notes or comments..."
                        :readonly="!isDraft"
                        style="display:none"
                    ></textarea>

                    <!-- Balon chat notes -->
                    <div class="mt-4 space-y-3">
                        <div
        v-for="(note, idx) in notesHistory"
        :key="idx"
        class="flex items-start gap-2"
        :class="{
            'justify-end': note.sender === formData.requestedBy,
            'justify-start': note.sender !== formData.requestedBy
        }"
    >
        <div
            :class="[
                'rounded-lg px-4 py-2 max-w-lg',
                note.sender === formData.requestedBy
                    ? 'bg-blue-50 dark:bg-blue-900 text-right ml-auto'
                    : 'bg-gray-100 dark:bg-gray-700 text-left mr-auto'
            ]"
        >
            <div class="flex items-center gap-2 mb-1" :class="note.sender === formData.requestedBy ? 'justify-end' : 'justify-start'">
                <span class="font-semibold"
                    :class="note.sender === formData.requestedBy ? 'text-blue-700 dark:text-blue-200' : 'text-gray-700 dark:text-gray-200'">
                    {{ note.sender }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ note.time }}</span>
            </div>
            <div :class="note.sender === formData.requestedBy ? 'text-blue-900 dark:text-blue-100' : 'text-gray-800 dark:text-gray-200'">
                {{ note.text }}
            </div>
        </div>
    </div>
</div>
                </div>

                <!-- Attachments Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachment Documents</label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4" v-if='isDraft'>
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="file-upload" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900 dark:text-white">Upload files</span>
                                    <input 
                                        id="file-upload" 
                                        name="file-upload" 
                                        type="file" 
                                        class="sr-only" 
                                        multiple
                                        @change="handleFileUpload"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                        :disabled="!isDraft"
                                    />
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PDF, DOC, DOCX, JPG, PNG up to 10MB each</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg text-left text-sm text-gray-500 dark:text-gray-400" id='see-attachment'>
                      Link check upload
                      
                </div>

                    <!-- Uploaded Files List -->
                    <div v-if="uploadedFiles.length > 0" class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Uploaded Files:</h4>
                        <ul class="space-y-2">
                            <li v-for="(file, index) in uploadedFiles" :key="index" class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-800 rounded">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ file.name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">({{ formatFileSize(file.size) }})</span>
                                </div>
                                <button @click="removeFile(index)" class="text-red-600 hover:text-red-800" :disabled="!isDraft">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Notes Section (after submit) -->
        <div v-if="showNotesChat" class="mt-8">
            <h3 class="text-lg font-semibold mb-4">Notes History</h3>
            <div class="space-y-3">
                <div v-for="(note, idx) in notesHistory" :key="idx" class="flex items-start gap-2">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg px-4 py-2">
                        <div class="text-sm text-gray-800 dark:text-gray-200">{{ note.text }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ note.time }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import { useMeta } from '@/composables/use-meta';

// Get form type from route params
const route = useRoute();
const router = useRouter();
const formType = ref(route.params.formType as string || route.query.type as string || 'purchaseRequest');
const formNumber = ref(route.params.formNumber as string || '');

useMeta({ title: computed(() => formTitle.value) });

// Form configuration based on type
const formConfig = computed(() => {
    switch (formType.value) {
        case 'purchaseRequest':
            return {
                title: 'Purchase Request',
                description: 'Create a new purchase request for items needed by your department',
                numberLabel: 'PR Number',
                numberPrefix: 'PR',
                itemsTitle: 'Items Requested'
            };
        case 'purchaseOrder':
            return {
                title: 'Purchase Order',
                description: 'Create a new purchase order to procure items from suppliers',
                numberLabel: 'PO Number',
                numberPrefix: 'PO',
                itemsTitle: 'Items to Purchase'
            };
        case 'assetRegistration':
            return {
                title: 'Asset Registration',
                description: 'Register new assets for tracking and management',
                numberLabel: 'AR Number',
                numberPrefix: 'AR',
                itemsTitle: 'Assets to Register'
            };
        default:
            return {
                title: 'Form Builder',
                description: 'Create a new form',
                numberLabel: 'Form Number',
                numberPrefix: 'F',
                itemsTitle: 'Items'
            };
    }
});

// Computed properties for form configuration
const formTitle = computed(() => formConfig.value.title);
const formDescription = computed(() => formConfig.value.description);
const formNumberLabel = computed(() => formConfig.value.numberLabel);
const formNumberPlaceholder = computed(() => `${formConfig.value.numberPrefix}-${new Date().getFullYear()}-001`);
const itemsSectionTitle = computed(() => formConfig.value.itemsTitle);

// Check if this is edit mode (form number exists in URL)
const isEditMode = computed(() => !!formNumber.value);

// Status draft, default true jika query draft=true, selain itu false
const isDraft = computed(() => formData.value.status === 'draft');

// Jika status berubah, update isDraft
watch(() => route.query.draft, (val) => {
    isDraft.value = val === 'true';
});

// Form data
const formData = ref({
    formNumber: '',
    cabangUser: '',
    requestDate: new Date().toISOString().slice(0, 16),
    requestedBy: '',
    department: '',
    supplier: '', // for PO
    poReference: '', // for AR
    justification: '', // for PR
    notes: '',
    status: 'Waiting', // status dari database, default draft
    personalDetail: {
        fullName: '',
        email: '',
        phone: ''
    },
    items: [
        {
            description: '',
            qty: 1,
            unitPrice: 0,
            totalPrice: 0,
            assetTag: '', // for AR
            location: '' // for AR
        }
    ]
});

// File upload
const uploadedFiles = ref<File[]>([]);
const newNote = ref('');
const notesHistory = ref<{text: string, time: string, sender: string}[]>([]);
const showNotesChat = ref(false);

// Simulasi data PR yang tersedia
const availablePRs = ref([
    {
        formNumber: 'PR-20240601-001',
        requestedBy: 'Alice',
        department: 'IT Department',
        justification: 'Pengadaan laptop baru',
        items: [
            { description: 'Laptop', qty: 2, unitPrice: 9000000, totalPrice: 18000000 },
            { description: 'Mouse', qty: 2, unitPrice: 150000, totalPrice: 300000 }
        ]
    },
    {
        formNumber: 'PR-20240601-002',
        requestedBy: 'Bob',
        department: 'HR Department',
        justification: 'Pengadaan kursi kantor',
        items: [
            { description: 'Chair', qty: 5, unitPrice: 700000, totalPrice: 3500000 }
        ]
    }
]);

const selectedPR = ref('');

// Ketika PR dipilih, isi input dan item dari PR
const onPRSelect = () => {
    const pr = availablePRs.value.find(pr => pr.formNumber === selectedPR.value);
    if (pr) {
        formData.value.requestedBy = pr.requestedBy;
        formData.value.department = pr.department;
        formData.value.justification = pr.justification || '';
        // Untuk PO dan AR, item bisa diubah sesuai kebutuhan
        formData.value.items = pr.items.map(item => ({
            ...item,
            assetTag: '',
            location: ''
        }));
    }
};

// Generate form number on mount
onMounted(() => {
    if (formNumber.value) {
        // Edit mode - load existing form data
        formData.value.formNumber = formNumber.value;
        loadFormData(formNumber.value);
    } else {
        // Create mode - generate new form number
        generateFormNumber();
    }
});

// Watch for route changes
watch(() => route.params, (newParams) => {
    formType.value = newParams.formType as string || 'purchaseRequest';
    formNumber.value = newParams.formNumber as string || '';
    
    if (formNumber.value) {
        formData.value.formNumber = formNumber.value;
        loadFormData(formNumber.value);
    } else {
        generateFormNumber();
    }
});

const generateFormNumber = () => {
    const year = new Date().getFullYear();
    const month = String(new Date().getMonth() + 1).padStart(2, '0');
    const day = String(new Date().getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    const newFormNumber = `${formConfig.value.numberPrefix}-${year}${month}${day}-${random}`;
    
    formData.value.formNumber = newFormNumber;
    
    // Update URL to include the generated form number
    router.replace(`/apps/form-builder/${formType.value}/${newFormNumber}`);
};

const loadFormData = (formNum: string) => {
    // TODO: Load existing form data from API/database
    // Simulasi data dari database, misal status: 'submitted'
    if (isEditMode.value) {
        formData.value = {
            ...formData.value,
            requestedBy: 'John Doe',
            department: 'IT Department',
            justification: 'Existing form justification...',
            notes: 'Existing notes...',
            status: 'draft' // contoh status dari database, ubah sesuai data asli
        };
    }
};

// Item management
const addItem = () => {
    const newItem: any = {
        description: '',
        qty: 1,
        unitPrice: 0,
        totalPrice: 0
    };
    
    if (formType.value === 'assetRegistration') {
        newItem.assetTag = '';
        newItem.location = '';
    }
    
    formData.value.items.push(newItem);
};

const removeItem = (index: number) => {
    if (formData.value.items.length > 1) {
        formData.value.items.splice(index, 1);
    }
};

const calculateTotal = (index: number) => {
    const item = formData.value.items[index];
    item.totalPrice = (item.qty || 0) * (item.unitPrice || 0);
};

// Calculate total amount
const totalAmount = computed(() => {
    return formData.value.items.reduce((sum, item) => sum + (item.totalPrice || 0), 0);
});

// File handling
const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        Array.from(target.files).forEach(file => {
            if (file.size <= 10 * 1024 * 1024) { // 10MB limit
                uploadedFiles.value.push(file);
            } else {
                showMessage(`File ${file.name} is too large. Maximum size is 10MB.`, 'error');
            }
        });
        target.value = ''; // Reset input
    }
};

const removeFile = (index: number) => {
    uploadedFiles.value.splice(index, 1);
};

const sendNote = () => {
    if (!newNote.value.trim()) return;
    // Kirim note dari user
    notesHistory.value.push({
        text: newNote.value,
        time: new Date().toLocaleString(),
        sender: formData.value.requestedBy || 'Me'
    });
    // Balasan dummy otomatis setelah 1 detik
    setTimeout(() => {
        notesHistory.value.push({
            text: 'Terima kasih atas pesan Anda. Ini balasan dummy.',
            time: new Date().toLocaleString(),
            sender: 'Other User'
        });
    }, 1000);
    newNote.value = '';
};

// Utility functions
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
    }).format(amount);
};

const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

// Form actions
const saveDraft = () => {
    // Validate required fields
    if (!formData.value.requestedBy || !formData.value.department) {
        showMessage('Please fill in required fields (Requested By, Department)', 'error');
        return;
    }

    console.log('Saving draft:', formData.value);
    console.log('Form Number:', formData.value.formNumber);
    console.log('Uploaded files:', uploadedFiles.value);
    
    // TODO: Save to API/database
    
    showMessage(`${formTitle.value} (${formData.value.formNumber}) saved as draft successfully!`);
};

const submitForm = () => {
    // Validate required fields
    if (!formData.value.requestedBy || !formData.value.department) {
        showMessage('Please fill in required fields (Requested By, Department)', 'error');
        return;
    }

    // Validate items
    const hasValidItems = formData.value.items.some(item => item.description.trim() !== '');
    if (!hasValidItems) {
        showMessage('Please add at least one item with description', 'error');
        return;
    }

    // Additional validations based on form type
    if (formType.value === 'purchaseOrder' && !formData.value.supplier) {
        showMessage('Please select a supplier for the purchase order', 'error');
        return;
    }

    if (formType.value === 'assetRegistration' && !formData.value.poReference) {
        showMessage('Please select a PO reference for asset registration', 'error');
        return;
    }

    if (formType.value === 'purchaseRequest' && !formData.value.justification.trim()) {
        showMessage('Please provide justification for the purchase request', 'error');
        return;
    }

    console.log('Submitting form:', formData.value);
    console.log('Form Number:', formData.value.formNumber);
    console.log('Uploaded files:', uploadedFiles.value);
    
    // TODO: Submit to API/database
    
    Swal.fire({
        title: 'Success!',
        text: `${formTitle.value} (${formData.value.formNumber}) has been submitted successfully!`,
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        // Redirect to submissions page or dashboard
        // window.location.href = '/apps/contacts';
        showNotesChat.value = true;
    });
};
</script>

<style scoped>
.btn {
    @apply px-4 py-2 rounded-md font-medium transition duration-200;
}

.disabled{
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

.form-input, .form-select, .form-textarea {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white;
}

.table-auto {
    @apply min-w-full border-collapse;
}

.table-auto th,
.table-auto td {
    @apply border border-gray-200 dark:border-gray-600;
}
</style>