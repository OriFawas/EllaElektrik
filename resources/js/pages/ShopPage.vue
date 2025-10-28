<script setup>
import HeaderLayout from '../components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed } from 'vue'

// kategori aktif
const selectedCategory = ref('Elektronik Rumah Tangga')

// daftar produk berdasarkan kategori
const productsByCategory = {
  'Elektronik Rumah Tangga': ['Kipas', 'Setrika', 'Raket Nyamuk', 'Hair Dryer', 'Antena', 'STB', 'Speaker', 'Speaker', 'Speaker', 'Speaker', 'Speaker'],
  'Elektronik Dapur': ['Magic Com', 'Blender', 'Mixer', 'Kompor', 'Mug Listrik'],
  'Kelistrikan': ['Kabel', 'Lem Kabel', 'Tali Ties', 'Stop Kontak', 'Saklar', 'Lampu', 'Senter'],
}

// tampilkan produk sesuai kategori aktif
const filteredProducts = computed(() => productsByCategory[selectedCategory.value])

const selectCategory = (kategori) => {
  selectedCategory.value = kategori
}

const resetFilter = () => {
  selectedCategory.value = 'Elektronik Rumah Tangga'
}
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
          <h3 class="font-medium mb-2">Produk</h3>
          <ul class="space-y-1 text-gray-700">
            <li v-for="(product, index) in filteredProducts" :key="index">
              <button class="hover:underline">{{ product }}</button>
            </li>
          </ul>
        </div>
      </aside>

      <!-- Produk Grid -->
      <section class="flex-1 grid grid-cols-5 gap-6">
        <div
          v-for="(product, index) in filteredProducts"
          :key="index"
          class="bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition"
        >
          <img
            src="/images/placeholder.png"
            alt="Product"
            class="w-full h-40 object-cover"
          />
          <div class="p-4">
            <h4 class="font-medium">{{ product }}</h4>
            <p class="text-gray-600">Rp -</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <FooterLayout />
  </div>
</template>
