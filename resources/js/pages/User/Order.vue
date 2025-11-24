<script setup>
import { Head } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref } from 'vue'

// Pesanan Aktif (hanya status "Siap Diambil")
const activeOrders = [
  {
    id: 101,
    name: 'Kipas Karakter',
    price: 'Rp 100.000',
    date: '15 November 2025',
    image: '/images/products/kipas.jpg',
    status: 'Siap Diambil',
  },
  {
    id: 102,
    name: 'Blender',
    price: 'Rp 150.000',
    date: '15 November 2025',
    image: '/images/products/blender.jpg',
    status: 'Siap Diambil',
  },
]

// Riwayat pesanan selesai
const historyOrders = [
  {
    id: 1,
    name: 'Lampu Tidur',
    price: 'Rp 8.000',
    date: '12 November 2025',
    image: '/images/products/lampu.jpg',
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
    name: 'Kipas Karakter',
    price: 'Rp 100.000',
    date: '10 November 2025',
    image: '/images/products/kipas.jpg',
  },
]
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">
    <HeaderLayout />
    <Head title="Riwayat Pesanan" />

    <UserLayout>
      <div class="bg-white p-8 rounded-lg shadow w-full space-y-10">

        <!-- Bagian 1: Pesanan Aktif -->
        <div>
          <h1 class="text-2xl font-semibold mb-6">Pesanan Aktif</h1>

          <div v-if="activeOrders.length" class="space-y-4">
            <div
              v-for="order in activeOrders"
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

                  <!-- Status hanya Siap Diambil -->
                  <p class="text-green-600 text-sm font-semibold mt-1">
                    Siap Diambil
                  </p>
                </div>
              </div>

              <p class="text-gray-800 font-semibold">{{ order.price }}</p>
            </div>
          </div>

          <div v-else class="text-center text-gray-500 py-10">
            Tidak ada pesanan aktif.
          </div>
        </div>

        <!-- Bagian 2: Riwayat Pesanan -->
        <div>
          <h1 class="text-2xl font-semibold mb-6">Pesanan Selesai</h1>

          <div v-if="historyOrders.length" class="space-y-4">
            <div
              v-for="order in historyOrders"
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
            Belum ada riwayat pesanan.
          </div>
        </div>

      </div>
    </UserLayout>

    <FooterLayout />
  </div>
</template>
