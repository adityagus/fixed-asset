<template>
  <div>
    <div v-for="item in items" :key="item.id" class="border border-[#d3d3d3] dark:border-[#1b2e4b] rounded mb-2">
      <button type="button"
        class="p-4 w-full flex items-center text-white-dark dark:bg-[#1b2e4b]"
        :class="{'!text-primary': active.includes(item.id)}"
        @click="toggleAccordion(item.id)"
      >
        {{ item.title }}
        <div class="ltr:ml-auto rtl:mr-auto" :class="{'rotate-180': active.includes(item.id)}">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
      </button>
      <div v-show="active.includes(item.id)" class="space-y-2 p-4 text-white-dark text-[13px] border-t border-[#d3d3d3] dark:border-[#1b2e4b]">
        <component :is="item.component" :showTableOnly="true" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue';
const props = defineProps<{ items: Array<{ id: number, title: string, component: any }> }>();
const active = ref<number[]>([]);

function toggleAccordion(id: number) {
  if (active.value.includes(id)) {
    active.value = active.value.filter(aid => aid !== id);
  } else {
    active.value.push(id);
  }
}
</script>
