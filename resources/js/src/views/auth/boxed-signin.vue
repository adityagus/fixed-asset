<template>
  <div
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 px-4 py-8">
    <div class="w-full max-w-md">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 space-y-6">
        <div class="text-center space-y-2">
          <img src="/assets/images/logo-vertical.png" alt="Logo" class="mx-auto h-12 w-auto" />
          <h3 class="text-gray-600 dark:text-gray-400">
            Please login to your account
          </h3>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <div>
            <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Username
            </label>
            <div class="relative">
              <input id="username" v-model="formData.user" type="text" placeholder="Enter your username"
                class="w-full px-4 py-3 pl-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                required />
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
              </span>
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Password
            </label>
            <div class="relative">
              <input id="password" v-model="formData.pass" :type="showPassword ? 'text' : 'password'"
                placeholder="Enter your password"
                class="w-full px-4 py-3 pl-11 pr-11 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                required />
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
              </span>
              <button type="button" @click="showPassword = !showPassword"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <!-- eye icon -->
              </button>
            </div>
          </div>

          <!-- <div class="flex items-center justify-between">
            <label class="flex items-center cursor-pointer">
              <input v-model="formData.rememberMe" type="checkbox"
                class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary focus:ring-offset-0" />
              <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                Remember me
              </span>
            </label>
            <a href="#" class="text-sm text-primary hover:text-primary/80 transition">
              Forgot password?
            </a>
          </div> -->

          <button type="submit"
            class="w-full py-3 px-4 bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition duration-200">
            Sign In
          </button>
        </form>

      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { useMeta } from '@/composables/use-meta';
import Swal from 'sweetalert2';
import { useAppStore } from '@/stores/index';

useMeta({ title: 'Login' });

const router = useRouter();
const showPassword = ref(false);

const formData = reactive({
  user: '',
  pass: '',
  rememberMe: false
});

const app = useAppStore();

const handleLogin = async () => {
  console.log('Login attempt:', formData);
  try {
    await app.login({ user: formData.user, pass: formData.pass, rememberMe: formData.rememberMe });
    // berhasil, redirect ke home atau halaman yang diinginkan
    router.push('/');
  } catch (error: any) {
    console.log('Login failed:', error);
    const errmessage = error.response?.data?.message || error.message || 'Internal Server Error';
    Swal.fire({
      icon: 'error',
      title: 'Login Failed',
      customClass: 'sweet-alerts',
      text: errmessage,
      timer: 2000
    });
  }
};
</script>

<style scoped>
.form-checkbox {
    @apply rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50;
}
</style>