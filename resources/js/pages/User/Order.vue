<script setup>
import { Head } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, onMounted } from 'vue'

// Data pesanan (contoh statis, bisa diganti dari backend)
const orders = [
  {
    id: 1,
    name: 'Kipas Karakter',
    price: 'Rp 100.000',
    date: '10 November 2025',
    image: '/images/products/kipas.jpg',
  },
  {
    id: 2,
    name: 'Blender',
    price: 'Rp 150.000',
    date: '11 November 2025',
    image: '/images/products/blender.jpg',
  },
  {
    id: 3,
    name: 'Lampu Tidur',
    price: 'Rp 8.000',
    date: '12 November 2025',
    image: '/images/products/lampu.jpg',
  },
]
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">
  <HeaderLayout />
  <Head title="Riwayat Pesanan" />
  <UserLayout>
    <div class="bg-white p-8 rounded-lg shadow w-full">
      <h1 class="text-2xl font-semibold mb-6">Riwayat Pesanan</h1>

      <div v-if="orders.length" class="space-y-4">
        <div
          v-for="order in orders"
          :key="order.id"
          class="flex items-center justify-between border-b pb-4"
        >
          <div class="flex items-center space-x-4">
            <img
              :src="order.image"
              alt="Produk"
              class="w-20 h-20 object-cover rounded-md"
            />
            <div>
              <p class="text-lg font-medium">{{ order.name }}</p>
              <p class="text-gray-600 text-sm">{{ order.date }}</p>
            </div>
          </div>
          <p class="text-gray-800 font-semibold">{{ order.price }}</p>
        </div>
      </div>

      <div v-else class="text-center text-gray-500 py-10">
        Belum ada pesanan yang dilakukan.
      </div>
    </div>
  </UserLayout>
  <FooterLayout />
  </div>
</template>
