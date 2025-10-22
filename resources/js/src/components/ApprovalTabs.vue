<template>
  <div class="panel mb-6">
    <ul class="flex flex-wrap mt-3 mb-5 border-b border-white-light dark:border-[#191e3a]">
      <li v-for="tab in tabs" :key="tab">
        <a href="javascript:"
           class="p-5 py-3 -mb-[1px] flex items-center relative before:transition-all before:duration-700 before:absolute hover:text-primary before:bottom-0 before:w-0 before:left-0 before:h-[1px] before:bg-primary hover:before:w-full focus:outline-none"
           :class="{'before:w-full text-primary' : activeTab === tab}"
           @click="setTab(tab)"
        >
          <svg v-if="tab === 'Waiting List'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else-if="tab === 'Revised'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><rect x="4" y="4" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M8 12h8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else-if="tab === 'Rejected'" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M8 8l8 8M16 8l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          <svg v-else width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mr-2"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M8 12l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          {{ tab }}
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
        <tbody>
          <tr v-if="filteredItems.length === 0">
            <td :colspan="columns.length" class="text-center py-4">No data found</td>
          </tr>
          <tr v-for="item in filteredItems" :key="item.id">
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
interface Column {
  key: string;
  title: string;
}

const props = defineProps<{
  items: any[];
  columns: Column[];
  tabs?: string[];
  initialTab?: string;
}>();
const emit = defineEmits(['tab-change']);
const activeTab = ref(props.initialTab);
const setTab = (tab: string) => {
  activeTab.value = tab;
  emit('tab-change', tab);
};
const filteredItems = computed(() => props.items.filter((item: any) => item.status === activeTab.value));
watch(() => props.initialTab, (val) => {
  activeTab.value = val;
});
</script>
