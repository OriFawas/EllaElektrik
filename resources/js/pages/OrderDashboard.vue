<template>
  <AdminLayout>
    <Head title="Admin Pesanan - Ella Elektrik" />

    <main class=" bg-gray-100 min-h-screen text-gray-900">
      <div class="space-y-8 md:space-y-12 p-2 md:p-0">

        <!-- 🟦 Pesanan User -->
        <section>
          <h2 class="text-xl md:text-2xl font-bold mb-4 px-2 md:px-0">Pesanan User</h2>
          <div v-if="filteredPesananUser.length" class="space-y-3 md:space-y-4">
            <div
              v-for="order in filteredPesananUser"
              :key="order.id"
              class="bg-white rounded-lg shadow p-3 md:p-4 hover:shadow-lg transition-shadow duration-200"
            >
              <!-- Mobile Layout -->
              <div class="md:hidden flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                  <div class="flex flex-col">
                    <span class="font-medium text-sm md:text-base">{{ order.nama }}</span>
                    <span class="text-gray-500 text-xs md:text-sm">NIK: {{ order.nik }}</span>
                  </div>
                  <span class="font-semibold text-sm text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                  <span>{{ order.createdAt }}</span>
                  <span class="text-xs line-clamp-1">{{ order.products }}</span>
                </div>
                <div class="flex items-center gap-2 pt-2">
                  <button
                    @click="completeOrder(order)"
                    title="Selesaikan Pesanan"
                    class="flex-1 px-2 py-1.5 rounded bg-blue-600 text-white text-xs font-medium hover:bg-blue-700 active:scale-95 transition-all"
                  >
                    <i class="fas fa-check mr-1"></i>Approve
                  </button>
                  <button
                    @click="confirmDeleteOrder(order)"
                    title="Hapus Pesanan"
                    class="flex-1 px-2 py-1.5 rounded bg-red-600 text-white text-xs font-medium hover:bg-red-700 active:scale-95 transition-all"
                  >
                    <i class="fas fa-xmark mr-1"></i>Hapus
                  </button>
                </div>
              </div>

              <!-- Desktop Layout -->
              <div class="hidden md:flex items-center justify-between gap-4">
                <div class="flex items-center gap-6 flex-1">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-16 text-sm text-gray-700 flex-1">
                  <span class="w-28 flex-shrink-0">{{ order.createdAt }}</span>
                  <span class="w-40 flex-shrink-0">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045] w-32 flex-shrink-0">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
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
                    <i class="fas fa-xmark text-xs"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 italic px-2 md:px-0">Tidak ada pesanan ditemukan.</p>
        </section>

        <!-- 🟨 Riwayat Pesanan -->
        <section>
          <h2 class="text-xl md:text-2xl font-bold mb-4 px-2 md:px-0">Riwayat Pesanan</h2>
          <div v-if="filteredRiwayat.length" class="space-y-3 md:space-y-4">
            <div
              v-for="order in filteredRiwayat"
              :key="order.id"
              class="bg-white rounded-lg shadow p-3 md:p-4 hover:shadow-lg transition-shadow duration-200"
            >
              <!-- Mobile Layout -->
              <div class="md:hidden flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                  <div class="flex flex-col flex-1">
                    <span class="font-medium text-sm md:text-base">{{ order.nama }}</span>
                    <span class="text-gray-500 text-xs md:text-sm">NIK: {{ order.nik }}</span>
                  </div>
                  <span
                    class="px-2 py-1 rounded-full text-xs font-semibold flex-shrink-0"
                    :class="order.status === 'Ditolak' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                  >
                    {{ order.status }}
                  </span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-600">
                  <span>{{ order.createdAt }}</span>
                  <span class="text-xs line-clamp-1">{{ order.products }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="font-semibold text-sm text-[#183045]">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>
              </div>

              <!-- Desktop Layout -->
              <div class="hidden md:flex items-center justify-between gap-4">
                <div class="flex items-center gap-6 flex-1">
                  <div class="flex flex-col">
                    <span class="font-medium">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <div class="flex items-center gap-16 text-sm text-gray-700 flex-1">
                  <span class="w-28 flex-shrink-0">{{ order.createdAt }}</span>
                  <span class="w-40 flex-shrink-0">{{ order.products }}</span>
                  <span class="font-semibold text-[#183045] w-32 flex-shrink-0">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                </div>

                <span
                  class="px-3 py-1 rounded-full text-sm font-semibold flex-shrink-0"
                  :class="order.status === 'Ditolak' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                >
                  {{ order.status }}
                </span>
              </div>
            </div>
          </div>
          <p v-else class="text-gray-500 italic px-2 md:px-0">Tidak ada pesanan ditemukan.</p>
        </section>

      </div>
    </main>
  </AdminLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, router } from '@inertiajs/vue3'
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

const props = defineProps<{
  pesananUser: Order[]
  riwayat: Order[]
}>()

const searchQuery = ref('')

const filteredPesananUser = computed(() =>
  props.pesananUser.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

const filteredRiwayat = computed(() =>
  props.riwayat.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

function completeOrder(order: Order) {
  router.post(`/admin/orders/${order.id}/complete`, {}, {
    preserveScroll: true,
  })
}

function confirmDeleteOrder(order: Order) {
  if (confirm(`Apakah Anda yakin ingin menolak pesanan dari ${order.nama}?`)) {
    router.delete(`/admin/orders/${order.id}`, {
      preserveScroll: true,
    })
  }
}
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
</style>
