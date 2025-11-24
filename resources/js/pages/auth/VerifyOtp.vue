<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="bg-white shadow-xl rounded-2xl p-6 w-full max-w-md">
      <h2 class="text-2xl font-bold text-center mb-4">Verifikasi OTP</h2>
      <p class="text-gray-600 text-center mb-6">Masukkan kode OTP yang dikirim ke email Anda</p>

      <form @submit.prevent="verifyOtp">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Kode OTP</label>
          <input
            v-model="otp"
            type="text"
            maxlength="6"
            class="w-full border rounded-lg p-2 focus:ring focus:outline-none"
            placeholder="Masukkan 6 digit OTP"
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold"
          :disabled="loading"
        >
          {{ loading ? 'Memverifikasi...' : 'Verifikasi' }}
        </button>
      </form>

      <p class="text-center text-sm text-gray-600 mt-4">
        Tidak menerima kode? 
        <button @click="resendOtp" class="text-blue-600 hover:underline" :disabled="resendLoading">
          {{ resendLoading ? 'Mengirim...' : 'Kirim ulang OTP' }}
        </button>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const otp = ref('')
const loading = ref(false)
const resendLoading = ref(false)

const verifyOtp = () => {
  loading.value = true
  router.post('/verify-otp', { otp: otp.value }, {
    onFinish: () => (loading.value = false)
  })
}

const resendOtp = () => {
  resendLoading.value = true
  router.post('/resend-otp', {}, {
    onFinish: () => (resendLoading.value = false)
  })
}
</script>
