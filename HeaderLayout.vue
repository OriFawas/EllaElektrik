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
          <!-- Kategori mega-menu (Tokopedia-like) -->
          <div class="relative kategori-menu">
            <span
              class="flex items-center gap-2 hover:text-gray-300 transition font-medium focus:outline-none cursor-pointer select-none"
              aria-haspopup="true"
              :aria-expanded="String(showKategori)"
              @mouseenter="onKategoriEnter"
              @mouseleave="onKategoriLeave"
            >
              Kategori
            </span>

            <!-- Overlay: hanya di bawah navbar, tidak menutupi header -->
            <div v-if="showKategori" class="fixed left-0 right-0 top-[72px] bottom-0 bg-black/40 z-40"></div>

            <!-- Mega menu: penuh lebar, satu warna, kategori & subkategori dipisah garis -->
            <div
              v-if="showKategori"
              class="fixed left-0 right-0 top-[72px] bg-[#183045] text-white shadow-lg z-50 border-t border-[#25406a]"
              style="min-height: 22rem;"
              @mouseleave="onKategoriLeave"
              @mouseenter="onKategoriEnter"
            >
              <div class="flex h-full">
                <!-- Left: category list -->
                <div class="w-64 border-r border-white/20 max-h-96 overflow-y-auto">
                  <ul>
                    <li
                      v-for="cat in categories"
                      :key="cat.id || cat.name"
                      @mouseenter="previewCategory(cat.id)"
                      class="px-4 py-3 cursor-pointer transition"
                      :class="[activeCategoryId === cat.id ? 'bg-[#1e293b]' : 'hover:bg-[#25406a]', 'text-white']"
                    >
                      <Link
                        :href="`/shop/${cat.slug}`"
                        class="block text-base transition font-medium"
                        :class="activeCategoryId === cat.id ? 'text-white font-bold' : 'text-white/90 hover:text-white'"
                      >
                        {{ cat.name }}
                      </Link>
                    </li>
                  </ul>
                </div>

                <!-- Right: subcategories and content -->
                <div class="flex-1 p-8">
                  <div class="grid grid-cols-3 gap-8">
                    <div class="col-span-3 mb-2 border-b border-white/20 pb-2">
                      <h3 class="text-xl font-semibold text-white">{{ activeCategoryName }}</h3>
                    </div>

                    <template v-if="subLoading">
                      <div class="col-span-3 text-sm text-gray-200">Memuat subkategori…</div>
                    </template>

                    <template v-else-if="subError">
                      <div class="col-span-3 text-sm text-red-200">Gagal memuat subkategori</div>
                    </template>

                    <template v-else>
                      <div v-if="subMap[activeCategoryId] && subMap[activeCategoryId].length === 0" class="col-span-3 text-sm text-gray-200">Tidak ada subkategori</div>
                      <div v-else v-for="(chunk, idx) in visibleSubColumns" :key="idx" class="space-y-2">
                        <ul class="space-y-1">
                          <li v-for="sub in chunk" :key="sub.id" class="transition-all">
                            <!-- PERBAIKAN: Menggunakan sub.id pada query parameter agar filter berfungsi -->
                            <Link
                              :href="`/shop/${activeCategorySlug}/?subcategory=${sub.id}`"
                              class="block text-base transition px-2 py-1 rounded"
                              :class="selectedSubId === sub.id ? 'font-bold text-white bg-[#25406a]' : 'text-white/80 hover:text-white hover:bg-[#25406a]/50'"
                              @mouseenter="selectedSubId = sub.id"
                              @mouseleave="selectedSubId = null"
                            >
                              {{ sub.name }}
                            </Link>
                          </li>
                        </ul>
                      </div>
                    </template>
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
// --- Mega-menu hover delay fix ---
let kategoriMenuTimer = null
const onKategoriEnter = () => {
  clearTimeout(kategoriMenuTimer)
  showKategori.value = true
}
const onKategoriLeave = () => {
  kategoriMenuTimer = setTimeout(() => {
    showKategori.value = false
  }, 120) // delay agar user bisa pindah ke menu
}
import { Link, router } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, computed } from 'vue'

const showDropdown = ref(false)
const showKategori = ref(false)
const categories = ref([])
const categoriesLoading = ref(false)
const categoriesError = ref(null)
const searchOpen = ref(false)
const searchQuery = ref('')
const results = ref([])
const searchLoading = ref(false)
const searchError = ref(null)
let searchTimer = null
const cartCount = ref(0)

// Mega-menu state
const activeCategoryId = ref(null)
const activeCategorySlug = ref('')
const activeCategoryName = ref('')
const subMap = ref({}) // { [categoryId]: [subcategories] }
const subLoading = ref(false)
const subError = ref(null)
const selectedSubId = ref(null)

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value
}

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
  // close profile dropdown if clicked outside any .relative wrapper used for it
  if (!e.target.closest('.relative')) {
    showDropdown.value = false
  }
  // close kategori dropdown if clicked outside its wrapper
  if (!e.target.closest('.kategori-menu')) {
    showKategori.value = false
  }
  if (!e.target.closest('.header-search')) {
    searchOpen.value = false
  }
}

const previewCategory = async (catId) => {
  if (!catId) return
  activeCategoryId.value = catId
  const cat = categories.value.find(c => String(c.id) === String(catId))
  if (cat) {
    activeCategorySlug.value = cat.slug ?? slugify(cat.name)
    activeCategoryName.value = cat.name
  } else {
    activeCategorySlug.value = ''
    activeCategoryName.value = ''
  }

  await fetchSubcategoriesForCategory(catId)
}

const visibleSubColumns = computed(() => {
  const subs = subMap.value[activeCategoryId.value] || []
  if (!subs || subs.length === 0) return []
  // split into 3 columns (adjust as needed)
  const cols = 3
  const per = Math.ceil(subs.length / cols)
  const chunks = []
  for (let i = 0; i < cols; i++) {
    const start = i * per
    const end = start + per
    const slice = subs.slice(start, end)
    if (slice.length) chunks.push(slice)
  }
  return chunks
})

const fetchCategories = async () => {
  categoriesLoading.value = true
  categoriesError.value = null
  try {
    // Try common API endpoints; adapt if your backend uses a different path
    let res = await fetch('/api/kategori')
    if (!res.ok) {
      res = await fetch('/api/categories')
    }
    if (!res.ok) throw new Error('Tidak ada endpoint kategori')
    const data = await res.json()
    // Expecting an array like [{id, name, slug}, ...]
    categories.value = Array.isArray(data) ? data : []
    // If backend returns object with data property
    if (!categories.value.length && data?.data) categories.value = data.data
  } catch (err) {
    console.warn('fetchCategories:', err)
    categoriesError.value = err.message || String(err)
    // Fallback: provide some default categories so UI is not empty
    categories.value = [
      { id: 'elektronik-rumah-tangga', name: 'Elektronik Rumah Tangga', slug: 'elektronik-rumah-tangga' },
      { id: 'elektronik-dapur', name: 'Elektronik Dapur', slug: 'elektronik-dapur' },
      { id: 'kelistrikan', name: 'Kelistrikan', slug: 'kelistrikan' },
    ]
  } finally {
    categoriesLoading.value = false
  }
}

const fetchSubcategoriesForCategory = async (catId) => {
  if (!catId) return
  // if cached, nothing to do
  if (subMap.value[catId]) return

  subLoading.value = true
  subError.value = null
  try {
    const res = await fetch(`/api/subkategori?category_id=${encodeURIComponent(catId)}`)
    if (!res.ok) throw new Error(`Server ${res.status}`)
    const data = await res.json()
    subMap.value[catId] = Array.isArray(data) ? data : (data?.data || [])
  } catch (err) {
    console.warn('fetchSubcategoriesForCategory', err)
    subError.value = err.message || String(err)
    subMap.value[catId] = []
  } finally {
    subLoading.value = false
  }
}

// simple slugify fallback used only when category.slug is missing
const slugify = (str) => {
  if (!str) return ''
  return String(str)
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
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

onMounted(async () => {
  await fetchCategories()
  if (categories.value && categories.value.length > 0) {
    // set first as active preview
    previewCategory(categories.value[0].id)
  }
})

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside)
  clearTimeout(kategoriMenuTimer)
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