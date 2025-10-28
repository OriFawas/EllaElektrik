<template>
  <header class="bg-[#183045] text-white shadow-md w-full fixed top-0 left-0 z-50">
    <div class="flex items-center justify-between px-12 py-4">
      <!-- Kiri: Logo + Menu -->
      <div class="flex items-center space-x-12">
      <!-- Logo + Nama -->
      <Link href="/" class="flex items-center space-x-3 hover:opacity-90 transition">
        <img src="/images/logoella.png" alt="Logo Ella Elektrik" class="h-12 w-auto" />
        <span class="font-semibold text-lg tracking-wide">Ella Elektrik</span>
      </Link>

        <!-- Menu -->
        <nav class="flex items-center space-x-8 text-base">
          <Link href="#" class="hover:text-gray-300 transition">Kategori</Link>
          <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
            <i class="fas fa-search"></i>
            <span>Cari</span>
          </div>
        </nav>
      </div>

      <!-- Kanan: Keranjang + Login/Profile -->
      <div class="flex items-center space-x-10">
        <!-- Compare Produk -->
        <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
          <img src="/images/compare-icon.png" alt="Compare Produk" class="h-5 w-6">
        </div>

        <!-- Keranjang -->
        <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
          <img src="/images/cart-icon.png" alt="Keranjang" class="h-5 w-5">
        </div>

        <!-- Jika belum login -->
        <template v-if="!$page.props.auth?.user">
          <Link href="/login" class="hover:text-gray-300 transition font-medium">Login</Link>
        </template>

        <!-- Jika sudah login -->
        <template v-else>
          <div class="relative">
            <button
              @click="toggleDropdown"
              class="flex items-center gap-2 hover:text-gray-300 transition font-medium focus:outline-none"
            >
              <span>{{ $page.props.auth.user.name ?? 'Profile' }}</span>
              <i class="fas fa-user text-lg"></i>
            </button>

            <!-- Dropdown -->
            <div
              v-if="showDropdown"
              class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-md shadow-lg overflow-hidden z-50"
            >
              <Link
                href="/profile"
                class="block px-4 py-2 hover:bg-gray-100 transition"
              >
                Profile
              </Link>
              <button
                @click="logout"
                class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600 transition"
              >
                Logout
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted } from 'vue'

const showDropdown = ref(false)

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

const handleClickOutside = (e) => {
  if (!e.target.closest('.relative')) {
    showDropdown.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))

// ✅ Logout fix — gunakan path langsung dan redirect manual
const logout = () => {
  router.post('/logout', {}, {
    onFinish: () => {
      router.visit('/') // balik ke halaman utama
    }
  })
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
</style>
