<script setup lang="ts">
import HeaderLayout from '../components/HeaderLayout.vue'
import { Head } from '@inertiajs/vue3';

</script>

<template>
  <!-- <Head title="Tentang Kami - Ella Elektrik" /> -->

  <!-- Header -->
    <HeaderLayout />

  <!-- Konten Utama -->
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">
      <!-- Judul -->
      <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">About Us</h1>

      <!-- Logo Ella Elektrik -->
      <div class="flex justify-center mb-12">
        <img src="/images/logoella.png" alt="Logo Ella Elektrik" class="h-32 w-auto" />
      </div>

      <!-- Section 1: Tentang Ella Elektrik -->
      <section class="mb-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-gray-300 pb-2">Tentang Ella Elektrik</h2>
        <p class="mb-4 text-black">
          Ella Elektrik adalah sebuah platform katalog dan pemesanan produk elektronik rumah tangga yang dibuat untuk memberikan kemudahan bagi masyarakat dalam mencari dan memesan berbagai jenis perangkat elektronik. Kami hadir sebagai jembatan antara pelanggan dan penyedia produk, agar proses pemesanan bisa dilakukan dengan cara yang lebih cepat, mudah, dan jelas.
        </p>
        <p class="mb-4 text-black">
          Berbeda dari toko online yang langsung menyediakan fitur pembayaran dan pengiriman otomatis, Ella Elektrik berfokus pada proses pre-order dan product inquiry. Melalui website ini, pelanggan dapat melihat katalog produk secara lengkap, mempelajari spesifikasi, membandingkan harga, lalu menyimpan atau memesan produk yang diminati. Setelah itu, tim kami akan membantu memproses pesanan dan memberikan informasi lebih lanjut mengenai ketersediaan dan cara pembelian.
        </p>
        <p class="mb-4 text-black">
          Kami memahami bahwa banyak pelanggan ingin memastikan ketersediaan, spesifikasi, dan kesesuaian produk sebelum melakukan pembelian. Karena itu, Ella Elektrik hadir sebagai solusi praktis untuk membantu pelanggan menemukan produk elektronik terbaik dengan cara yang lebih personal dan terarah.
        </p>
      </section>

      <!-- Section 2: Visi & Misi -->
      <section class="mb-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-gray-300 pb-2">Visi & Misi Ella Elektrik</h2>

        <h3 class="text-xl font-semibold text-gray-900 mt-6 mb-2">Visi</h3>
        <p class="mb-4 text-black">
          Menjadi platform katalog elektronik terpercaya di Indonesia yang memudahkan masyarakat dalam menemukan, memilih, dan memesan produk elektronik rumah tangga secara praktis dan informatif.
        </p>

        <h3 class="text-xl font-semibold text-gray-900 mt-6 mb-2">Misi</h3>
        <p class="mb-4 text-black">
          Ella Elektrik berkomitmen untuk menyediakan informasi produk elektronik yang lengkap, jelas, dan mudah dipahami oleh setiap pengguna. Melalui website ini, pelanggan dapat dengan mudah mencari dan memesan produk tanpa perlu datang langsung ke toko. Kami berupaya membangun kepercayaan pelanggan dengan menghadirkan tampilan website yang profesional, sederhana, dan nyaman digunakan. Selain itu, Ella Elektrik juga berperan dalam membantu penjual elektronik untuk mempromosikan produknya secara digital. Kami akan terus berinovasi dan meningkatkan kualitas layanan agar pengalaman pengguna menjadi semakin mudah dan menyenangkan.
        </p>
      </section>

      <!-- Section 3: Keunggulan Ella Elektrik -->
      <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-gray-300 pb-2">Keunggulan Ella Elektrik</h2>
        <p class="mb-4 text-black">
          Ella Elektrik hadir dengan komitmen untuk memberikan kemudahan, kepercayaan, dan kenyamanan bagi setiap pelanggan. Kami percaya bahwa pengalaman berbelanja bukan hanya soal produk, tetapi juga tentang kepuasan dan rasa aman ketika memilih. Oleh karena itu, kami berpegang pada nilai-nilai utama yang menjadi dasar dalam setiap langkah kami:
        </p>

        <ul class="list-disc pl-6 space-y-4 text-black">
          <li>
            <strong>Kualitas Terjamin</strong><br>
            Setiap produk yang ditampilkan di Ella Elektrik telah melalui proses kurasi agar pelanggan hanya mendapatkan pilihan terbaik dari merek terpercaya dan bergaransi resmi.
          </li>
          <li>
            <strong>Kemudahan dan Kenyamanan</strong><br>
            Website yang dirancang dengan tampilan sederhana dan navigasi yang mudah, sehingga pengguna dapat menemukan produk yang mereka butuhkan dengan cepat tanpa kebingungan.
          </li>
          <li>
            <strong>Transparansi Informasi</strong><br>
            Kami menyajikan informasi produk secara jelas dan akurat — mulai dari spesifikasi, harga, hingga ketersediaan — agar pengguna dapat membuat keputusan dengan yakin.
          </li>
        </ul>
      </section>
    </div>
  </div>
</template>

<style scoped>
/* Optional: tambahkan styling jika perlu */
</style>