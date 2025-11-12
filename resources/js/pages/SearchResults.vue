<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <HeaderLayout />

    <main class="container mx-auto px-8 pt-28 pb-16">
      <!-- Search Bar -->
      <form @submit.prevent="onSubmit" class="mb-8">
        <div class="flex items-center gap-3">
          <input
            v-model="query"
            type="text"
            placeholder="Cari produk..."
            class="flex-1 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#183045]"
          />
          <button
            type="submit"
            class="bg-[#183045] text-white px-5 py-2 rounded-md hover:opacity-90"
          >
            Cari
          </button>
        </div>
      </form>

      <!-- States -->
      <div v-if="loading" class="text-gray-500">Memuat hasil…</div>
      <div v-else-if="error" class="text-red-500">Gagal memuat: {{ error }}</div>
      <div v-else>
        <div v-if="!query.trim()" class="text-gray-500">Masukkan kata kunci untuk mencari produk.</div>
        <div v-else-if="products.length === 0" class="text-gray-500">Tidak ada produk yang cocok.</div>

        <!-- Results Grid -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <Link
            v-for="(product, idx) in products"
            :key="product.id || idx"
            :href="`/products/${product.slug}`"
            class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition block"
          >
            <img
              :src="product.image_url || '/images/placeholder.png'"
              :alt="product.name || 'Product'"
              class="w-full h-40 object-cover"
            />
            <div class="p-4">
              <h4 class="font-medium line-clamp-2">{{ product.name }}</h4>
              <p class="text-gray-600">{{ formatPrice(product.price) }}</p>
              <p class="mt-1">
                <span :class="[
                  'px-2 py-0.5 rounded-full text-xs',
                  (product.stock ?? 0) <= 0 ? 'bg-red-100 text-red-700' : ((product.stock ?? 0) <= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')
                ]">
                  Stok: {{ product.stock ?? 0 }}
                </span>
              </p>
            </div>
          </Link>
        </div>
      </div>
    </main>

    <FooterLayout />
  </div>
</template>

<script setup>
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { Link } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  initialQuery: { type: String, default: '' },
})

const query = ref(props.initialQuery || '')
const products = ref([])
const loading = ref(false)
const error = ref(null)

const formatPrice = (value) => {
  if (value == null) return '-'
  try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value) } catch { return value }
}

const fetchResults = async () => {
  error.value = null
  if (!query.value || !query.value.trim()) {
    products.value = []
    return
  }
  loading.value = true
  try {
    const q = encodeURIComponent(query.value.trim())
    const res = await fetch(`/api/products?q=${q}`)
    if (!res.ok) throw new Error(`Server returned ${res.status}`)
    const data = await res.json()
    products.value = Array.isArray(data) ? data : []
  } catch (e) {
    error.value = e.message || String(e)
    products.value = []
  } finally {
    loading.value = false
  }
}

const onSubmit = async () => {
  // update URL for shareability without full page reload
  const q = query.value.trim()
  const url = q ? `/search?q=${encodeURIComponent(q)}` : '/search'
  window.history.pushState({}, '', url)
  await fetchResults()
}

// Auto-search when arriving with a query
onMounted(() => { if (query.value) fetchResults() })

// Optional: live search as user types (debounced)
let t = null
watch(query, () => {
  clearTimeout(t)
  t = setTimeout(() => { fetchResults() }, 400)
})
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
</style>
