<script setup>
import HeaderLayout from '../components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed, watch, onMounted } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'

// mobile filter collapsible state
const isMobileFilterOpen = ref(false)

// kategori aktif (default)
const selectedCategory = ref('Elektronik Rumah Tangga')

// subcategories will be loaded from the backend (contain id, name)
const subcategories = ref([])

// category slug for API queries
const selectedCategorySlug = ref('')

// fallback mapping (used if backend not available)
const subcategoriesByCategory = {
  'Elektronik Rumah Tangga': ['Kipas', 'Setrika', 'Raket Nyamuk', 'Hair Dryer', 'Antena', 'STB', 'Speaker'],
  'Elektronik Dapur': ['Magic Com', 'Blender', 'Mixer', 'Kompor', 'Mug Listrik'],
  'Kelistrikan': ['Kabel', 'Klem Kabel', 'Tali Ties', 'Stopkontak', 'Saklar', 'Kalkulator', 'Baterai', 'Lampu', 'Lampu Tidur', 'Senter'],
}

// display list used by template (array of objects with id and name)
const filteredSubcategories = computed(() => subcategories.value.length > 0 ? subcategories.value : (subcategoriesByCategory[selectedCategory.value] || []).map((n, i) => ({ id: `fallback-${i}`, name: n })))

// selected subcategories (multi-select)
const selectedSubcategories = ref([])

// products loaded for the selected subcategories
const products = ref([])
const loading = ref(false)
const error = ref(null)

const formatPrice = (value) => {
  if (value == null) return '-'
  try {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value)
  } catch (e) {
    return value
  }
}

// fetch products from backend API. Expects an endpoint like: /api/products?category=...&subcategories=k1,k2
const fetchProducts = async () => {
  error.value = null
  // if no subcategories selected, clear products and skip
  if (!selectedSubcategories.value || selectedSubcategories.value.length === 0 || !selectedCategorySlug.value) {
    products.value = []
    return
  }

  loading.value = true
  try {
    const subQuery = encodeURIComponent(selectedSubcategories.value.join(','))
    const catQuery = encodeURIComponent(selectedCategorySlug.value || selectedCategory.value)
    const res = await fetch(`/api/products?category=${catQuery}&subcategories=${subQuery}`)
    if (!res.ok) throw new Error(`Server returned ${res.status}`)
    const data = await res.json()
    // Expect data to be an array of product objects matching App\\Models\\Product
    products.value = Array.isArray(data) ? data : []
  } catch (err) {
    console.error('Failed to fetch products', err)
    error.value = err.message || String(err)
    products.value = []
  } finally {
    loading.value = false
  }
}

// watch for changes to selected subcategories and reload products
watch(selectedSubcategories, () => {
  fetchProducts()
}, { deep: true })

const fetchSubcategories = async (kategori) => {
  try {
    const q = encodeURIComponent(kategori)
    const res = await fetch(`/api/subkategori?category=${q}`)
    if (!res.ok) throw new Error(`Server returned ${res.status}`)
    const data = await res.json()
    // Expect array of {id, name}
    subcategories.value = Array.isArray(data) ? data.map(s => ({ id: s.id, name: s.name })) : []
    return subcategories.value
  } catch (err) {
    console.warn('Failed to load subcategories from API, falling back to local map', err)
    subcategories.value = []
    return []
  }
}

const selectCategory = async (kategori) => {
  // change category and clear subcategory selections
  // if passing slug or name, derive slug/label from shared categories (if available)
  const page = usePage()
  const categories = page.props.categories ?? []
  const catObj = categories.find(c => c.name === kategori || c.slug === kategori)
  if (catObj) {
    selectedCategory.value = catObj.name
    selectedCategorySlug.value = catObj.slug
  } else {
    selectedCategory.value = kategori
    selectedCategorySlug.value = kategori
  }
  selectedSubcategories.value = []
  // load subcategories for this category
  const subs = await fetchSubcategories(selectedCategorySlug.value || selectedCategory.value)
  // If backend returned real subcategories, auto-select them so clicking category shows all products in that category
  if (subs && subs.length > 0) {
    selectedSubcategories.value = subs.map(s => s.id)
    // fetchProducts will run via the watcher, but call it explicitly to be immediate
    fetchProducts()
  }
}

const toggleSubcategory = (subId) => {
  const idx = selectedSubcategories.value.indexOf(subId)
  if (idx === -1) {
    selectedSubcategories.value.push(subId)
  } else {
    selectedSubcategories.value.splice(idx, 1)
  }
}

// update URL query string to reflect selected category/subcategories
const updateUrlFromState = () => {
  const slug = selectedCategorySlug.value || selectedCategory.value
  const base = `/shop/${slug}`
  const ids = (selectedSubcategories.value || []).map(String).filter(Boolean)
  const query = ids.length > 0 ? `?subcategories=${encodeURIComponent(ids.join(','))}` : ''
  const newUrl = `${base}${query}`
  if (window.location.pathname + window.location.search !== newUrl) {
    window.history.replaceState({}, '', newUrl)
  }
}

watch([selectedCategorySlug, selectedSubcategories], () => {
  updateUrlFromState()
}, { deep: true })

const isSubSelected = (subId) => selectedSubcategories.value.includes(subId)

const resetFilter = () => {
  selectedCategory.value = 'Elektronik Rumah Tangga'
  selectedSubcategories.value = []
}

onMounted(async () => {
  // Attempt to initialise category and subcategory selection from URL (path + query)
  const page = usePage()
  const categories = page.props.categories ?? []

  // parse path to get category slug (path: /shop/{categorySlug})
  const pathParts = window.location.pathname.split('/').filter(Boolean)
  const maybeShopSlug = (pathParts[0] === 'shop' && pathParts[1]) ? pathParts[1] : null

  if (maybeShopSlug) {
    const cat = categories.find(c => String(c.slug) === String(maybeShopSlug))
    if (cat) {
      selectedCategory.value = cat.name
      selectedCategorySlug.value = cat.slug
    } else {
      selectedCategory.value = decodeURIComponent(maybeShopSlug)
      selectedCategorySlug.value = maybeShopSlug
    }
  }

  // parse subcategories query string. Two formats supported:
  // - subcategories=1,2,3 (IDs)
  // - sub=slug-of-subcategory (legacy from header links)
  const urlParams = new URLSearchParams(window.location.search)
  const subIds = urlParams.get('subcategories')
  const subSlug = urlParams.get('sub')

  if (subIds) {
    const ids = subIds.split(',').map(s => s.trim()).filter(Boolean)
    if (ids.length > 0) {
      // ensure subcategories list loaded
      await fetchSubcategories(selectedCategorySlug.value || selectedCategory.value)
      selectedSubcategories.value = ids
      await fetchProducts()
      return
    }
  }

  if (subSlug) {
    // find subcategory id from shared categories list
    const found = categories.flatMap(c => (c.subkategories ?? []).map(s => ({ id: s.id, slug: s.slug, catSlug: c.slug }))).find(s => String(s.slug) === String(subSlug))
    if (found) {
      // ensure selected category matches the found one
      const cat = categories.find(c => String(c.slug) === String(found.catSlug))
      if (cat) {
        selectedCategory.value = cat.name
        selectedCategorySlug.value = cat.slug
      }
      selectedSubcategories.value = [String(found.id)]
      await fetchSubcategories(selectedCategorySlug.value || selectedCategory.value)
      await fetchProducts()
      return
    }
  }

  // default behaviour if no URL params: load default category
  await selectCategory(selectedCategory.value)
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <HeaderLayout />

    <main
      class="flex flex-1 px-4 sm:px-6 md:px-10 lg:px-12 py-8 gap-6 lg:gap-10 pt-24 flex-col lg:flex-row"
    >
      <!-- =======================
           SIDEBAR FILTER
      ======================== -->
      <aside
        class="w-full lg:w-[15%] space-y-6 lg:sticky lg:top-32 lg:self-start 
               bg-white lg:bg-transparent p-2 lg:p-2 rounded-lg shadow lg:shadow-none"
      >
        <!-- Header filter (mobile collapsible) -->
        <div
          class="flex justify-between items-center lg:hidden cursor-pointer"
          @click="isMobileFilterOpen = !isMobileFilterOpen"
        >
          <h2 class="font-semibold text-lg">Filter</h2>

          <svg
            :class="[
              'w-6 h-6 transition-transform',
              isMobileFilterOpen ? 'rotate-180' : 'rotate-0'
            ]"
            fill="none" stroke="currentColor" viewBox="0 0 24 24"
          >
            <path stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>

  <div
        v-if="selectedCategory"
    class="mb-2 text-sm text-[#183045] font-semibold lg:hidden"
  >
    <span
      class="underline cursor-pointer"
      @click="selectCategory(null)"
    >
      {{ selectedCategory }}
    </span>
  </div>

        <div
          :class="[
            'transition-all overflow-hidden lg:overflow-visible lg:max-h-none',
            isMobileFilterOpen ? 'max-h-screen mt-4' : 'max-h-0 lg:max-h-none'
          ]"
        >
          <!-- Kategori -->
          <div class="mt-3 lg:mt-0">
            <h3 class="font-medium mb-2">Kategori</h3>
            <ul class="space-y-1 text-gray-700">
              <li>
                <button
                  @click="selectCategory('Elektronik Rumah Tangga')"
                  class="max-w-[200px] text-left"
                  :class="selectedCategory === 'Elektronik Rumah Tangga'
                    ? 'font-bold text-[#183045]'
                    : 'hover:underline'"
                >
                  Elektronik Rumah Tangga
                </button>
              </li>
              <li>
                <button
                  @click="selectCategory('Elektronik Dapur')"
                  class="max-w-[200px] text-left"
                  :class="selectedCategory === 'Elektronik Dapur'
                    ? 'font-bold text-[#183045]'
                    : 'hover:underline'"
                >
                  Elektronik Dapur
                </button>
              </li>
              <li>
                <button
                  @click="selectCategory('Kelistrikan')"
                  :class="selectedCategory === 'Kelistrikan'
                    ? 'font-bold text-[#183045]'
                    : 'hover:underline'"
                >
                  Kelistrikan
                </button>
              </li>
            </ul>
          </div>

          <!-- Subkategori -->
          <div class="mt-6">
            <h3 class="font-medium mb-2">Subkategori</h3>
            <ul class="space-y-1 text-gray-700">
              <li v-for="(sub, index) in filteredSubcategories" :key="sub.id">
                <button
                  @click="toggleSubcategory(sub.id)"
                  :class="isSubSelected(sub.id)
                    ? 'font-bold text-[#183045]'
                    : 'hover:underline'"
                >
                  {{ sub.name }}
                </button>
              </li>
            </ul>
          </div>
        </div>
      </aside>

      <!-- PRODUK TETAP -->
      <section class="flex-1">
  <div v-if="loading" class="flex items-center justify-center h-56 text-gray-500">
    Memuat produk...
  </div>

  <div v-else-if="error" class="flex items-center justify-center h-56 text-red-500">
    Gagal memuat produk: {{ error }}
  </div>

  <div
    v-else-if="products.length > 0"
    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6"
  >
    <div
      v-for="(product, index) in products"
      :key="product.id || index"
      class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition"
    >
      <Link :href="`/products/${product.slug}`" class="block">
        <img
          :src="product.image_url || '/images/placeholder.png'"
          :alt="product.name || 'Product'"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h4 class="font-medium">{{ product.name }}</h4>
          <p class="text-gray-600">{{ formatPrice(product.price) }}</p>
          <p class="mt-1">
            <span
              :class="[
                'px-2 py-0.5 rounded-full text-xs',
                product.stock <= 0 ? 'bg-red-100 text-red-700' :
                product.stock <= 10 ? 'bg-yellow-100 text-yellow-700' :
                'bg-green-100 text-green-700'
              ]"
            >
              Stok: {{ product.stock ?? 0 }}
            </span>
          </p>
        </div>
      </Link>
    </div>
  </div>

  <div v-else class="flex items-center justify-center h-56 text-gray-500">
    Pilih subkategori untuk melihat produk.
  </div>
</section>

    </main>

    <FooterLayout />
  </div>
</template>