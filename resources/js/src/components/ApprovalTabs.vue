<template>
  <div class="panel mb-6">
    <ul class="flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]">
      <li v-for="tab in tabs" :key="tab.value">
        <a href="javascript:"
           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 before:absolute hover:text-primary before:bottom-0 before:w-0 before:left-0 before:h-[1px] before:bg-primary hover:before:w-full focus:outline-none"
           :class="{'before:w-full text-primary' : activeTab === tab.value}"
           @click="setTab(tab.value)"
        >
          <svg v-if="tab.value === 'waiting approval'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else-if="tab.value === 'revised'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 12h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else-if="tab.value === 'rejected'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M8 8l8 8M16 8l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M8 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          {{ tab.label }}
        </a>
      </li>
    </ul>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th v-for="col in columns" :key="col.key">{{ col.title }}</th>
          </tr>
        </thead>
        <tbody v-if='isPending'>
          <tr>
            <td :colspan="columns.length" class="py-4" style='text-align: center;'>Loading...</td>
          </tr>
        </tbody>
        <tbody v-if='!isPending'>
          <!-- grouping -->
          
          <tr v-if="filteredItems.length === 0 ">
            <td :colspan="columns.length" class="py-4" style="text-align: center;">No data found</td>
          </tr>
          <tr
            v-for="item in filteredItems"
            :key="item.id"
            @click="onRowClick(item)"
            style="cursor:pointer"
          >
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
}>();
const emit = defineEmits(['tab-change', 'row-click']);
const activeTab = ref(props.initialTab);
const isLoading = ref(false);

watch(() => props.isPending, (val) => {
  isLoading.value = val ?? false;
});

// Tidak perlu mapping label ke status DB, langsung pakai value
const setTab = (tabValue: string) => {
  activeTab.value = tabValue;
  emit('tab-change', tabValue);
};

const filteredItems = computed(() => {
  console.log('Active Tab:', activeTab.value);
  const dbStatus = (activeTab.value || '').toLowerCase();
  console.log('DB Status for filtering:', dbStatus);
  
  // baru: ambil dari objek
  console.log('Items for filtering:', props.items);
  return props.items?.[dbStatus] || [];
});

watch(() => props.initialTab, (val) => {
  activeTab.value = val;
});

const onRowClick = (item: any) => {
  emit('row-click', item);
};
</script>
