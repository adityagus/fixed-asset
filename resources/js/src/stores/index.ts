import { defineStore } from 'pinia';
import i18n from '@/i18n';
import appSetting from '@/app-setting';
import {LoginPayload, UserData} from '@/types/user'; 
import { login as apiLogin } from '@/services/api/submissionService';


export const useAppStore = defineStore('app', {
    state: () => ({
        token: (localStorage.getItem('authToken') ||
            sessionStorage.getItem('authToken') ||
            null) as string | null,
        user:
            localStorage.getItem('authUser') ||
            sessionStorage.getItem('authUser') ||
            null
                ? (JSON.parse(
                      localStorage.getItem('authUser') ||
                          sessionStorage.getItem('authUser') ||
                          'null'
                  ) as UserData | null)
                : null,
        loading: false as boolean,
        isDarkMode: false,
        mainLayout: 'app',
        theme: 'light',
        menu: 'vertical',
        layout: 'full',
        rtlClass: 'ltr',
        animation: '',
        navbar: 'navbar-sticky',
        locale: 'en',
        sidebar: false,
        activeTab: 'purchase-request',
        languageList: [
            { code: 'zh', name: 'Chinese' },
            { code: 'da', name: 'Danish' },
            { code: 'en', name: 'English' },
            { code: 'fr', name: 'French' },
            { code: 'de', name: 'German' },
            { code: 'el', name: 'Greek' },
            { code: 'hu', name: 'Hungarian' },
            { code: 'it', name: 'Italian' },
            { code: 'ja', name: 'Japanese' },
            { code: 'pl', name: 'Polish' },
            { code: 'pt', name: 'Portuguese' },
            { code: 'ru', name: 'Russian' },
            { code: 'es', name: 'Spanish' },
            { code: 'sv', name: 'Swedish' },
            { code: 'tr', name: 'Turkish' },
            { code: 'ae', name: 'Arabic' },
        ],
        isShowMainLoader: true,
        semidark: false,
    }),

    actions: {
        setMainLayout(payload: any = null) {
            this.mainLayout = payload //app , auth
        },
        toggleTheme(payload: any = null) {
            payload = payload || this.theme // light|dark|system
            localStorage.setItem('theme', payload)
            this.theme = payload
            if (payload == 'light') {
                this.isDarkMode = false
            } else if (payload == 'dark') {
                this.isDarkMode = true
            } else if (payload == 'system') {
                if (
                    window.matchMedia &&
                    window.matchMedia('(prefers-color-scheme: dark)').matches
                ) {
                    this.isDarkMode = true
                } else {
                    this.isDarkMode = false
                }
            }

            if (this.isDarkMode) {
                document.querySelector('body')?.classList.add('dark')
            } else {
                document.querySelector('body')?.classList.remove('dark')
            }
        },
        toggleMenu(payload: any = null) {
            payload = payload || this.menu // vertical, collapsible-vertical, horizontal
            this.sidebar = false // reset sidebar state
            localStorage.setItem('menu', payload)
            this.menu = payload
        },
        tabSubmission(payload: any = null) {
            payload = payload || this.activeTab
            console.log('tabSubmission called with:', payload);
            localStorage.setItem('submissionTab', payload)
            this.activeTab = payload
        },
        toggleLayout(payload: any = null) {
            payload = payload || this.layout // full, boxed-layout
            localStorage.setItem('layout', payload)
            this.layout = payload
        },
        toggleRTL(payload: any = null) {
            payload = payload || this.rtlClass // rtl, ltr
            localStorage.setItem('rtlClass', payload)
            this.rtlClass = payload
            document
                .querySelector('html')
                ?.setAttribute('dir', this.rtlClass || 'ltr')
        },
        toggleAnimation(payload: any = null) {
            payload = payload || this.animation // animate__fadeIn, animate__fadeInDown, animate__fadeInUp, animate__fadeInLeft, animate__fadeInRight, animate__slideInDown, animate__slideInLeft, animate__slideInRight, animate__zoomIn
            payload = payload?.trim()
            localStorage.setItem('animation', payload)
            this.animation = payload
            appSetting.changeAnimation()
        },
        toggleNavbar(payload: any = null) {
            payload = payload || this.navbar // navbar-sticky, navbar-floating, navbar-static
            localStorage.setItem('navbar', payload)
            this.navbar = payload
        },
        toggleSemidark(payload: any = null) {
            payload = payload || false
            localStorage.setItem('semidark', payload)
            this.semidark = payload
        },
        toggleLocale(payload: any = null) {
            payload = payload || this.locale
            i18n.global.locale.value = payload
            localStorage.setItem('i18n_locale', payload)
            this.locale = payload
            if (this.locale?.toLowerCase() === 'ae') {
                this.toggleRTL('rtl')
            } else {
                this.toggleRTL('ltr')
            }
        },
        toggleSidebar(state: boolean = false) {
            this.sidebar = !this.sidebar
        },
        toggleMainLoader(state: boolean = false) {
            this.isShowMainLoader = true
            setTimeout(() => {
                this.isShowMainLoader = false
            }, 500)
        },
        async login(payload: LoginPayload) {
            this.loading = true
            try {
                // Reset user & token sebelum login baru
                this.token = null
                this.user = null
                localStorage.removeItem('authToken')
                localStorage.removeItem('authUser')
                sessionStorage.removeItem('authToken')
                sessionStorage.removeItem('authUser')

                // panggil service API (harus sesuai dengan service-mu)
                const res = await apiLogin({
                    user: payload.user,
                    pass: payload.pass,
                })
                const token = res?.token
                const user = res?.user ?? { name: payload.user } // sesuaikan dengan respon API

                if (!token) {
                    throw new Error('Token tidak diterima dari server')
                }

                // simpan di state
                this.token = token
                this.user = user

                // simpan persistence berdasarkan rememberMe
                if (payload.rememberMe) {
                    localStorage.setItem('authToken', token)
                    localStorage.setItem('authUser', JSON.stringify(user))
                    // hapus sessionStorage bila ada
                    sessionStorage.removeItem('authToken')
                    sessionStorage.removeItem('authUser')
                } else {
                    sessionStorage.setItem('authToken', token)
                    sessionStorage.setItem('authUser', JSON.stringify(user))
                    localStorage.removeItem('authToken')
                    localStorage.removeItem('authUser')
                }

                this.loading = false
                return res
            } catch (err) {
                this.loading = false
                // lempar error agar komponen bisa menampilkan pesan
                throw err
            }
        },

        logout() {
            this.token = null
            this.user = null
            localStorage.removeItem('authToken')
            localStorage.removeItem('authUser')
            sessionStorage.removeItem('authToken')
            sessionStorage.removeItem('authUser')
        },

        // bila mau: helper untuk restore manual (opsional)
        async hydrateFromStorage() {
            const token =
                localStorage.getItem('authToken') ||
                sessionStorage.getItem('authToken') ||
                null
            const userRaw =
                localStorage.getItem('authUser') ||
                sessionStorage.getItem('authUser') ||
                null
            this.token = token
            this.user = userRaw ? JSON.parse(userRaw) : null
        },
    },
    getters: {
        isLoggedIn: (state) => !!state.token,
    },
})
