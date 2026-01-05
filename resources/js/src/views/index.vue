<template>
    
    <template v-if="store?.user">
        <div class="mb-4">
            <span class="text-gray-700 dark:text-gray-200">Hi,</span>
            <span class="font-bold text-primary">{{ store.user.nama || 'User' }}👋</span>!
            <span class="text-gray-700 dark:text-gray-200">Welcome back.</span>
        </div>
    </template>
                
    <!-- Fixed Asset Statistics Panel -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6 ">
                <div class="panel h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-100 to-blue-300 dark:from-gray-800 dark:to-gray-900 shadow-lg rounded-xl">
                    <div class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-2">
                        <count-up :start-val='0':end-val="assetStats.active_assets + assetStats.inactive_assets + assetStats.depreciated_assets" :duration="2" />
                    </div>
                    <div class="text-lg text-gray-700 dark:text-gray-300">Total Aset</div>
                </div>
                <div class="panel h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-100 to-green-300 dark:from-green-900 dark:to-green-700 shadow-lg rounded-xl">
                    <div class="text-2xl font-bold text-green-700 dark:text-green-300 mb-2">
                        <count-up :start-val='0':end-val="assetStats.active_assets + assetStats.inactive_assets" :duration="2" />
                    </div>
                    <div class="text-lg text-gray-700 dark:text-gray-300">Aset Disusutkan</div>
                </div>
                <div class="panel h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-yellow-100 to-yellow-300 dark:from-yellow-900 dark:to-yellow-700 shadow-lg rounded-xl">
                    <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mb-2">
                        <count-up :start-val='0':end-val="assetStats.depreciated_assets" :duration="2" />
                    </div>
                    <div class="text-lg text-gray-700 dark:text-gray-300">Aset Habis Disusutkan</div>
                </div>
                <div class="panel h-full flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-100 to-purple-300 dark:from-purple-900 dark:to-purple-700 shadow-lg rounded-xl">
                    <div class="flex text-2xl font-bold text-purple-700 dark:text-purple-300 mb-2 gap-1">
                        Rp <count-up :start-val='0':end-val="assetStats.total_value" :duration="2" />
                    </div>
                    <div class="text-lg text-gray-700 dark:text-gray-300">Nilai Buku Aset</div>
                    <div class="text-gray-600 dark:text-gray-300 text-sm font-medium">
                        <span class='flex gap-1'>
                            Penyusutan: Rp <count-up :start-val='0':end-val="assetStats.depreciation_value" :duration="2" />
                        </span>
                    </div>
                </div>
            </div>

                            <!-- Fully Depreciated Assets Dashboard -->
                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 rounded-2xl shadow-xl p-4 mb-10">
                                <!-- <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-full flex items-center justify-center">
                                            <svg width="40" height="40" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21V7a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14"/><rect x="7" y="13" width="10" height="8" rx="2"/><path d="M17 21V7a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v14"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-3xl font-bold text-blue-700 dark:text-blue-300">125</div>
                                            <div class="text-gray-600 dark:text-gray-300 text-sm font-medium">Total Aset Disusutkan</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="bg-yellow-100 dark:bg-yellow-900 p-4 rounded-full flex items-center justify-center">
                                            <svg width="40" height="40" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">Rp 3.200.000.000</div>
                                            <div class="text-gray-600 dark:text-gray-300 text-sm font-medium">Nilai Buku: Rp 0</div>
                                            <div class="text-xs text-gray-400">(Zero Book Value)</div>
                                        </div>
                                    </div>
                                </div> -->
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
                                        <h6 class="font-semibold mb-2 text-gray-700 dark:text-gray-200">Jenis Aset Disusutkan</h6>
                                        <apexchart v-if="assetBodyStats !== undefined" type="pie" width="180" :options="pieTypeOptions" :series="pieTypeSeries" />
                                        <div v-else class="flex items-center justify-center h-[180px] w-full text-gray-400">Loading...</div>
                                        <div class="flex justify-center gap-4 mt-2 text-xs ">
                                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span>Kendaraan</span>
                                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-yellow-500"></span>Mesin Kantor</span>
                                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-green-500"></span>Peralatan Kayu</span>
                                            <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span>Lainnya</span>
                                            
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 flex flex-col items-center">
                                        <h6 class="font-semibold mb-2 text-gray-700 dark:text-gray-200">Umur Aset</h6>
                                        <apexchart v-if="assetBodyStats !== undefined" type="bar" :options="barAgeOptions" :series="barAgeSeries" />
                                        <div v-else class="flex items-center justify-center h-[160px] w-full text-gray-400">Loading...</div>
                                    </div>
                                    <div class="flex flex-col gap-3 justify-between">
                                        <div class="flex items-center gap-3 bg-red-50 dark:bg-red-900 rounded-lg p-3">
                                            <svg width="28" height="28" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/>
                                                <line x1="9" y1="9" x2="15" y2="15" stroke="#dc2626" stroke-width="2" stroke-linecap="round"/>
                                                <line x1="15" y1="9" x2="9" y2="15" stroke="#dc2626" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                            <div>
                                                <div class="flex gap-1 text-lg font-bold text-red-700 dark:text-red-300">
                                                    <count-up :start-val='0' :end-val="assetBodyStats?.asset_status?.non_operational?.percent || 0" :decimal-places="Number.isInteger(assetBodyStats?.asset_status?.non_operational?.percent) ? 0 : 1" :duration="2"
                                                    :key="assetBodyStats?.asset_status?.non_operational?.percent"
                                                    />%</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-300">tidak operasional</div>
                                                <div class="text-xs text-red-400">Aset Tidak Digunakan</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 bg-green-50 dark:bg-green-900 rounded-lg p-3">
                                            <svg width="28" height="28" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2l4-4"/></svg>
                                            <div>
                                                <div class="flex gap-1 text-lg font-bold text-green-700 dark:text-green-300">
                                                    <count-up
                                                        :start-val="0"
                                                        :end-val="assetBodyStats?.asset_status?.still_operational?.percent || 0"
                                                        :decimal-places="Number.isInteger(assetBodyStats?.asset_status?.still_operational?.percent) ? 0 : 1"
                                                        :duration="2"
                                                        :key="assetBodyStats?.asset_status?.still_operational?.percent"
                                                    />%
                                                </div>
                                                <div class="text-xs text-gray-600 dark:text-gray-300">masih operasional</div>
                                                <div class="text-xs text-green-400">Aset Masih Digunakan</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-900 rounded-lg p-3">
                                            <svg width="28" height="28" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/></svg>
                                            <div>
                                                <div class="text-lg font-bold text-blue-700 dark:text-blue-300">{{ assetBodyStats?.asset_status?.change_needed?.count }}</div>
                                                <div class="text-xs text-gray-600 dark:text-gray-300">Aset Perlu Diganti</div>
                                                <div class="text-xs text-blue-400">Rencana Penggantian</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

</template>
<script lang="ts" setup>
    import { ref, computed } from 'vue';
    import apexchart from 'vue3-apexcharts';
    import { useAppStore } from '@/stores/index';
    import { useGetBodyStatistik, useGetHeaderStatistik } from '@/services/queries';
    import CountUp from 'vue-countup-v3'
    const store = useAppStore();

    // Statistik Fixed Asset (dummy, ganti dengan API/store jika ada)
    const {data: assetStats, isPending, isSuccess} = useGetHeaderStatistik();
    const {data: assetBodyStats } = useGetBodyStatistik();    
    
    // Pie chart: Jenis Aset Disusutkan
    const pieTypeOptions = {
        chart: { type: 'pie' },
        labels: [
            'Kendaraan',
            'Mesin Kantor',
            'Peralatan Kayu',
            'Lainnya'
        ],
        colors: ['#3b82f6', '#f59e42', '#22c55e', '#ef4444'],
        // colors: ['#3b82f6', '#f59e42', '#22c55e', '#ef4444', '#8b5cf6', '#06b6d4', '#f43f5e', '#10b981'],
        legend: { show: false },
        dataLabels: { enabled: true },
    };
    const pieTypeSeries = computed(() => {
        if (!assetBodyStats?.value?.asset_types) {
            // fallback jika data belum siap
            return [0, 0, 0, 0];
        }
        return [
            assetBodyStats.value.asset_types.kendaraan || 0,
            assetBodyStats.value.asset_types.mesin_kantor || 0,
            assetBodyStats.value.asset_types.peralatan_kayu || 0,
            assetBodyStats.value.asset_types.lainnya || 0,
        ];
    });

    // Bar chart: Umur Aset
    const barAgeOptions = {
        chart: { type: 'bar', toolbar: { show: false }, fontFamily: 'Nunito, sans-serif' },
        xaxis: { categories: ['0-5 Tahun', '6-10 Tahun', '> 10 Tahun'], labels: { style: { fontSize: '12px' } } },
        yaxis: { labels: { style: { fontSize: '12px' } } },
        plotOptions: { bar: { borderRadius: 6, columnWidth: '40%' } },
        colors: ['#2563eb'],
        grid: { borderColor: '#e0e6ed', strokeDashArray: 5 },
        dataLabels: { enabled: true },
    };
    const barAgeSeries = computed(() => {
        if (!assetBodyStats?.value?.asset_age) {
            // fallback jika data belum siap
            return [{ name: 'Aset', data: [0, 0, 0] }];
        }
        return [{
            name: 'Aset',
            data: [
                assetBodyStats.value.asset_age.age_0_5 || 0,
                assetBodyStats.value.asset_age.age_6_10 || 0,
                assetBodyStats.value.asset_age.age_above_10 || 0
            ]
        }];
    });
    
    // const barAgeSeries = computed(() => [{ name: 'Aset', data: [45, 60, 20] }]);
    
    
        // const assetStats = ref({
        //     total: headerStatistik.total_value || 0,
        //     active: headerStatistik.value?.activeAssets || 0,
        //     depreciated: headerStatistik.value?.depreciatedAssets || 0,
        //     depreciationValue: headerStatistik.value?.depreciationValue || 0,
        // });

    // Helper currency (IDR)
    function currencyFilter(value: number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
    }
</script>
