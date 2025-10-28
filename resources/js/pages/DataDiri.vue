<script setup lang="ts">
import HeaderLayout from '../components/HeaderLayout.vue'
import { ref } from 'vue';

// Form state
const form = ref({
  nama: '',
  email: '',
  telepon: '',
  provinsi: '',
  kota: '',
  alamat: '',
  nik: '',
  ktp: null as File | null,
});

// Handle file upload
function handleUpload(event: Event) {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    form.value.ktp = target.files[0];
  }
}

// Submit form
function handleSubmit() {
  console.log('Data dikirim:', form.value);
  alert('Data berhasil disimpan!');
}
</script>

<template>
  <div class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <HeaderLayout />

    <!-- Form container -->
    <div class="flex justify-center items-start pt-20 p-4">
      <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <!-- Judul Form -->
        <h2 class="text-center text-xl font-semibold mb-1 text-black">Data Diri</h2>
        <div class="text-center mb-4">
          <i class="fas fa-user text-2xl text-gray-600"></i>
        </div>
        <p class="text-center text-sm text-gray-500 mb-6">
          Silahkan isi data diri anda terlebih dahulu
        </p>

        <!-- Form -->
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Nama Lengkap -->
          <input
            v-model="form.nama"
            type="text"
            placeholder="Nama Lengkap"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Alamat Email -->
          <input
            v-model="form.email"
            type="email"
            placeholder="Alamat Email"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Nomor Telepon -->
          <input
            v-model="form.telepon"
            type="text"
            placeholder="Nomor Telepon"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Provinsi -->
          <input
            v-model="form.provinsi"
            type="text"
            placeholder="Provinsi"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Kota/Kabupaten -->
          <input
            v-model="form.kota"
            type="text"
            placeholder="Kota/Kabupaten"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Alamat Lengkap -->
          <input
            v-model="form.alamat"
            type="text"
            placeholder="Alamat Lengkap"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- NIK -->
          <input
            v-model="form.nik"
            type="text"
            placeholder="NIK"
            class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
          />

          <!-- Unggah KTP -->
          <div class="mt-4">
            <label class="block text-sm text-gray-600 mb-2">Unggah KTP Anda</label>
            <div
              class="border-2 border-dashed border-gray-300 p-6 text-center cursor-pointer hover:border-blue-500 transition"
              @click="$refs.fileInput.click()"
            >
              <i class="fas fa-file-upload text-2xl text-gray-500 mb-2"></i>
              <p class="text-gray-600">Klik untuk unggah KTP</p>
              <input
                ref="fileInput"
                type="file"
                @change="handleUpload"
                class="hidden"
                accept="image/*,.pdf"
              />
              <p v-if="form.ktp" class="mt-2 text-sm text-green-600">
                File terpilih: {{ form.ktp.name }}
              </p>
            </div>
          </div>

          <!-- Simpan Button -->
          <button
            type="submit"
            class="w-full bg-blue-500 text-white font-medium py-2 rounded mt-6 hover:bg-blue-600 transition"
          >
            Simpan
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<style scoped>
input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
}

input::placeholder {
  color: #9ca3af;
  opacity: 1;
}
</style>
