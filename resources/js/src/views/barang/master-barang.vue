<template>
  <div class="mb-5">
    <div class="space-y-2 font-semibold bg-white p-4 rounded">
      <AccordionGroup :items="accordionItems" />
    </div>
  </div>
</template>

<script lang="ts" setup>

import AccordionGroup from '@/components/AccordionGroup.vue';
import CrudMasterBarang from '@/components/CrudMasterBarang.vue';
import CrudMasterKategori from '@/components/CrudMasterKategori.vue';
import CrudMasterTipe from '@/components/CrudMasterTipe.vue';
import CrudMasterMerek from '@/components/CrudMasterMerek.vue';
import CrudMasterVendor from '@/components/CrudMasterVendor.vue';

const accordionItems = [
  { id: 1, title: 'Master Barang', component: CrudMasterBarang },
  { id: 2, title: 'Master Kategori Barang', component: CrudMasterKategori },
  { id: 3, title: 'Master Tipe Barang', component: CrudMasterTipe },
  { id: 4, title: 'Master Merek Barang', component: CrudMasterMerek },
  { id: 5, title: 'Master Vendor', component: CrudMasterVendor },
];
</script>
