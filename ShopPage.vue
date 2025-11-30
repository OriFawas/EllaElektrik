<script setup>
import HeaderLayout from '../components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed, watch, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

// Get page props from Inertia
const page = usePage()

// Category state - now using ID instead of name
const categoryId = ref(page.props.categoryId ?? null)
const categoryName = ref(page.props.category ?? '')
const categorySlug = ref(page.props.categorySlug ?? '')

// Available categories list for filter
const allCategories = ref([])

// MULTI-SELECT: Track multiple selected categories
const selectedCategories = ref([])

// Get subcategory from query string
function getSubcategoryFromQuery() {
  if (typeof window === 'undefined') return null
  try {
    const params = new URLSearchParams(window.location.search)
    return params.get('subcategory')
  } catch {
    return null
  }
}

// Subcategories for selected categories (combined)
const subcategories = ref([])
const selectedSubcategories = ref([])
const selectedSubcategory = ref(getSubcategoryFromQuery())

// Products and loading states
const products = ref([])
const loading = ref(false)
const error = ref(null)
const categoriesLoading = ref(false)
const subcategoriesLoading = ref(false)

const formatPrice = (value) => {
  if (value == null) return '-'
  try {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value)
  } catch (e) {
    return value
  }
}

// Fetch all categories for filter
const fetchAllCategories = async () => {
  categoriesLoading.value = true
  try {
    const res = await fetch('/api/kategori')
    if (!res.ok) throw new Error('Gagal memuat kategori')
    const data = await res.json()
    allCategories.value = Array.isArray(data) ? data : []
  } catch (err) {
    console.error('Failed to fetch categories', err)
    allCategories.value = []
  } finally {
    categoriesLoading.value = false
  }
}

// Fetch subcategories for multiple categories and combine them
const fetchSubcategoriesForSelectedCategories = async (categoryIds) => {
  if (!categoryIds || categoryIds.length === 0) {
    subcategories.value = []
    return
  }
  
  subcategoriesLoading.value = true
  try {
    // Fetch subcategories for each category and combine (remove duplicates)
    const allSubs = []
    const subIds = new Set()
    
    for (const catId of categoryIds) {
      try {
        const res = await fetch(`/api/subkategori?category_id=${encodeURIComponent(catId)}`)
        if (!res.ok) {
          console.warn(`Failed to fetch subcategories for category ${catId}: ${res.status}`)
          continue
        }
        const data = await res.json()
        
        if (Array.isArray(data)) {
          data.forEach(sub => {
            if (!subIds.has(sub.id)) {
              subIds.add(sub.id)
              allSubs.push(sub)
            }
          })
        }
      } catch (err) {
        console.warn(`Failed to load subcategories for category ${catId}`, err)
      }
    }
    
    // Ensure subcategories are set
    subcategories.value = allSubs
    console.log(`Loaded ${allSubs.length} subcategories for categories:`, categoryIds)
  } catch (err) {
    console.error('Error in fetchSubcategoriesForSelectedCategories:', err)
    subcategories.value = []
  } finally {
    subcategoriesLoading.value = false
  }
}

// Fetch products based on selected subcategories (or all if none selected)
const fetchProducts = async () => {
  error.value = null
  
  // Cek apakah user memilih kategori atau tidak sama sekali
  const isFilterEmpty = selectedCategories.value.length === 0

  if (isFilterEmpty) {
    loading.value = true
    try {
      const res = await fetch(`/api/products`)
      if (!res.ok) throw new Error(`Server returned ${res.status}`)
      let data = await res.json()
      if (Array.isArray(data)) {
        products.value = data
      } else {
        products.value = []
      }
    } catch (err) {
      console.error('Failed to fetch all products', err)
      error.value = err.message || String(err)
      products.value = []
    } finally {
      loading.value = false
    }
    return
  }
  
  // Jika ada kategori yang dipilih, kita harus melakukan filter.
  let targetSubcategories = selectedSubcategories.value
  
  if (!targetSubcategories || targetSubcategories.length === 0) {
     targetSubcategories = subcategories.value.map(s => s.id)
  }
  
  // Jika setelah dicek ternyata tetap tidak ada subkategori, maka hasil produk kosong.
  if (targetSubcategories.length === 0) {
      products.value = []
      return
  }
  
  // Fetch produk berdasarkan subkategori
  loading.value = true
  try {
    const subQuery = encodeURIComponent(targetSubcategories.join(','))
    const res = await fetch(`/api/products?subcategories=${subQuery}`)
    if (!res.ok) throw new Error(`Server returned ${res.status}`)
    let data = await res.json()
    if (Array.isArray(data)) {
      products.value = data
    } else {
      products.value = []
    }
  } catch (err) {
    console.error('Failed to fetch products', err)
    error.value = err.message || String(err)
    products.value = []
  } finally {
    loading.value = false
  }
}

// Watch for changes to selectedSubcategories and reload products
watch(selectedSubcategories, () => {
  fetchProducts()
}, { deep: true })

// Watch for changes to selectedCategories and update subcategories
watch(selectedCategories, async (newCategories) => {
  console.log('Categories changed, fetching subcategories for:', newCategories.map(c => c.name))
  if (newCategories.length === 0) {
    subcategories.value = []
    selectedSubcategories.value = []
    selectedSubcategory.value = null
    await fetchProducts()
    return
  }
  
  try {
    await fetchSubcategoriesForSelectedCategories(newCategories.map(c => c.id))
    console.log('Subcategories fetched successfully:', subcategories.value.length)
    
    // Reset subcategory selection when categories change
    selectedSubcategories.value = []
    selectedSubcategory.value = null
  } catch (err) {
    console.error('Error fetching subcategories:', err)
    subcategories.value = []
    selectedSubcategories.value = []
  }
}, { deep: true })

// Handle category toggle (multi-select)
const toggleCategory = (category) => {
  const idx = selectedCategories.value.findIndex(c => c.id === category.id)
  if (idx > -1) {
    selectedCategories.value.splice(idx, 1)
  } else {
    selectedCategories.value.push(category)
  }
}

// Check if category is selected
const isCategorySelected = (categoryId) => {
  return selectedCategories.value.some(c => c.id === categoryId)
}

// Handle subcategory toggle (multi-select)
const toggleSubcategory = (subId) => {
  const idx = selectedSubcategories.value.indexOf(String(subId))
  if (idx > -1) {
    selectedSubcategories.value.splice(idx, 1)
  } else {
    selectedSubcategories.value.push(String(subId))
  }
}

// Check if subcategory is selected
const isSubSelected = (subId) => selectedSubcategories.value.includes(String(subId))

// Reset all filters - show all products
const resetFilter = async () => {
  selectedCategories.value = []
  selectedSubcategories.value = []
  selectedSubcategory.value = null
  subcategories.value = []
  await fetchProducts()
}

// Initialize on mount
onMounted(async () => {
  try {
    // Step 1: Fetch all categories
    await fetchAllCategories()
    console.log('Categories loaded:', allCategories.value.length)
    
    // Step 2: Determine which category to load
    let targetCategoryId = categoryId.value
    let targetCategory = null
    
    if (targetCategoryId) {
      targetCategory = allCategories.value.find(c => c.id === targetCategoryId)
    }
    
    if (!targetCategory && allCategories.value.length > 0) {
      // Fallback to first category
      targetCategory = allCategories.value[0]
      targetCategoryId = targetCategory.id
    }
    
    if (!targetCategory) {
      console.warn('No categories available')
      return
    }
    
    // Step 3: Set selected categories
    selectedCategories.value = [targetCategory]
    console.log('Selected category:', targetCategory.name)
    
    // Step 4: Fetch subcategories and WAIT for completion
    await fetchSubcategoriesForSelectedCategories([targetCategoryId])
    console.log('Subcategories loaded:', subcategories.value.length)
    
    // Step 5: Select subcategories after they are loaded
    if (subcategories.value.length > 0) {
      // Check if there's a specific subcategory in query string
      const subId = getSubcategoryFromQuery()
      if (subId) {
        // PERBAIKAN: Karena Header mengirim ID, subId disini adalah ID (string)
        selectedSubcategories.value = [String(subId)]
        console.log('Selected subcategory from query:', subId)
      } else {
        // Select all subcategories by default if no query
        selectedSubcategories.value = subcategories.value.map(s => String(s.id))
      }
    } else {
      console.warn('No subcategories available after fetch')
    }
    
    // Step 6: Fetch products
    await fetchProducts()
  } catch (err) {
    console.error('Error in onMounted:', err)
  }
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <HeaderLayout />

    <!-- Main Content -->
    <main class="flex flex-1 px-12 py-8 gap-10 pt-29">
      <!-- Sidebar Filter -->
      <aside class="w-1/4 space-y-6 sticky top-30 self-start">
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-lg mb-3">Filter</h2>
          <button
            v-if="selectedCategories.length > 0 || selectedSubcategories.length > 0"
            @click="resetFilter"
            class="text-xs text-blue-600 hover:text-blue-800 underline mb-3"
          >
            Reset
          </button>
        </div>

        <div>
          <h3 class="font-medium mb-2">Kategori</h3>
          <div v-if="categoriesLoading" class="text-gray-500 text-sm">Memuat kategori...</div>
          <ul v-else class="space-y-1 text-gray-700">
            <li v-for="cat in allCategories" :key="cat.id">
              <button
                @click="toggleCategory(cat)"
                :class="isCategorySelected(cat.id) ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                {{ cat.name }}
              </button>
            </li>
          </ul>
        </div>

        <div>
          <h3 class="font-medium mb-2">Subkategori</h3>
          <ul v-if="subcategories.length > 0" class="space-y-1 text-gray-700">
            <li v-for="sub in subcategories" :key="sub.id">
              <button
                @click="toggleSubcategory(sub.id)"
                :aria-pressed="isSubSelected(sub.id)"
                :class="isSubSelected(sub.id) ? 'font-bold text-[#183045]' : 'hover:underline'"
              >
                {{ sub.name }}
              </button>
            </li>
          </ul>
          <div v-else-if="!subcategoriesLoading" class="text-gray-500 text-sm">
            {{ selectedCategories.length === 0 ? 'Pilih kategori terlebih dahulu' : 'Tidak ada subkategori' }}
          </div>
          <div v-else class="text-gray-500 text-sm">Memuat subkategori...</div>
        </div>
      </aside>

      <!-- Produk Grid -->
      <section class="flex-1">
        <!-- Product grid -->
        <div v-if="loading" class="flex items-center justify-center h-56 text-gray-500">Memuat produk...</div>

        <div v-else-if="error" class="flex items-center justify-center h-56 text-red-500">Gagal memuat produk: {{ error }}</div>

        <div v-else-if="products.length > 0" class="grid grid-cols-5 gap-6">
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
                <!-- PERBAIKAN: Menambahkan class warna text-gray-900 dan font-bold agar nama produk muncul -->
                <h4 class="font-bold text-gray-900 text-lg mb-1 leading-tight line-clamp-2">{{ product.name }}</h4>
                <p class="text-gray-600 font-medium">{{ formatPrice(product.price) }}</p>
                <p class="mt-2">
                  <span :class="[
                    'px-2 py-0.5 rounded-full text-xs font-medium',
                    product.stock <= 0 ? 'bg-red-100 text-red-700' : (product.stock <= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')
                  ]">
                    Stok: {{ product.stock ?? 0 }}
                  </span>
                </p>
              </div>
            </Link>
          </div>
        </div>

        <div v-else class="flex items-center justify-center h-56 text-gray-500">
          Tidak ada produk yang sesuai dengan filter Anda.
        </div>
      </section>
    </main>

    <!-- Footer -->
    <FooterLayout />
  </div>
</template>