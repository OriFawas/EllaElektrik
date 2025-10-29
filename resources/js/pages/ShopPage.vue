<script setup>
import HeaderLayout from '../components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed, watch, onMounted } from 'vue'

// kategori aktif (default)
const selectedCategory = ref('Elektronik Rumah Tangga')

// subcategories will be loaded from the backend (contain id, name)
const subcategories = ref([])

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
  if (!selectedSubcategories.value || selectedSubcategories.value.length === 0) {
    products.value = []
    return
  }

  loading.value = true
  try {
    const subQuery = encodeURIComponent(selectedSubcategories.value.join(','))
    const catQuery = encodeURIComponent(selectedCategory.value)
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
  selectedCategory.value = kategori
  selectedSubcategories.value = []
  // load subcategories for this category
  const subs = await fetchSubcategories(kategori)
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

const isSubSelected = (subId) => selectedSubcategories.value.includes(subId)

const resetFilter = () => {
  selectedCategory.value = 'Elektronik Rumah Tangga'
  selectedSubcategories.value = []
}

onMounted(async () => {
  // initial load for default category and show all its products
  await selectCategory(selectedCategory.value)
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <HeaderLayout />

    <!-- Main Content -->
    <main class="flex flex-1 px-12 py-8 gap-10 pt-29">
      <!-- Sidebar Filter -->
      <aside class="w-1/4 space-y-6">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-lg mb-3">Filter</h2>
        </div>

        <div>
          <h3 class="font-medium mb-2">Kategori</h3>
          <ul class="space-y-1 text-gray-700">
            <li>
              <button
                @click="selectCategory('Elektronik Rumah Tangga')"
                :class="selectedCategory === 'Elektronik Rumah Tangga' ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                Elektronik Rumah Tangga
              </button>
            </li>
            <li>
              <button
                @click="selectCategory('Elektronik Dapur')"
                :class="selectedCategory === 'Elektronik Dapur' ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                Elektronik Dapur
              </button>
            </li>
            <li>
              <button
                @click="selectCategory('Kelistrikan')"
                :class="selectedCategory === 'Kelistrikan' ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                Kelistrikan
              </button>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="font-medium mb-2">Subkategori</h3>
          <ul class="space-y-1 text-gray-700">
            <li v-for="(sub, index) in filteredSubcategories" :key="sub.id">
              <button
                @click="toggleSubcategory(sub.id)"
                :aria-pressed="isSubSelected(sub.id)"
                :class="isSubSelected(sub.id) ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                {{ sub.name }}
              </button>
            </li>
          </ul>
        </div>
      </aside>

      <!-- Produk Grid (placeholder cards removed) -->
      <section class="flex-1">
        <!-- Product grid: render product cards loaded from API. If none selected, show hint. -->
        <div v-if="loading" class="flex items-center justify-center h-56 text-gray-500">Memuat produk...</div>

        <div v-else-if="error" class="flex items-center justify-center h-56 text-red-500">Gagal memuat produk: {{ error }}</div>

        <div v-else-if="products.length > 0" class="grid grid-cols-5 gap-6">
          <div
            v-for="(product, index) in products"
            :key="product.id || index"
            class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition"
          >
            <img
              :src="product.image_url || '/images/placeholder.png'"
              :alt="product.name || 'Product'"
              class="w-full h-40 object-cover"
            />
            <div class="p-4">
              <h4 class="font-medium">{{ product.name }}</h4>
              <p class="text-gray-600">{{ formatPrice(product.price) }}</p>
            </div>
          </div>
        </div>

        <div v-else class="flex items-center justify-center h-56 text-gray-500">
          Pilih subkategori untuk melihat produk.
        </div>
      </section>
    </main>

    <!-- Footer -->
    <FooterLayout />
  </div>
</template>
