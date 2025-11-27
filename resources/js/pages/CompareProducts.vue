<script setup>
import { Head } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import HeaderLayout from '../components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'

// Props from Inertia
const props = defineProps({
  products: { type: Array, default: () => [] }
})

// State
const searchLeft = ref('')
const searchRight = ref('')
const selectedLeft = ref('')
const selectedRight = ref('')
const ignoreRestriction = ref(false)

const allProducts = computed(() => props.products || [])

const leftProduct = computed(() => {
  const id = parseInt(selectedLeft.value)
  return allProducts.value.find(p => p.id === id) || null
})
const rightProduct = computed(() => {
  const id = parseInt(selectedRight.value)
  return allProducts.value.find(p => p.id === id) || null
})

// Helpers
const byName = (q) => (p) => p.name?.toLowerCase().includes(q.trim().toLowerCase())
const sameSub = (target) => (p) => !target || p.subkategori_id === target.subkategori_id

// Filters with subcategory restriction (unless ignored)
const filteredLeftProducts = computed(() => {
  let list = allProducts.value
  if (!ignoreRestriction.value && rightProduct.value) {
    list = list.filter(sameSub(rightProduct.value))
  }
  return list.filter(byName(searchLeft.value))
})
const filteredRightProducts = computed(() => {
  let list = allProducts.value
  if (!ignoreRestriction.value && leftProduct.value) {
    list = list.filter(sameSub(leftProduct.value))
  }
  return list.filter(byName(searchRight.value))
})

const formatPrice = (value) => {
  if (value == null) return '-'
  try { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(Number(value)) } catch { return value }
}

onMounted(() => {
  const params = new URLSearchParams(window.location.search)
  const productId = params.get('product')

  if (!productId) return

  // Jika slot kiri kosong → isi kiri
  if (!selectedLeft.value) {
    selectedLeft.value = productId
    return
  }

  // Slot kiri sudah terisi → isi slot kanan
  if (!selectedRight.value) {
    selectedRight.value = productId
    return
  }

  // Kalau dua-duanya sudah terisi, biarkan saja (atau bisa dibuat override)
})

</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Head title="Perbandingan Produk - Ella Elektrik" />

    <!-- Header -->
    <HeaderLayout />

    <main class="container mx-auto px-6 py-10">
      <h1 class="text-2xl font-bold mb-6 text-center">Perbandingan Produk</h1>

      <!-- Controls -->
      <div class="flex items-center justify-end mb-4 gap-3">
        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
          <input type="checkbox" v-model="ignoreRestriction" class="rounded border-gray-300" />
          Abaikan batas subkategori
        </label>
      </div>

      <!-- Grid 2 kolom -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Kolom kiri -->
        <div class="bg-white shadow rounded-xl p-6">
          <label class="block font-semibold mb-2">Compare with</label>

          <input
            v-model="searchLeft"
            type="text"
            placeholder="Cari produk..."
            class="w-full border rounded-md p-2 mb-3"
          />

          <select
            v-model="selectedLeft"
            class="w-full border rounded-md p-2 mb-4"
          >
            <option value="">Pilih produk...</option>
            <option
              v-for="p in filteredLeftProducts"
              :key="p.id"
              :value="p.id"
            >
              {{ p.name }}
            </option>
          </select>

          <div v-if="leftProduct" class="text-center">
            <img
              :src="leftProduct.image"
              :alt="leftProduct.name"
              class="w-48 h-48 object-cover mx-auto rounded-lg"
            />
            <h2 class="mt-3 font-semibold">{{ leftProduct.name }}</h2>
            <p class="text-gray-500">{{ formatPrice(leftProduct.price) }}</p>

            <div class="mt-4 text-left">
              <h3 class="font-bold mb-2">Spesifikasi:</h3>
              <ul class="space-y-1">
                <li
                  v-for="(s, i) in leftProduct.specs"
                  :key="i"
                  class="text-sm text-gray-600"
                >
                  • {{ s }}
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Kolom kanan -->
        <div class="bg-white shadow rounded-xl p-6">
          <label class="block font-semibold mb-2">Compare with</label>

          <input
            v-model="searchRight"
            type="text"
            placeholder="Cari produk..."
            class="w-full border rounded-md p-2 mb-3"
          />

          <select
            v-model="selectedRight"
            class="w-full border rounded-md p-2 mb-4"
          >
            <option value="">Pilih produk...</option>
            <option
              v-for="p in filteredRightProducts"
              :key="p.id"
              :value="p.id"
            >
              {{ p.name }}
            </option>
          </select>

          <div v-if="rightProduct" class="text-center">
            <img
              :src="rightProduct.image"
              :alt="rightProduct.name"
              class="w-48 h-48 object-cover mx-auto rounded-lg"
            />
            <h2 class="mt-3 font-semibold">{{ rightProduct.name }}</h2>
            <p class="text-gray-500">{{ formatPrice(rightProduct.price) }}</p>

            <div class="mt-4 text-left">
              <h3 class="font-bold mb-2">Spesifikasi:</h3>
              <ul class="space-y-1">
                <li
                  v-for="(s, i) in rightProduct.specs"
                  :key="i"
                  class="text-sm text-gray-600"
                >
                  • {{ s }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <FooterLayout />
  </div>
</template>

