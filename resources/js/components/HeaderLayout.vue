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
          <!-- Kategori dropdown -->
          <div
            class="relative category-dropdown"
            @mouseenter="openCategoryDropdown"
            @mouseleave="startCloseCategoryTimer"
            @focusin="openCategoryDropdown"
            @focusout="closeCategoryDropdown"
          >
            <button
              class="hover:text-gray-300 transition flex items-center gap-2 font-medium"
              @keydown.esc="closeCategoryDropdown"
              aria-haspopup="true"
              :aria-expanded="categoryOpen"
            >
              Kategori
              <i class="fas fa-chevron-down text-sm"></i>
            </button>

            <!-- Dropdown panel -->
            <div
              v-if="categoryOpen"
              class="absolute left-0 top-full mt-1 w-[62rem] bg-white text-gray-800 rounded-md shadow-xl overflow-hidden z-50"
              role="menu"
              @mouseenter="openCategoryDropdown"
              @mouseleave="startCloseCategoryTimer"
            >
              <!-- Header -->
              <div class="border-b border-gray-100 px-4 py-3 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Kategori</h3>
              </div>

              <div class="p-4 flex gap-6">
                <!-- Left: category list (compact) -->
                <ul class="w-1/4 max-h-[60vh] overflow-y-auto divide-y divide-gray-100 pr-2">
                  <li
                    v-for="cat in categories"
                    :key="cat.id"
                    @mouseenter="setActiveCategory(cat.id)"
                    class="py-2 px-2 cursor-pointer"
                  >
                    <Link
                      :href="`/shop/${cat.slug}`"
                      class="flex items-center gap-2 text-sm"
                      @focus="setActiveCategory(cat.id)">
                      <span :class="[ 'w-1 h-5 inline-block mr-2', activeCategoryId == cat.id ? 'bg-[#183045]' : 'bg-transparent' ]" />
                      <span :class="[ activeCategoryId == cat.id ? 'font-semibold text-[#183045]' : 'text-gray-700' ]">{{ cat.name }}</span>
                    </Link>
                  </li>
                </ul>

                <!-- Right: subcategories for active category (split into columns) -->
                <div class="flex-1">
                  <div v-if="activeCategory" class="grid grid-cols-3 gap-6">
                    <div class="col-span-3 flex items-start justify-between">
                      <div class="text-lg font-medium text-gray-900">{{ activeCategory.name }}</div>
                    </div>

                    <div v-for="col in splitSubcategories(activeCategory.subkategories, 3)" :key="JSON.stringify(col)">
                      <ul class="space-y-2">
                        <li v-for="sub in col" :key="sub.id" class="py-0">
                          <Link
                            :href="`/shop/${activeCategory.slug}?subcategories=${sub.id}`"
                            class="text-sm hover:text-blue-600 block overflow-hidden truncate"
                          >
                            {{ sub.name }}
                          </Link>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
      </div>

      <!-- Kanan: Keranjang + Login/Profile -->
      <div class="flex items-center space-x-10">
        <!-- Compare Produk -->
        <Link
          href="/compare-products"
          class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer transition"
        >
          <img src="/images/compare-icon.png" alt="Compare Produk" class="h-5 w-6">
        </Link>

        <!-- Keranjang -->
        <Link
          href="/cart"
          class="flex items-center space-x-2 hover:text-gray-300 cursor-pointer relative"
        >
          <img src="/images/cart-icon.png" alt="Keranjang" class="h-5 w-5">
          <span
          v-if="cartCount > 0"
          class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
        >
          {{ cartCount }}
        </span>
        </Link>

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
                href="/user/dashboard"
                class="block px-4 py-2 hover:bg-gray-100 transition"
              >
                Dashboard
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
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, computed, watch } from 'vue'

const showDropdown = ref(false)
const searchOpen = ref(false)
const searchQuery = ref('')
const results = ref([])
const searchLoading = ref(false)
const searchError = ref(null)
let searchTimer = null
const cartCount = ref(0)
const categoryOpen = ref(false)
const categories = ref([])
const activeCategoryId = ref(null)

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

let categoryCloseTimer = null
const openCategoryDropdown = () => {
  clearTimeout(categoryCloseTimer)
  categoryOpen.value = true
}
const startCloseCategoryTimer = () => {
  clearTimeout(categoryCloseTimer)
  categoryCloseTimer = setTimeout(() => {
    categoryOpen.value = false
    activeCategoryId.value = null
  }, 220)
}
const closeCategoryDropdown = () => {
  clearTimeout(categoryCloseTimer)
  categoryOpen.value = false
  activeCategoryId.value = null
}
const setActiveCategory = (id) => { activeCategoryId.value = id }

// Splits an array into N roughly equal columns for display
const splitSubcategories = (subs = [], cols = 3) => {
  if (!Array.isArray(subs) || subs.length === 0) return Array.from({ length: cols }, () => [])
  const perCol = Math.ceil(subs.length / cols)
  const groups = []
  for (let i = 0; i < cols; i++) {
    groups.push(subs.slice(i * perCol, i * perCol + perCol))
  }
  return groups
}

const activeCategory = computed(() => categories.value.find(c => String(c.id) === String(activeCategoryId.value)) || categories.value[0] || null)

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

const handleClickOutside = (e) => {
  // Profile dropdown: hide when clicking outside its relative container
  if (!e.target.closest('.relative')) {
    showDropdown.value = false
  }

  if (!e.target.closest('.header-search')) {
    searchOpen.value = false
  }

  // Category dropdown: hide when clicked outside the category dropdown block
  if (!e.target.closest('.category-dropdown') && !e.target.closest('[aria-haspopup="true"]')) {
    categoryOpen.value = false
    activeCategoryId.value = null
  }
}

const fetchCartCount = async () => {
  try {
    const res = await fetch('/api/cart')
    if (!res.ok) throw new Error('Gagal memuat keranjang')
    const data = await res.json()
    cartCount.value = data.item_count || 0
  } catch (err) {
    console.error(err)
    cartCount.value = 0
  }
}

onMounted(() => {
  fetchCartCount()
})

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))

// Load categories from Inertia shared props (if available) or fallback empty
const page = usePage()
categories.value = page.props.categories ?? []
watch(() => page.props.categories, (newVal) => {
  categories.value = newVal ?? []
})

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
