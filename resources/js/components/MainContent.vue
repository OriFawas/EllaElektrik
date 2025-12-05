<template>
  <main class="pt-15">

    <!-- HERO SECTION -->
    <section class="bg-[#123458] text-white flex flex-col md:flex-row items-center justify-between px-6 md:px-12 py-12 md:py-16">

      <!-- TEKS -->
      <div class="max-w-lg space-y-6 md:-mt-6 text-center md:text-left">
        <div class="mb-10 md:mb-20 md:ml-12">
          <h1 class="text-3xl md:text-4xl font-bold">Ella Elektrik</h1>
        </div>

        <div class="space-y-2 mb-10 md:mb-20 md:ml-12">
          <p>Solusi Elektronik Terlengkap untuk Kebutuhan Anda</p>
          <p>Belanja Elektronik Mudah & Terpercaya</p>
          <p>Semua ada di Ella Elektrik</p>
        </div>

        <div class="md:ml-12">
          <Link
            href="/shop/Elektronik-Rumah-Tangga"
            class="border border-white px-4 py-2 rounded hover:bg-white hover:text-[#183247] transition"
          >
            Lihat Selengkapnya
          </Link>
        </div>
      </div>

      <!-- GAMBAR -->
      <div class="mt-8 md:-mt-10 md:mr-3 flex justify-center md:block">
        <img
          src="/images/produklogo.png"
          alt="Produk Ella Elektrik"
          class="w-60 sm:w-72 md:w-[700px]"
        />
      </div>
    </section>


    <!-- KATEGORI PILIHAN -->
    <section class="bg-[#d8ccc0] py-12 px-6 md:px-8 text-center">
      <h2 class="text-2xl font-bold mb-8 text-left md:ml-14">Kategori Pilihan</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 justify-items-center">

        <!-- Kategori 1 -->
        <Link
          href="/shop/Elektronik-Rumah-Tangga"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-52 h-56 sm:w-60 sm:h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/elektronikrumahtangga.png" class="w-40 h-32 mb-4" />
          <span class="font-medium text-lg text-center">Elektronik Rumah Tangga</span>
        </Link>

        <!-- Kategori 2 -->
        <Link
          href="/shop/Elektronik-Dapur"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-52 h-56 sm:w-60 sm:h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/elektronikdapur.png" class="w-40 h-32 mb-4" />
          <span class="font-medium text-lg text-center">Elektronik Dapur</span>
        </Link>

        <!-- Kategori 3 -->
        <Link
          href="/shop/Kelistrikan"
          class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-52 h-56 sm:w-60 sm:h-60 flex flex-col items-center justify-center transition"
        >
          <img src="/images/kelistrikan.png" class="w-40 h-32 mb-4" />
          <span class="font-medium text-lg text-center">Kelistrikan</span>
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
