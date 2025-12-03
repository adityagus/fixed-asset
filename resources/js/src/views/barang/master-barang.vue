<template>
  <div class="mb-5">
    <div class="space-y-2 font-semibold bg-white p-4 rounded">
      <AccordionGroup :items="accordionItems">
        <!-- Master Barang -->
        <template #item-1>
          <div v-if="isLoadingBarang">Loading...</div>
          <div v-else-if="errorBarang">Error: {{ errorBarang.message }}</div>
          <div v-else>
            <p>Total: {{ masterBarangList.length }}</p>
            <ul>
              <li v-for="item in masterBarangList" :key="item.id">
                {{ item.nama_item || item.nama_barang }}
              </li>
            </ul>
          </div>
        </template>
        <!-- Master Kategori -->
        <template #item-2>
          <div v-if="isLoadingKategori">Loading...</div>
          <div v-else-if="errorKategori">Error: {{ errorKategori.message }}</div>
          <div v-else>
            <p>Total: {{ kategoriListData.length }}</p>
            <ul>
              <li v-for="item in kategoriListData" :key="item.id_kategori">
                {{ item.kategori || item.nama_kategori }}
              </li>
            </ul>
          </div>
        </template>
        <!-- Master Tipe -->
        <template #item-3>
          <div v-if="isLoadingTipe">Loading...</div>
          <div v-else-if="errorTipe">Error: {{ errorTipe.message }}</div>
          <div v-else>
            <p>Total: {{ tipeListData.length }}</p>
            <ul>
              <li v-for="item in tipeListData" :key="item.id">
                {{ item.nama_type || item.nama_tipe }}
              </li>
            </ul>
          </div>
        </template>
        <!-- Master Merek -->
        <template #item-4>
          <div v-if="isLoadingMerek">Loading...</div>
          <div v-else-if="errorMerek">Error: {{ errorMerek.message }}</div>
          <div v-else>
            <p>Total: {{ merekListData.length }}</p>
            <ul>
              <li v-for="(item, index) in merekListData" :key="index">
                {{ item.merek || item.nama_merk }}
              </li>
            </ul>
          </div>
        </template>
        <!-- Master Vendor -->
        <template #item-5>
          <div v-if="isLoadingVendor">Loading...</div>
          <div v-else-if="errorVendor">Error: {{ errorVendor.message }}</div>
          <div v-else>
            <p>Total: {{ vendorListData.length }}</p>
            <ul>
              <li v-for="item in vendorListData" :key="item.id">
                {{ item.nama_vendor }}
              </li>
            </ul>
          </div>
        </template>
      </AccordionGroup>
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
import { computed, watch } from 'vue';
import { useGetMasterBrg, useGetKategoriList, useGetTipeBarangList, useGetMerkList, useGetVendorList } from '@/services/queries';

const accordionItems = [
  { id: 1, title: 'Master Barang', component: CrudMasterBarang },
  { id: 2, title: 'Master Kategori Barang', component: CrudMasterKategori },
  { id: 3, title: 'Master Tipe Barang', component: CrudMasterTipe },
  { id: 4, title: 'Master Merek Barang', component: CrudMasterMerek },
  { id: 5, title: 'Master Vendor', component: CrudMasterVendor },
];

const { data: masterBarang, isLoading: isLoadingBarang, error: errorBarang } = useGetMasterBrg();
const { data: kategoriList, isLoading: isLoadingKategori, error: errorKategori } = useGetKategoriList();
const { data: tipeList, isLoading: isLoadingTipe, error: errorTipe } = useGetTipeBarangList();
const { data: merekList, isLoading: isLoadingMerek, error: errorMerek } = useGetMerkList();
const { data: vendorList, isLoading: isLoadingVendor, error: errorVendor } = useGetVendorList();

// Debug watch
watch(masterBarang, (newVal) => {
  console.log('masterBarang:', newVal);
});

watch(kategoriList, (newVal) => {
  console.log('kategoriList:', newVal);
});

watch(tipeList, (newVal) => {
  console.log('tipeList:', newVal);
});

watch(merekList, (newVal) => {
  console.log('merekList:', newVal);
});

watch(vendorList, (newVal) => {
  console.log('vendorList:', newVal);
});

const masterBarangList = computed(() => {
  if (!masterBarang.value) return [];
  // Cek jika ada property 'data'
  if (masterBarang.value.data) return masterBarang.value.data;
  // Jika langsung array
  if (Array.isArray(masterBarang.value)) return masterBarang.value;
  return [];
});

const kategoriListData = computed(() => {
  if (!kategoriList.value) return [];
  if (kategoriList.value.data) return kategoriList.value.data;
  if (Array.isArray(kategoriList.value)) return kategoriList.value;
  return [];
});

const tipeListData = computed(() => {
  if (!tipeList.value) return [];
  if (tipeList.value.data) return tipeList.value.data;
  if (Array.isArray(tipeList.value)) return tipeList.value;
  return [];
});

const merekListData = computed(() => {
  if (!merekList.value) return [];
  if (merekList.value.data) return merekList.value.data;
  if (Array.isArray(merekList.value)) return merekList.value;
  return [];
});

console.log('meerekListData', merekListData);

const vendorListData = computed(() => {
  if (!vendorList.value) return [];
  if (vendorList.value.data) return vendorList.value.data;
  if (Array.isArray(vendorList.value)) return vendorList.value;
  return [];
});
</script>
