<template>
  <main class="pt-20">
    <section class="bg-[#123458] text-white flex flex-col md:flex-row items-center justify-between px-12 py-16">
      <!-- Teks -->
      <div class="max-w-lg space-y-6 -mt-6">
        <div class="mb-20 ml-12">
          <h1 class="text-4xl font-bold">Ella Elektrik</h1>
        </div>

        <div class="space-y-2 mb-20 ml-12">
          <p>Solusi Elektronik Terlengkap untuk Kebutuhan Anda</p>
          <p>Belanja Elektronik Mudah & Terpercaya</p>
          <p>Semua ada di Ella Elektrik</p>
        </div>

        <div class="ml-12">
          <Link
            :href="firstCategoryLink"
          >
          <button class="border border-white px-4 py-2 rounded hover:bg-white hover:text-[#183247] transition">
            Lihat Selengkapnya
          </button>
          </Link>
        </div>
      </div>

      <!-- Gambar produk -->
      <div class="-mt-10 mr-3">
        <img src="/images/produklogo.png" alt="Produk Ella Elektrik" class="w-[700px]" />
      </div>
    </section>

    <!-- KATEGORI PILIHAN -->
    <section class="bg-[#d8ccc0] py-12 px-8 text-center">
      <h2 class="text-2xl font-bold mb-8 text-left ml-14">Kategori Pilihan</h2>
      <div class="flex flex-wrap justify-center gap-10">
        <!-- Dynamic categories from database -->
        <template v-if="categoriesLoading">
          <div class="text-gray-600">Memuat kategori...</div>
        </template>

        <template v-else-if="categoriesError">
          <div class="text-red-600">Gagal memuat kategori</div>
        </template>

        <template v-else-if="categories.length > 0">
          <Link
            v-for="category in categories"
            :key="category.id"
            :href="`/shop/${category.slug}`"
            class="bg-gray-200 shadow-md hover:shadow-lg rounded-xl w-60 h-60 flex flex-col items-center justify-center transition"
          >
            <img 
              :src="getCategoryImage(category.slug)" 
              :alt="category.name" 
              class="w-50 h-40 mb-4" 
            />
            <span class="font-medium text-lg">{{ category.name }}</span>
          </Link>
        </template>

        <template v-else>
          <div class="text-gray-600">Tidak ada kategori tersedia</div>
        </template>
      </div>
    </section>
  </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'

const categories = ref([])
const categoriesLoading = ref(false)
const categoriesError = ref(null)

// Mapping slug ke image
const categoryImageMap = {
  'elektronik-rumah-tangga': '/images/elektronikrumahtangga.png',
  'elektronik-dapur': '/images/elektronikdapur.png',
  'kelistrikan': '/images/kelistrikan.png',
}

const getCategoryImage = (slug) => {
  return categoryImageMap[slug] || '/images/placeholder.png'
}

const firstCategoryLink = computed(() => {
  return categories.value.length > 0 ? `/shop/${categories.value[0].slug}` : '/shop'
})

const fetchCategories = async () => {
  categoriesLoading.value = true
  categoriesError.value = null
  try {
    const res = await fetch('/api/kategori')
    if (!res.ok) throw new Error('Gagal memuat kategori')
    const data = await res.json()
    categories.value = Array.isArray(data) ? data : []
  } catch (err) {
    console.error('fetchCategories error:', err)
    categoriesError.value = err.message
    categories.value = []
  } finally {
    categoriesLoading.value = false
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

<style scoped>
/* bisa tambahkan efek tambahan di sini nanti kalau mau animasi hover */
</style>
