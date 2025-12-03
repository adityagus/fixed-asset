<template>
    <div :class="{ 'dark text-white-dark': store.semidark }">
        <nav
            class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
            <div class="bg-white dark:bg-[#0e1726] h-full">
                <div class="flex justify-between items-center px-4 py-3">
                    <router-link to="/" class="main-logo flex items-center shrink-0">
                        <img class="w-40 ml-[5px] flex-none" src="/assets/images/logo-vertical.png" alt="" />
                    </router-link>
                    <a href="javascript:;"
                        class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180 hover:text-primary"
                        @click="store.toggleSidebar()">
                        <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
                <perfect-scrollbar :options="{
                    swipeEasing: true,
                    wheelPropagation: false,
                }" class="h-[calc(100vh-80px)] relative">
                    <div class="cursor-pointer flex items-center gap-2 border-solid border-gray-200 p-2 px-5 rounded bg-gray-100 dark:hover:bg-dark-light/10"
                        @click="openCreateModal">
                        <svg width="40" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="25" cy="25" r="25" fill="#FBBD0A" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M25 15C25.3789 15 25.7422 15.1505 26.0102 15.4184C26.2781 15.6863 26.4286 16.0497 26.4286 16.4286V23.5714H33.5714C33.9503 23.5714 34.3137 23.7219 34.5816 23.9898C34.8495 24.2578 35 24.6211 35 25C35 25.3789 34.8495 25.7422 34.5816 26.0102C34.3137 26.2781 33.9503 26.4286 33.5714 26.4286H26.4286V33.5714C26.4286 33.9503 26.2781 34.3137 26.0102 34.5816C25.7422 34.8495 25.3789 35 25 35C24.6211 35 24.2578 34.8495 23.9898 34.5816C23.7219 34.3137 23.5714 33.9503 23.5714 33.5714V26.4286H16.4286C16.0497 26.4286 15.6863 26.2781 15.4184 26.0102C15.1505 25.7422 15 25.3789 15 25C15 24.6211 15.1505 24.2578 15.4184 23.9898C15.6863 23.7219 16.0497 23.5714 16.4286 23.5714H23.5714V16.4286C23.5714 16.0497 23.7219 15.6863 23.9898 15.4184C24.2578 15.1505 24.6211 15 25 15Z"
                                fill="white" />
                        </svg>
                        <strong class="text-black dark:text-white-light hover:text-primary">Pengajuan Baru</strong>
                    </div>
                    <ul class="relative font-semibold space-y-0.5 p-4 py-0">
                        <li class='mb-4'>
                        </li>


                        <li class="nav-item">
                            <router-link to="/submission" class="group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary shrink-0 size-6" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('List Pengajuan')
                                        }}</span>
                                </div>
                            </router-link>
                        </li>

                        <li class="menu nav-item">
                            <button type="button" class="nav-link group w-full" :class="{ active: activeDropdown === 'approval' }"
                                @click="activeDropdown === 'approval' ? (activeDropdown = null) : (activeDropdown = 'approval')">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="group-hover:!text-primary shrink-0 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('Persetujuan')
                                        }}</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'approval' || isPRApprovalActive || isPOApprovalActive || isRAApprovalActive }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <transition>
                                <div v-show="activeDropdown === 'approval' || isPRApprovalActive || isPOApprovalActive || isRAApprovalActive" class="overflow-hidden">
                                    <ul class="sub-menu text-gray-500">
                                        <li>
                                            <router-link to="/approval/pr-approval" @click="toggleMobileMenu"
                                                :class="{ 'active': isPRApprovalActive }">
                                                Persetujuan PR
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link to="/approval/po-approval" @click="toggleMobileMenu"
                                                :class="{ 'active': isPOApprovalActive }">
                                                Persetujuan PO
                                            </router-link>
                                        </li>
                                        <li>
                                            <router-link to="/approval/ra-approval" @click="toggleMobileMenu"
                                                :class="{ 'active': isRAApprovalActive }">
                                                Persetujuan RA
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </transition>
                        </li>

                        <li class="menu nav-item">
                            <button type="button" class="nav-link group w-full" :class="{ active: activeDropdown === 'report' }"
                                @click="activeDropdown === 'report' ? (activeDropdown = null) : (activeDropdown = 'report')">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M6.22209 4.60105C6.66665 4.304 7.13344 4.04636 7.6171 3.82976C8.98898 3.21539 9.67491 2.9082 10.5875 3.4994C11.5 4.09061 11.5 5.06041 11.5 7.00001V8.50001C11.5 10.3856 11.5 11.3284 12.0858 11.9142C12.6716 12.5 13.6144 12.5 15.5 12.5H17C18.9396 12.5 19.9094 12.5 20.5006 13.4125C21.0918 14.3251 20.7846 15.011 20.1702 16.3829C19.9536 16.8666 19.696 17.3334 19.399 17.7779C18.3551 19.3402 16.8714 20.5578 15.1355 21.2769C13.3996 21.9959 11.4895 22.184 9.64665 21.8175C7.80383 21.4509 6.11109 20.5461 4.78249 19.2175C3.45389 17.8889 2.5491 16.1962 2.18254 14.3534C1.81598 12.5105 2.00412 10.6004 2.72315 8.86451C3.44218 7.12861 4.65982 5.64492 6.22209 4.60105Z"
                                            fill="currentColor" />
                                        <path
                                            d="M21.446 7.06901C20.6342 5.00831 18.9917 3.36579 16.931 2.55398C15.3895 1.94669 14 3.34316 14 5.00002V9.00002C14 9.5523 14.4477 10 15 10H19C20.6569 10 22.0533 8.61055 21.446 7.06901Z"
                                            fill="currentColor" />
                                    </svg>


                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('Report')
                                        }}</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'report' }">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>

                            <transition>
                                <div v-show="activeDropdown === 'report'" class="overflow-hidden">
                                    <ul class="sub-menu text-gray-500">
                                        <li>
                                            <router-link to="/report/assets" @click="toggleMobileMenu">{{ $t('Asset') }}</router-link>
                                        </li>
                                        <li>
                                            <router-link to="/report/barcodes" @click="toggleMobileMenu">{{ $t('Barcode')
                                                }}</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </transition>
                        </li>

                        <li class="menu nav-item">
                            <router-link to="/assets" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="group-hover:!text-primary shrink-0 size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Asset</span>
                                </div>
                            </router-link>
                        </li>

                        <!-- <li class="menu nav-item">
                            <router-link to="/migrasi" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M6.22209 4.60105C6.66665 4.304 7.13344 4.04636 7.6171 3.82976C8.98898 3.21539 9.67491 2.9082 10.5875 3.4994C11.5 4.09061 11.5 5.06041 11.5 7.00001V8.50001C11.5 10.3856 11.5 11.3284 12.0858 11.9142C12.6716 12.5 13.6144 12.5 15.5 12.5H17C18.9396 12.5 19.9094 12.5 20.5006 13.4125C21.0918 14.3251 20.7846 15.011 20.1702 16.3829C19.9536 16.8666 19.696 17.3334 19.399 17.7779C18.3551 19.3402 16.8714 20.5578 15.1355 21.2769C13.3996 21.9959 11.4895 22.184 9.64665 21.8175C7.80383 21.4509 6.11109 20.5461 4.78249 19.2175C3.45389 17.8889 2.5491 16.1962 2.18254 14.3534C1.81598 12.5105 2.00412 10.6004 2.72315 8.86451C3.44218 7.12861 4.65982 5.64492 6.22209 4.60105Z"
                                            fill="currentColor" />
                                        <path
                                            d="M21.446 7.06901C20.6342 5.00831 18.9917 3.36579 16.931 2.55398C15.3895 1.94669 14 3.34316 14 5.00002V9.00002C14 9.5523 14.4477 10 15 10H19C20.6569 10 22.0533 8.61055 21.446 7.06901Z"
                                            fill="currentColor" />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('Migrasi')
                                        }}</span>
                                </div>
                            </router-link>
                        </li> -->

                        <h2
                            class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">
                            <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>{{ $t('MASTER') }}</span>
                        </h2>

                        <li class="menu nav-item">
                            <router-link to="/master/barang" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M13 15.4C13 13.3258 13 12.2887 13.659 11.6444C14.318 11 15.3787 11 17.5 11C19.6213 11 20.682 11 21.341 11.6444C22 12.2887 22 13.3258 22 15.4V17.6C22 19.6742 22 20.7113 21.341 21.3556C20.682 22 19.6213 22 17.5 22C15.3787 22 14.318 22 13.659 21.3556C13 20.7113 13 19.6742 13 17.6V15.4Z"
                                            fill="currentColor" />
                                        <path
                                            d="M2 8.6C2 10.6742 2 11.7113 2.65901 12.3556C3.31802 13 4.37868 13 6.5 13C8.62132 13 9.68198 13 10.341 12.3556C11 11.7113 11 10.6742 11 8.6V6.4C11 4.32582 11 3.28873 10.341 2.64437C9.68198 2 8.62132 2 6.5 2C4.37868 2 3.31802 2 2.65901 2.64437C2 3.28873 2 4.32582 2 6.4V8.6Z"
                                            fill="currentColor" />
                                        <path
                                            d="M13 5.5C13 4.4128 13 3.8692 13.1713 3.44041C13.3996 2.86867 13.8376 2.41443 14.389 2.17761C14.8024 2 15.3266 2 16.375 2H18.625C19.6734 2 20.1976 2 20.611 2.17761C21.1624 2.41443 21.6004 2.86867 21.8287 3.44041C22 3.8692 22 4.4128 22 5.5C22 6.5872 22 7.1308 21.8287 7.55959C21.6004 8.13133 21.1624 8.58557 20.611 8.82239C20.1976 9 19.6734 9 18.625 9H16.375C15.3266 9 14.8024 9 14.389 8.82239C13.8376 8.58557 13.3996 8.13133 13.1713 7.55959C13 7.1308 13 6.5872 13 5.5Z"
                                            fill="currentColor" />
                                        <path opacity="0.5"
                                            d="M2 18.5C2 19.5872 2 20.1308 2.17127 20.5596C2.39963 21.1313 2.83765 21.5856 3.38896 21.8224C3.80245 22 4.32663 22 5.375 22H7.625C8.67337 22 9.19755 22 9.61104 21.8224C10.1624 21.5856 10.6004 21.1313 10.8287 20.5596C11 20.1308 11 19.5872 11 18.5C11 17.4128 11 16.8692 10.8287 16.4404C10.6004 15.8687 10.1624 15.4144 9.61104 15.1776C9.19755 15 8.67337 15 7.625 15H5.375C4.32663 15 3.80245 15 3.38896 15.1776C2.83765 15.4144 2.39963 15.8687 2.17127 16.4404C2 16.8692 2 17.4128 2 18.5Z"
                                            fill="currentColor" />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('Barang')
                                        }}</span>
                                </div>
                            </router-link>
                        </li>


                        <li class="menu nav-item">
                            <router-link to="/users" class="nav-link group" @click="toggleMobileMenu">
                                <div class="flex items-center">
                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 6.75C9.1005 6.75 6.75 9.1005 6.75 12C6.75 14.8995 9.1005 17.25 12 17.25C12.4142 17.25 12.75 17.5858 12.75 18C12.75 18.4142 12.4142 18.75 12 18.75C8.27208 18.75 5.25 15.7279 5.25 12C5.25 8.27208 8.27208 5.25 12 5.25C15.7279 5.25 18.75 8.27208 18.75 12C18.75 12.8103 18.6069 13.5889 18.3439 14.3108C18.211 14.6756 17.9855 14.9732 17.7354 15.204L17.6548 15.2783C16.8451 16.0252 15.6294 16.121 14.7127 15.5099C14.3431 15.2635 14.0557 14.9233 13.8735 14.5325C13.3499 14.9205 12.7017 15.15 12 15.15C10.2603 15.15 8.85 13.7397 8.85 12C8.85 10.2603 10.2603 8.85 12 8.85C13.7397 8.85 15.15 10.2603 15.15 12V13.5241C15.15 13.8206 15.2981 14.0974 15.5448 14.2618C15.8853 14.4888 16.3369 14.4533 16.6377 14.1758L16.7183 14.1015C16.8295 13.9989 16.8991 13.8944 16.9345 13.7973C17.1384 13.2376 17.25 12.6327 17.25 12C17.25 9.1005 14.8995 6.75 12 6.75ZM12 10.35C12.9113 10.35 13.65 11.0887 13.65 12C13.65 12.9113 12.9113 13.65 12 13.65C11.0887 13.65 10.35 12.9113 10.35 12C10.35 11.0887 11.0887 10.35 12 10.35Z"
                                            fill="currentColor" />
                                    </svg>

                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">{{
                                        $t('Users')
                                        }}</span>
                                </div>
                            </router-link>
                        </li>
                    </ul>
                </perfect-scrollbar>
            </div>
        </nav>

        <!-- Modal for Create New -->
        <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-start pt-20 justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Pengajuan Baru</h2>
                    <button @click="closeCreateModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                </div>
                <div class="mb-4">
                    <label class="block mb-2 font-medium">Pilih Form</label>
                    <select v-model="selectedForm" class="w-full border rounded px-3 py-2">
                        <option value="" disabled>Pilih Form</option>
                        <option value="purchase-request">Permintaan Pembelian</option>
                        <option value="purchase-order">Pesanan Pembelian</option>
                        <option value="registration-asset">Pendaftaran Asset</option>
                    </select>
                </div>
                <button @click="proceedCreate" :disabled="!selectedForm"
                    class="bg-blue-500 text-white px-4 py-2 rounded w-full font-semibold disabled:opacity-50">Proses</button>
            </div>
        </div>
    </div>

</template>

<script lang="ts" setup>
import { ref, onMounted, computed } from 'vue';
import { useAppStore } from '@/stores/index';
import { useCreateFormSubmission } from '@/services/mutations';
import { useRoute } from 'vue-router';

const route = useRoute();
const store = useAppStore();
const activeDropdown: any = ref('');
const subActive: any = ref('');

// Modal state and logic
const showCreateModal = ref(false);
const selectedForm = ref('');

const openCreateModal = () => {
    console.log('Opening Create Modal');
    showCreateModal.value = true;
    selectedForm.value = '';
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const createFormSubmission = useCreateFormSubmission();

const handleCreateFormSubmission = (formType) => {
    createFormSubmission.mutate(formType);
};

const isPRApprovalActive = computed(() =>
  (route.path.includes('/form/purchase-request') && route.query.from === 'approval') ||
  route.path.startsWith('/approval/pr-approval')
);

const isPOApprovalActive = computed(() =>
  (route.path.includes('/form/purchase-order') && route.query.from === 'approval') ||
  route.path.startsWith('/approval/po-approval')
);

const isRAApprovalActive = computed(() =>
  (route.path.includes('/form/registration-asset') && route.query.from === 'approval') ||
  route.path.startsWith('/approval/ra-approval')
);


const proceedCreate = () => {
    // Navigate to the dynamic form page with the selected form type
    // purchaseRequest
    const formType = selectedForm.value;
    if (selectedForm.value) {
        handleCreateFormSubmission(formType)
        // window.location.href = `/apps/form-builder/${selectedForm.value}`;
    }
    closeCreateModal();
};

// Transition handlers untuk smooth height animation
const onEnter = (el: HTMLElement) => {
    el.style.height = '0';
    el.offsetHeight; // Force reflow
    el.style.height = el.scrollHeight + 'px';
};

const onAfterEnter = (el: HTMLElement) => {
    el.style.height = 'auto';
};

const onLeave = (el: HTMLElement) => {
    el.style.height = el.scrollHeight + 'px';
    el.offsetHeight; // Force reflow
    el.style.height = '0';
};

const onAfterLeave = (el: HTMLElement) => {
    el.style.height = 'auto';
};

onMounted(() => {
    const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
    if (selector) {
        selector.classList.add('active');
        const ul: any = selector.closest('ul.sub-menu');
        if (ul) {
            let ele: any = ul.closest('li.menu').querySelectorAll('.nav-link') || [];
            if (ele.length) {
                ele = ele[0];
                setTimeout(() => {
                    ele.click();
                });
            }
        }
    }
});

const toggleMobileMenu = () => {
    if (window.innerWidth < 1024) {
        store.toggleSidebar();
    }
};
</script>

<style scoped>
/* Smooth transition untuk collapse */
.overflow-hidden {
    transition: height 0.3s ease;
}
</style>