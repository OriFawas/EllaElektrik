<script setup lang="ts">
import HeaderLayout from '../components/HeaderLayout.vue'
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// State untuk accordion
const openIndex = ref(-1);

function toggleAccordion(index: number) {
  openIndex.value = openIndex.value === index ? -1 : index;
}
</script>

<template>
  <!-- <Head title="Help Center - Ella Elektrik" /> -->

  <!-- Header -->
    <HeaderLayout />

  <!-- Konten Utama -->
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Judul -->
      <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Help Center</h1>

      <!-- FAQ Accordion -->
      <div class="space-y-4">
        <!-- FAQ 1 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(0)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Apakah website ini menjual produk atau hanya katalog?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 0 }"
            ></i>
          </button>
          <div v-show="openIndex === 0" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Website ini berfungsi sebagai katalog untuk menampilkan informasi detail produk. Pemesanan dapat dilakukan melalui website dan datang langsung ke toko untuk pengambilan barang.
          </div>
        </div>

        <!-- FAQ 2 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(1)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Bagaimana cara melakukan pemesanan produk?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 1 }"
            ></i>
          </button>
          <div v-show="openIndex === 1" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Anda dapat memilih produk yang diinginkan, lalu klik tombol "Pesan". Tim kami akan menghubungi Anda untuk konfirmasi ketersediaan, harga, dan cara pembelian.
          </div>
        </div>

        <!-- FAQ 3 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(2)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Apakah stok produk selalu tersedia?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 2 }"
            ></i>
          </button>
          <div v-show="openIndex === 2" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Stok produk bisa berubah sewaktu-waktu. Kami akan memberikan informasi terbaru saat Anda melakukan pemesanan.
          </div>
        </div>

        <!-- FAQ 4 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(3)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Apakah Ella Elektrik menyediakan garansi produk?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 3 }"
            ></i>
          </button>
          <div v-show="openIndex === 3" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Ya, semua produk yang kami tawarkan adalah produk resmi dari brand terpercaya dan dilengkapi garansi resmi sesuai ketentuan masing-masing produsen.
          </div>
        </div>

        <!-- FAQ 5 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(4)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Apakah tersedia pengiriman?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 4 }"
            ></i>
          </button>
          <div v-show="openIndex === 4" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Saat ini, Ella Elektrik berfokus pada proses pemesanan dan konsultasi produk. Untuk pengiriman, kami akan menginformasikan opsi yang tersedia setelah pesanan dikonfirmasi.
          </div>
        </div>

        <!-- FAQ 6 -->
        <div class="bg-white rounded-lg shadow-sm">
          <button
            @click="toggleAccordion(5)"
            class="w-full px-6 py-4 text-left font-medium text-gray-700 flex justify-between items-center hover:bg-gray-50 transition"
          >
            <span>Berapa lama proses pengiriman?</span>
            <i
              class="fas fa-chevron-down text-sm transition-transform"
              :class="{ 'rotate-180': openIndex === 5 }"
            ></i>
          </button>
          <div v-show="openIndex === 5" class="px-6 pb-4 pt-2 text-gray-700 leading-relaxed border-t border-gray-100">
            Proses pengiriman biasanya membutuhkan waktu 2-5 hari kerja, tergantung lokasi tujuan.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Optional: tambahkan styling jika perlu */
</style>