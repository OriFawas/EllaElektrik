<script setup>
import { Head } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, onMounted } from 'vue'

const form = {
  name: 'Danan',
  email: 'danan@example.com',
  phone: '08123456789',
  province: 'Jawa Timur',
  city: 'Surabaya',
  address: 'Jl. Mawar No. 123',
  nik: '3501234567890001',
  ktp: null
}

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.ktp = file.name
    alert(`File KTP dipilih: ${file.name}`)
  }
}

const submit = () => {
  alert('Data profil tersimpan (simulasi saja)')
}
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">
  <HeaderLayout />
  <Head title="Profil Saya" />
  <UserLayout>
    <!-- Container full width -->
    <div class="bg-white p-8 rounded-lg shadow w-full ">
      <h1 class="text-2xl font-semibold mb-6">Profil Saya</h1>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Nama Lengkap</label>
            <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>

          <div>
            <label class="text-sm font-medium">Alamat Email</label>
            <input v-model="form.email" type="email" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Nomor Telepon</label>
            <input v-model="form.phone" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="text-sm font-medium">NIK</label>
            <input v-model="form.nik" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Provinsi</label>
            <input v-model="form.province" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="text-sm font-medium">Kota / Kabupaten</label>
            <input v-model="form.city" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
        </div>

        <div>
          <label class="text-sm font-medium">Alamat Lengkap</label>
          <textarea v-model="form.address" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea>
        </div>

        <div>
        <label class="text-sm font-medium">Upload KTP</label>

        <!-- input file, tetap tampil tapi kecil (mirip ukuran KTP) -->
        <div class="mt-1">
          <input
            type="file"
            @change="onFileChange"
            class="border rounded p-2 w-90 h-13 cursor-pointer"
          />
        </div>

        <!-- info file -->
        <p v-if="form.ktp" class="text-green-600 text-sm mt-1">
          File dipilih: {{ form.ktp }}
        </p>
      </div>


        <div class="text-right">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </UserLayout>
  <FooterLayout />
  </div>
</template>
