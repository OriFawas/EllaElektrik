<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'

// 📦 Data dummy
const verifikasi = ref([
  { id: 1, userId: 101, nama: 'Jule', products: 'Kipas Angin', totalPrice: 550000, createdAt: '2025-10-28' },
  { id: 2, userId: 102, nama: 'Safno', products: 'Rice Cooker', totalPrice: 320000, createdAt: '2025-10-28' },
])

const pesananUser = ref([
  { id: 3, userId: 103, nama: 'Zize', products: 'Lampu LED', totalPrice: 210000, createdAt: '2025-10-27' },
])

const riwayat = ref([
  { id: 4, userId: 104, nama: 'Agus', products: 'Mesin Cuci', totalPrice: 2500000, createdAt: '2025-10-25', status: 'Selesai' },
])

// 🔎 Search
const searchQuery = ref('')

const filteredVerifikasi = computed(() =>
  verifikasi.value.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

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

// ✅ Fungsi-fungsi
function approveOrder(order: any) {
  pesananUser.value.push(order)
  verifikasi.value = verifikasi.value.filter(item => item.id !== order.id)
}

function completeOrder(order: any) {
  riwayat.value.push({
    ...order,
    status: 'Selesai',
    createdAt: new Date().toISOString().split('T')[0],
  })
  pesananUser.value = pesananUser.value.filter(item => item.id !== order.id)
}

function rejectOrder(order: any) {
  if (confirm(`Yakin ingin menolak pesanan dari ${order.nama}?`)) {
    riwayat.value.push({
      ...order,
      status: 'Ditolak',
      createdAt: new Date().toISOString().split('T')[0],
    })
    verifikasi.value = verifikasi.value.filter(item => item.id !== order.id)
  }
}
</script>

<template>
  <AdminLayout>
    <Head title="Admin Pesanan - Ella Elektrik" />

    <main class="p-6 bg-gray-100 min-h-screen text-gray-900">
      <div class="max-w-7xl mx-auto space-y-12">

        <!-- 🟩 Verifikasi Pesanan -->
        <section>
          <h2 class="text-2xl font-bold mb-4">Verifikasi Pesanan</h2>
          <div v-if="filteredVerifikasi.length" class="space-y-4">
            <div
              v-for="order in filteredVerifikasi"
              :key="order.id"
              class="bg-white rounded-lg shadow p-4 flex items-center justify-between hover:shadow-lg transition-shadow duration-200"
            >
              <div class="flex items-center w-full justify-between">
                <!-- Info kiri -->
                <div class="flex items-center gap-6">
                  <img src="/images/user-avatar.png" alt="User" class="h-10 w-10 rounded-full" />
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">ID: {{ order.userId }}</span>
                  </div>
                </div>

                <!-- Info tengah -->
                <div class="flex items-center gap-16 text-sm text-gray-700">
                  <span class="w-28">{{ order.createdAt }}</span>
                  <span class="w-40">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <!-- Tombol aksi -->
                <div class="flex space-x-3">
                  <button
                    @click="rejectOrder(order)"
                    class="w-7 h-7 flex items-center justify-center bg-red-600 text-white rounded-full hover:bg-red-700 hover:scale-110 active:scale-95 transition-all duration-200"
                    title="Tolak Pesanan"
                  >
                    <i class="fas fa-times text-xs"></i>
                  </button>

                  <button
                    @click="approveOrder(order)"
                    class="w-7 h-7 flex items-center justify-center bg-green-600 text-white rounded-full hover:bg-green-700 hover:scale-110 active:scale-95 transition-all duration-200"
                    title="Setujui Pesanan"
                  >
                    <i class="fas fa-check text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 italic">Tidak ada pesanan ditemukan.</p>
        </section>

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
                <!-- Info kiri -->
                <div class="flex items-center gap-6">
                  <img src="/images/user-avatar.png" alt="User" class="h-10 w-10 rounded-full" />
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">ID: {{ order.userId }}</span>
                  </div>
                </div>

                <!-- Info tengah -->
                <div class="flex items-center gap-16 text-sm text-gray-700">
                  <span class="w-28">{{ order.createdAt }}</span>
                  <span class="w-40">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <!-- Tombol aksi -->
                <button
                  @click="completeOrder(order)"
                  class="w-7 h-7 flex items-center justify-center bg-blue-600 text-white rounded-full hover:bg-blue-700 hover:scale-110 active:scale-95 transition-all duration-200"
                  title="Tandai Selesai"
                >
                  <i class="fas fa-check text-xs"></i>
                </button>
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
                <!-- Info kiri -->
                <div class="flex items-center gap-6">
                  <img src="/images/user-avatar.png" alt="User" class="h-10 w-10 rounded-full" />
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">ID: {{ order.userId }}</span>
                  </div>
                </div>

                <!-- Info tengah -->
                <div class="flex items-center gap-16 text-sm text-gray-700">
                  <span class="w-28">{{ order.createdAt }}</span>
                  <span class="w-40">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <!-- Status -->
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

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";

i {
  font-size: 14px;
}
button {
  transition: all 0.25s ease-in-out;
}
</style>
