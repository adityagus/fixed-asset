<template>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Elements</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-2 rtl:before:ml-2">
                <span>Loader</span>
            </li>
        </ul>
        <div class="pt-5 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Loaders with Buttons</h5>
                    <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;" @click="toggleCode('code1')"
                        ><span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Code
                        </span></a
                    >
                </div>
                <div class="mb-5">
                    <div class="flex flex-wrap items-center justify-center w-full gap-4">
                        <button type="button" class="btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0">
                            <svg
                                viewBox="0 0 24 24"
                                width="24"
                                height="24"
                                stroke="currentColor"
                                stroke-width="1.5"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="w-5 h-5 animate-[spin_2s_linear_infinite] inline-block align-middle ltr:mr-2 rtl:ml-2 shrink-0"
                            >
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                            </svg>
                            Loading
                        </button>

                        <button type="button" class="btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0">
                            <span
                                class="animate-spin border-2 border-white border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle shrink-0"
                            ></span
                            >Loading
                        </button>

                        <button type="button" class="btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0">
                            <span class="animate-ping w-3 h-3 ltr:mr-4 rtl:ml-4 inline-block rounded-full bg-white shrink-0"></span>
                            Loading
                        </button>
                    </div>
                </div>
                <template v-if="codeArr.includes('code1')">
                    <highlight>
                        <pre>
&lt;!-- gradient yellow --&gt;
&lt;button type=&quot;button&quot; class=&quot;btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0&quot;&gt;
  &lt;svg&gt; ... &lt;/svg&gt;
  Loading
&lt;/button&gt;

&lt;button type=&quot;button&quot; class=&quot;btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0&quot;&gt;
  &lt;span class=&quot;animate-spin border-2 border-white border-l-transparent rounded-full w-5 h-5 ltr:mr-4 rtl:ml-4 inline-block align-middle&quot;&gt;&lt;/span&gt;Loading
&lt;/button&gt;

&lt;button type=&quot;button&quot; class=&quot;btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0&quot;&gt;
  &lt;span class=&quot;animate-ping w-3 h-3 ltr:mr-4 rtl:ml-4 inline-block rounded-full bg-white&quot;&gt;&lt;/span&gt;
  Loading
&lt;/button&gt;
</pre
                        >
                    </highlight>
                </template>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-5">
                    <h5 class="font-semibold text-lg dark:text-white-light">Position</h5>
                    <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;" @click="toggleCode('code2')"
                        ><span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Code
                        </span></a
                    >
                </div>
                <div class="mb-5">
                    <div class="flex flex-wrap w-full">
                        <div class="flex flex-wrap items-center justify-center w-full sm:w-1/2 text-center mb-3 sm:mb-0">
                            <p class="w-full mb-2">Default Button</p>
                            <button type="button" class="btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0">
                                <svg
                                    viewBox="0 0 24 24"
                                    width="24"
                                    height="24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="w-5 h-5 animate-[spin_2s_linear_infinite] inline-block align-middle ltr:mr-2 rtl:ml-2 shrink-0"
                                >
                                    <line x1="12" y1="2" x2="12" y2="6"></line>
                                    <line x1="12" y1="18" x2="12" y2="22"></line>
                                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                    <line x1="2" y1="12" x2="6" y2="12"></line>
                                    <line x1="18" y1="12" x2="22" y2="12"></line>
                                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                </svg>
                                Loading
                            </button>
                        </div>
                        <div class="flex flex-wrap items-center justify-center w-full sm:w-1/2 text-center">
                            <p class="w-full mb-2">Outline Button</p>
                            <button type="button" class="btn btn-lg border-2 border-yellow-500 text-yellow-600 hover:bg-gradient-to-r hover:from-yellow-400 hover:to-yellow-600 hover:text-white hover:border-transparent transition-all">
                                Loading
                                <svg
                                    viewBox="0 0 24 24"
                                    width="24"
                                    height="24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="w-5 h-5 animate-[spin_2s_linear_infinite] inline-block align-middle ltr:ml-2 rtl:mr-2 shrink-0"
                                >
                                    <line x1="12" y1="2" x2="12" y2="6"></line>
                                    <line x1="12" y1="18" x2="12" y2="22"></line>
                                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                    <line x1="2" y1="12" x2="6" y2="12"></line>
                                    <line x1="18" y1="12" x2="22" y2="12"></line>
                                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <template v-if="codeArr.includes('code2')">
                    <highlight>
                        <pre>
&lt;!-- default --&gt;
&lt;div class=&quot;flex flex-wrap items-center justify-center w-full sm:w-1/2 text-center mb-3 sm:mb-0&quot;&gt;
  &lt;p class=&quot;w-full mb-2&quot;&gt;Default Button&lt;/p&gt;
  &lt;button type=&quot;button&quot; class=&quot;btn btn-lg bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white border-0&quot;&gt;
    &lt;svg&gt; ... &lt;/svg&gt;
    Loading
  &lt;/button&gt;
&lt;/div&gt;

&lt;!-- outline --&gt;
&lt;div class=&quot;flex flex-wrap items-center justify-center w-full sm:w-1/2 text-center&quot;&gt;
  &lt;p class=&quot;w-full mb-2&quot;&gt;Outline Button&lt;/p&gt;
  &lt;button type=&quot;button&quot; class=&quot;btn btn-lg border-2 border-yellow-500 text-yellow-600 hover:bg-gradient-to-r hover:from-yellow-400 hover:to-yellow-600 hover:text-white hover:border-transparent transition-all&quot;&gt;
    Loading
    &lt;svg&gt; ... &lt;/svg&gt;
  &lt;/button&gt;
&lt;/div&gt;
</pre
                        >
                    </highlight>
                </template>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-10">
                    <h5 class="font-semibold text-lg dark:text-white-light">Sizes</h5>
                    <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;" @click="toggleCode('code3')"
                        ><span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Code
                        </span></a
                    >
                </div>
                <div class="mb-5">
                    <div class="grid grid-cols-3 w-full gap-4">
                        <span
                            class="animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-12 h-12 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-[3px] border-yellow-500 border-l-transparent rounded-full w-6 h-6 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-12 h-12 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-[3px] border-transparent border-l-yellow-500 rounded-full w-6 h-6 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span class="w-5 h-5 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-500"></span></span>
                        <span class="w-4 h-4 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-500"></span></span>
                        <span class="w-3 h-3 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-500"></span></span>
                    </div>
                </div>
                <template v-if="codeArr.includes('code3')">
                    <highlight>
                        <pre>
&lt;!-- large --&gt;
&lt;span class=&quot;animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-12 h-12 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- default --&gt;
&lt;span class=&quot;animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- small --&gt;
&lt;span class=&quot;animate-spin border-[3px] border-yellow-500 border-l-transparent rounded-full w-6 h-6 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- large --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-12 h-12 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- default --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- small --&gt;
&lt;span class=&quot;animate-spin border-[3px] border-transparent border-l-yellow-500 rounded-full w-6 h-6 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- large --&gt;
&lt;span class=&quot;w-5 h-5 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-500&quot;&gt;&lt;/span&gt;&lt;/span&gt;

&lt;!-- default --&gt;
&lt;span class=&quot;w-4 h-4 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-500&quot;&gt;&lt;/span&gt;&lt;/span&gt;

&lt;!-- small --&gt;
&lt;span class=&quot;w-3 h-3 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-500&quot;&gt;&lt;/span&gt;&lt;/span&gt;
</pre
                        >
                    </highlight>
                </template>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-10">
                    <h5 class="font-semibold text-lg dark:text-white-light">Colors</h5>
                    <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;" @click="toggleCode('code4')"
                        ><span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Code
                        </span></a
                    >
                </div>
                <div class="mb-5">
                    <div class="grid grid-cols-4 w-full gap-4">
                        <span
                            class="animate-spin border-4 border-yellow-400 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-yellow-600 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-amber-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-yellow-400 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-yellow-600 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-spin border-4 border-transparent border-l-amber-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span class="w-4 h-4 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-400"></span></span>
                        <span class="w-4 h-4 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-500"></span></span>
                        <span class="w-4 h-4 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-yellow-600"></span></span>
                        <span class="w-4 h-4 m-auto mb-10"><span class="animate-ping inline-flex h-full w-full rounded-full bg-amber-500"></span></span>
                    </div>
                </div>
                <template v-if="codeArr.includes('code4')">
                    <highlight>
                        <pre>
&lt;!-- yellow-400 --&gt;
&lt;span class=&quot;animate-spin border-4 border-yellow-400 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-500 --&gt;
&lt;span class=&quot;animate-spin border-4 border-yellow-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-600 --&gt;
&lt;span class=&quot;animate-spin border-4 border-yellow-600 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- amber-500 --&gt;
&lt;span class=&quot;animate-spin border-4 border-amber-500 border-l-transparent rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-400 --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-yellow-400 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-500 --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-yellow-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-600 --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-yellow-600 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- amber-500 --&gt;
&lt;span class=&quot;animate-spin border-4 border-transparent border-l-amber-500 rounded-full w-10 h-10 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- yellow-400 --&gt;
&lt;span class=&quot;w-4 h-4 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-400&quot;&gt;&lt;/span&gt;&lt;/span&gt;

&lt;!-- yellow-500 --&gt;
&lt;span class=&quot;w-4 h-4 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-500&quot;&gt;&lt;/span&gt;&lt;/span&gt;

&lt;!-- yellow-600 --&gt;
&lt;span class=&quot;w-4 h-4 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-yellow-600&quot;&gt;&lt;/span&gt;&lt;/span&gt;

&lt;!-- amber-500 --&gt;
&lt;span class=&quot;w-4 h-4 m-auto mb-10&quot;&gt;&lt;span class=&quot;animate-ping inline-flex h-full w-full rounded-full bg-amber-500&quot;&gt;&lt;/span&gt;&lt;/span&gt;
</pre
                        >
                    </highlight>
                </template>
            </div>

            <div class="panel">
                <div class="flex items-center justify-between mb-10">
                    <h5 class="font-semibold text-lg dark:text-white-light">Custom</h5>
                    <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;" @click="toggleCode('code5')">
                        <span class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                                <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                <path
                                    d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                />
                            </svg>
                            Code
                        </span>
                    </a>
                </div>
                <div class="mb-5">
                    <div class="grid grid-cols-3 w-full gap-4">
                        <span
                            class="animate-spin border-8 border-yellow-200 border-l-yellow-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-[spin_2s_linear_infinite] border-8 border-yellow-200 border-l-yellow-500 border-r-yellow-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10"
                        ></span>
                        <span
                            class="animate-[spin_3s_linear_infinite] border-8 border-r-yellow-400 border-l-yellow-500 border-t-yellow-600 border-b-amber-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10"
                        ></span>
                    </div>
                </div>
                <template v-if="codeArr.includes('code5')">
                    <highlight>
                        <pre>
&lt;!-- custom loader --&gt;
&lt;span class=&quot;animate-spin border-8 border-yellow-200 border-l-yellow-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- custom loader --&gt
&lt;span class=&quot;animate-[spin_2s_linear_infinite] border-8 border-yellow-200 border-l-yellow-500 border-r-yellow-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10&quot;&gt;&lt;/span&gt;

&lt;!-- custom loader --&gt
&lt;span
  class=&quot;animate-[spin_3s_linear_infinite] border-8 border-r-yellow-400 border-l-yellow-500 border-t-yellow-600 border-b-amber-500 rounded-full w-14 h-14 inline-block align-middle m-auto mb-10&quot;
&gt;&lt;/span&gt;
</pre
                        >
                    </highlight>
                </template>
            </div>
        </div>
    </div>
</template>
<script lang="ts" setup>
    import highlight from '@/components/plugins/highlight.vue';
    import codePreview from '@/composables/codePreview';
    import { useMeta } from '@/composables/use-meta';
    useMeta({ title: 'Loader' });

    const { codeArr, toggleCode } = codePreview();
</script>