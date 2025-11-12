<template>
<<<<<<< HEAD
  <header class="bg-[#183045] text-white shadow-md w-full">
    <div class="flex items-center justify-between px-12 py-4">
      <!-- Kiri: Logo + Menu -->
      <div class="flex items-center space-x-12">
        <!-- Logo + Nama -->
        <div class="flex items-center space-x-3">
          <img src="/images/logoella.png" alt="Logo Ella Elektrik" class="h-12 w-auto" />
          <span class="font-semibold text-lg tracking-wide">Ella Elektrik</span>
        </div>
=======
  <header class="bg-[#183045] text-white shadow-md w-full fixed top-0 left-0 z-50">
    <div class="flex items-center justify-between px-12 py-4">
      <!-- Kiri: Logo + Menu -->
      <div class="flex items-center space-x-12">
      <!-- Logo + Nama -->
      <Link href="/" class="flex items-center space-x-3 hover:opacity-90 transition">
        <img src="/images/logoella.png" alt="Logo Ella Elektrik" class="h-12 w-auto" />
        <span class="font-semibold text-lg tracking-wide">Ella Elektrik</span>
      </Link>
>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b

        <!-- Menu -->
        <nav class="flex items-center space-x-8 text-base">
          <Link href="#" class="hover:text-gray-300 transition">Kategori</Link>
<<<<<<< HEAD
          <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
            <i class="fas fa-search"></i>
            <span>Cari</span>
          </div>
        </nav>
=======
        </nav>

        <!-- Kiri: Search (Left aligned, long) -->
        <div class="relative header-search w-[36rem] max-w-[50vw] hidden sm:block">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/80"></i>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari"
            class="w-full bg-transparent text-white placeholder-white/70 border-0 border-b-2 border-white/90 focus:border-white outline-none pl-9 pr-3 py-2"
            @focus="openResults"
            @input="debouncedSearch"
          />
          <!-- Results Dropdown -->
          <div v-if="searchOpen" class="absolute left-0 top-full mt-2 w-full z-50">
            <div class="bg-white text-gray-800 rounded-md shadow-lg p-2">
              <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                <div v-if="searchLoading" class="py-4 text-center text-sm text-gray-500">Memuat…</div>
                <div v-else-if="searchError" class="py-4 text-center text-sm text-red-500">{{ searchError }}</div>
                <div v-else>
                  <div v-if="searchQuery.trim() && results.length === 0" class="py-4 text-center text-sm text-gray-500">Tidak ada hasil</div>
                  <Link
                    v-for="(prod, i) in results"
                    :key="prod.id || i"
                    :href="`/products/${prod.slug}`"
                    class="flex items-center gap-3 py-2 px-2 rounded hover:bg-gray-50 transition"
                  >
                    <img :src="prod.image_url || '/images/placeholder.png'" :alt="prod.name" class="w-10 h-10 object-cover rounded" />
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium truncate">{{ prod.name }}</p>
                      <p class="text-xs text-gray-500 truncate">{{ formatPrice(prod.price) }}</p>
                    </div>
                    <span :class="[
                      'px-2 py-0.5 rounded-full text-[10px] whitespace-nowrap',
                      (prod.stock ?? 0) <= 0 ? 'bg-red-100 text-red-700' : ((prod.stock ?? 0) <= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')
                    ]">Stok {{ prod.stock ?? 0 }}</span>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b
      </div>

      <!-- Kanan: Keranjang + Login/Profile -->
      <div class="flex items-center space-x-10">
        <!-- Compare Produk -->
<<<<<<< HEAD
        <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
          <img src="/images/compare-icon.png" alt="Compare Produk" class="h-5 w-6">
        </div>

        <!-- Keranjang -->
        <div class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition">
          <img src="/images/cart-icon.png" alt="Keranjang" class="h-5 w-5">
        </div>
=======
        <Link
          href="/compare-products"
          class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition"
        >
          <img src="/images/compare-icon.png" alt="Compare Produk" class="h-5 w-6">
        </Link>

        <!-- Keranjang -->
        <Link
          href="/cart"
          class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition"
        >
          <img src="/images/cart-icon.png" alt="Keranjang" class="h-5 w-5">
        </Link>
>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b

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
<<<<<<< HEAD
=======
const searchOpen = ref(false)
const searchQuery = ref('')
const results = ref([])
const searchLoading = ref(false)
const searchError = ref(null)
let searchTimer = null
>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

<<<<<<< HEAD
=======
const openResults = () => { searchOpen.value = true }

const formatPrice = (value) => {
  if (value == null) return '-'
  try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value) } catch { return value }
}

const runSearch = async () => {
  searchError.value = null
  const q = searchQuery.value.trim()
  if (!q) { results.value = []; return }
  searchLoading.value = true
  try {
    const res = await fetch(`/api/products?q=${encodeURIComponent(q)}`)
    if (!res.ok) throw new Error(`Server ${res.status}`)
    const data = await res.json()
    results.value = Array.isArray(data) ? data.slice(0, 15) : [] // limit results
  } catch (e) {
    searchError.value = e.message || String(e)
    results.value = []
  } finally {
    searchLoading.value = false
  }
}

const debouncedSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(runSearch, 350)
}

>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b
const handleClickOutside = (e) => {
  if (!e.target.closest('.relative')) {
    showDropdown.value = false
  }
<<<<<<< HEAD
=======
  if (!e.target.closest('.header-search')) {
    searchOpen.value = false
  }
>>>>>>> deeab084d5997b89c4ed55a325bea112aeb0f33b
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
