<script setup lang="ts">
import HeaderLayout from '../components/HeaderLayout.vue'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
</script>

<template>
  <!-- <Head title="Garansi - Ella Elektrik" /> -->

  <!-- Header -->
    <HeaderLayout />

  <!-- Konten Utama -->
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Judul -->
      <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Garansi</h1>

      <!-- Paragraf 1 -->
      <p class="mb-6 text-gray-800 leading-relaxed">
        Ella Elektrik hanya menampilkan produk elektronik resmi yang memiliki garansi dari merek atau distributor terkait. Setiap produk yang kami tampilkan memiliki keterangan garansi yang dapat dilihat di halaman detail produk. Jika Anda ingin memastikan status garansi produk sebelum melakukan pemesanan, anda dapat menghubungi tim kami melalui halaman Contact Us.
      </p>

      <!-- Subjudul -->
      <h2 class="text-xl font-semibold text-gray-900 mt-10 mb-4">Beberapa hal yang perlu diperhatikan :</h2>

      <!-- Daftar Poin -->
      <ul class="list-disc pl-6 space-y-3 mb-6 text-gray-800 leading-relaxed">
        <li>
          Garansi produk mengikuti ketentuan dari masing-masing merek atau pihak distributor.
        </li>
        <li>
          Proses klaim garansi dilakukan langsung melalui pusat layanan resmi (service center) sesuai petunjuk yang tertera di kartu garansi produk.
        </li>
        <li>
          Ella Elektrik membantu memberikan informasi kontak service center jika pelanggan membutuhkan panduan lebih lanjut.
        </li>
      </ul>

      <!-- Paragraf 2 -->
      <p class="text-gray-800 leading-relaxed">
        Kami percaya bahwa kejelasan informasi garansi merupakan salah satu hal terpenting dalam membangun rasa percaya antara pelanggan dan penyedia produk. Dengan memberikan penjelasan yang transparan mengenai ketentuan garansi, masa berlaku, serta proses klaim yang perlu dilakukan, pelanggan dapat merasa lebih aman dan yakin dalam setiap keputusan pembelian.
      </p>
    </div>
  </div>
</template>

<style scoped>
/* Optional: tambahkan styling jika perlu */
</style>