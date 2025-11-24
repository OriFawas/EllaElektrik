<template>
  <AdminLayout>
    <Head title="Admin Pesanan - Ella Elektrik" />

    <main class="p-6 bg-gray-100 min-h-screen text-gray-900">
      <div class="max-w-7xl mx-auto space-y-12">

        <!-- 🟦 Pesanan User -->
        <section>
          <h2 class="text-2xl font-bold mb-4">Pesanan User</h2>
          <div v-if="filteredPesananUser.length" class="space-y-4">
            <div
              v-for="order in filteredPesananUser"
              :key="order.id"
              class="bg-white rounded-lg shadow p-4 flex items-center justify-between hover:shadow-lg transition-shadow duration-200"
            >
              <div class="flex items-center w-full justify-between">
                <div class="flex items-center gap-6">
                  <img src="/images/user-avatar.png" class="h-10 w-10 rounded-full" />
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-16 text-sm text-gray-700">
                  <span class="w-28">{{ order.createdAt }}</span>
                  <span class="w-40">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <div class="flex items-center gap-2">
                  <button
                    @click="completeOrder(order)"
                    title="Selesaikan Pesanan"
                    class="w-7 h-7 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 hover:scale-110 active:scale-95 transition-all duration-200"
                  >
                    <i class="fas fa-check text-xs"></i>
                  </button>

                  <button
                    @click="confirmDeleteOrder(order)"
                    title="Hapus Pesanan"
                    class="w-7 h-7 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 hover:scale-110 active:scale-95 transition-all duration-200"
                  >
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 italic">Tidak ada pesanan ditemukan.</p>
        </section>

        <!-- 🟨 Riwayat Pesanan -->
        <section>
          <h2 class="text-2xl font-bold mb-4">Riwayat Pesanan</h2>
          <div v-if="filteredRiwayat.length" class="space-y-4">
            <div
              v-for="order in filteredRiwayat"
              :key="order.id"
              class="bg-white rounded-lg shadow p-4 flex items-center justify-between hover:shadow-lg transition-shadow duration-200"
            >
              <div class="flex items-center w-full justify-between">
                <div class="flex items-center gap-6">
                  <img src="/images/user-avatar.png" class="h-10 w-10 rounded-full" />
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-16 text-sm text-gray-700">
                  <span class="w-28">{{ order.createdAt }}</span>
                  <span class="w-40">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <span
                  class="px-3 py-1 rounded-full text-sm font-semibold"
                  :class="order.status === 'Ditolak' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                >
                  {{ order.status }}
                </span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 italic">Tidak ada pesanan ditemukan.</p>
        </section>

      </div>
    </main>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'

interface Order {
  id: number
  nik: string
  nama: string
  products: string
  totalPrice: number
  createdAt: string
  status?: string
}

const pesananUser = ref<Order[]>([
  { id: 1, nik: '3201123456789001', nama: 'Budi', products: 'Kipas Angin', totalPrice: 150000, createdAt: '2025-10-28' },
  { id: 3, nik: '3201987654321002', nama: 'Zize', products: 'Lampu LED', totalPrice: 210000, createdAt: '2025-10-27' },
])

const riwayat = ref<Order[]>([
  { id: 2, nik: '3201123456789003', nama: 'Sari', products: 'TV LED', totalPrice: 3500000, createdAt: '2025-10-26', status: 'Selesai' },
  { id: 4, nik: '3201987654321004', nama: 'Agus', products: 'Mesin Cuci', totalPrice: 2500000, createdAt: '2025-10-25', status: 'Selesai' },
])

const searchQuery = ref('')

const filteredPesananUser = computed(() =>
  pesananUser.value.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

const filteredRiwayat = computed(() =>
  riwayat.value.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

function completeOrder(order: Order) {
  riwayat.value.push({
    ...order,
    status: 'Selesai',
    createdAt: new Date().toISOString().split('T')[0],
  })
  pesananUser.value = pesananUser.value.filter(item => item.id !== order.id)
}

function confirmDeleteOrder(order: Order) {
  if (confirm(`Apakah Anda yakin ingin menghapus pesanan dari ${order.nama}?`)) {
    pesananUser.value = pesananUser.value.filter(item => item.id !== order.id)
  }
}
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
</style>
