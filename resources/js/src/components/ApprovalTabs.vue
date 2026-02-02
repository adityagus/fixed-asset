<template>
    <div class="panel mb-6">
        <ul class="flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]">
            <li v-for="tab in tabs" :key="tab.value">
                <a href="javascript:"
                    class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 before:absolute hover:text-primary before:bottom-0 before:w-0 before:left-0 before:h-[1px] before:bg-primary hover:before:w-full focus:outline-none"
                    :class="{ 'before:w-full text-primary': activeTab === tab.value }" @click="setTab(tab.value)">
                    <svg v-if="tab.value === 'Waiting Approval'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="mr-2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <svg v-else-if="tab.value === 'Revised'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="mr-2">
                        <rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5" />
                        <path d="M8 12h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <svg v-else-if="tab.value === 'Rejected'" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="mr-2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M8 8l8 8M16 8l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M8 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    {{ tab.indo || tab.label }}
                </a>
            </li>
        </ul>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th v-for="col in columns" :key="col.key" class='text-left'>{{ col.title }}</th>
                    </tr>
                </thead>
                <tbody v-if='isPending'>
                    <tr>
                        <td :colspan="columns.length" class="py-4" style='text-align: center;'>Memuat data...</td>
                    </tr>
                </tbody>
                <tbody v-if='!isPending'>
                    <!-- grouping -->

                    <tr v-if="filteredItems.length === 0">
                        <td :colspan="columns.length" class="py-4" style="text-align: center;">Tidak ada data ditemukan</td>
                    </tr>
                    <tr v-for="item in filteredItems" :key="item.id" @click="onRowClick(item)" style="cursor:pointer">
                        <td v-for="col in columns" :key="(col as Column).key">
                            <slot :name="(col as Column).key" :item="item as any">
                                {{ item[(col as Column).key] }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script lang="ts" setup>
import { useGetCabangList } from '@/services/queries';
import { ref, computed, watch, defineProps, defineEmits } from 'vue';
/**
 * @typedef {Object} ApprovalItem
 * @property {number} id
 * @property {string} number
 * @property {string} title
 * @property {string} requester
 * @property {string} date
 * @property {string} status
 */
interface TabItem {
    label: string;
    value: string;
}
interface Column {
    key: string;
    title: string;
}

const props = defineProps<{
    items: Record<string, any[]>; // Ubah dari any[] ke Record<string, any[]>
    columns: Column[];
    tabs?: TabItem[];
    initialTab?: string;
    isSuccess?: boolean;
    isPending?: boolean;
    type?: string;
}>();
const emit = defineEmits(['tab-change', 'row-click']);
const activeTab = ref(props.initialTab);
const isLoading = ref(false);
const { data: cabangList } = useGetCabangList();


watch(() => props.isPending, (val) => {
    isLoading.value = val ?? false;
});

// Tidak perlu mapping label ke status DB, langsung pakai value
const setTab = (tabValue: string) => {
    activeTab.value = tabValue;
    emit('tab-change', tabValue);
};

const filteredItems = computed(() => {
    const dbStatus = (activeTab.value || '').toLowerCase();
    const items = Array.isArray(props.items?.[dbStatus]) ? props.items[dbStatus] : [];
    if(props.type === 'purchase-request') {
        // Map cabang_id to cabang name
        items.forEach((item: any) => {
            const cabang = item.cabang_id === 9999
                ? { nm_area: 'HO' }
                : cabangList.value.find((cabang: any) => cabang.id === item.cabang_id);
            item.nameLocation = cabang ? cabang.nm_area : 'Unknown';
        });
    }else if(props.type === 'purchase-order') {
        // Map cabang_id to cabang name
        items.forEach((item: any) => {
            const cabang = item.cabang_id === 9999
                ? { nm_area: 'HO' }
                : cabangList.value.find((cabang: any) => cabang.id === item.purchase_request?.cabang_id);
            item.nameLocation = cabang ? cabang.nm_area : 'Unknown';
        });
    }else if(props.type === 'registration-asset') {
        // Map cabang_id to cabang name
        items.forEach((item: any) => {
            const cabang = item.cabang_id === 9999
                ? { nm_area: 'HO' }
                : cabangList.value.find((cabang: any) => cabang.id === item.purchase_order?.purchase_request?.cabang_id);
            item.nameLocation = cabang ? cabang.nm_area : 'Unknown';
        });
    }
    console.log('isi dari items yang sudah di filter:', items);
    return items;

});

watch(() => props.initialTab, (val) => {
    activeTab.value = val;
});

const onRowClick = (item: any) => {
    emit('row-click', item);
};
</script>
