<template>
  <main class="pt-20">
    <section class="bg-[#123458] text-white flex flex-col md:flex-row items-center justify-between px-12 py-16">
      <!-- Teks -->
      <div class="max-w-lg space-y-6 -mt-6">
        <div class="mb-20 ml-12">
          <h1 class="text-4xl font-bold">Ella Elektrik</h1>
        </div>

        <div class="space-y-2 mb-20 ml-12">
          <p>Solusi Elektronik Terlengkap untuk Kebutuhan Anda</p>
          <p>Belanja Elektronik Mudah & Terpercaya</p>
          <p>Semua ada di Ella Elektrik</p>
        </div>

        <div class="ml-12">
          <button class="border border-white px-4 py-2 rounded hover:bg-white hover:text-[#183247] transition">
            Lihat Selengkapnya
          </button>
        </div>
      </div>

      <!-- Gambar produk -->
      <div class="-mt-10 mr-3">
        <img src="/images/produklogo.png" alt="Produk Ella Elektrik" class="w-[700px]" />
      </div>
    </section>

    <!-- KATEGORI PILIHAN -->
    <section class="bg-[#d8ccc0] py-12 px-8 text-center">
      <h2 class="text-2xl font-bold mb-8 text-left ml-14">Kategori Pilihan</h2>
      <div class="flex flex-wrap justify-center gap-10">

        <!-- Kategori 1 -->
        <Link
          href="/shop/Elektronik-Rumah-Tangga"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-60 h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/elektronikrumahtangga.png" alt="Elektronik Rumah Tangga" class="w-50 h-40 mb-4" />
          <span class="font-medium text-lg">Elektronik Rumah Tangga</span>
        </Link>

        <!-- Kategori 2 -->
        <Link
          href="/shop/Elektronik-Dapur"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-60 h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/elektronikdapur.png" alt="Elektronik Dapur" class="w-50 h-40 mb-4" />
          <span class="font-medium text-lg">Elektronik Dapur</span>
        </Link>

        <!-- Kategori 3 -->
        <Link
          href="/shop/Kelistrikan"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-60 h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/kelistrikan.png" alt="Kelistrikan" class="w-50 h-40 mb-4" />
          <span class="font-medium text-lg">Kelistrikan</span>
        </Link>
      </div>
    </section>
  </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
/* bisa tambahkan efek tambahan di sini nanti kalau mau animasi hover */
</style>
