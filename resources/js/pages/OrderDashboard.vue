<template>
  <AdminLayout>
    <Head title="Admin Pesanan - Ella Elektrik" />

    <main class="bg-gray-100 min-h-screen text-gray-900">
      <div class="space-y-6 md:space-y-8 p-4 md:p-6">

        <!-- Header with Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Pesanan</h1>
              <p class="text-sm text-gray-600 mt-1">Kelola pesanan dan riwayat transaksi</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 md:gap-4">
              <!-- Search Bar -->
              <div class="relative flex-1 md:w-64">
                <input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari nama atau produk..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                />
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              </div>

              <!-- Filter Dropdown -->
              <select
                v-model="statusFilter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all"
              >
                <option value="all">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Selesai</option>
                <option value="rejected">Ditolak</option>
              </select>

              <!-- Sort Dropdown -->
              <select
                v-model="sortBy"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white transition-all"
              >
                <option value="newest">Terbaru</option>
                <option value="oldest">Terlama</option>
                <option value="highest">Harga Tertinggi</option>
                <option value="lowest">Harga Terendah</option>
              </select>
            </div>
          </div>

          <!-- Stats Cards -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs md:text-sm text-blue-600 font-medium">Pending</p>
                  <p class="text-xl md:text-2xl font-bold text-blue-700 mt-1">{{ pesananUser.length }}</p>
                </div>
                <i class="fas fa-clock text-2xl md:text-3xl text-blue-400"></i>
              </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs md:text-sm text-green-600 font-medium">Selesai</p>
                  <p class="text-xl md:text-2xl font-bold text-green-700 mt-1">{{ riwayatCompleted.length }}</p>
                </div>
                <i class="fas fa-check-circle text-2xl md:text-3xl text-green-400"></i>
              </div>
            </div>

            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs md:text-sm text-red-600 font-medium">Ditolak</p>
                  <p class="text-xl md:text-2xl font-bold text-red-700 mt-1">{{ riwayatRejected.length }}</p>
                </div>
                <i class="fas fa-times-circle text-2xl md:text-3xl text-red-400"></i>
              </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-xs md:text-sm text-purple-600 font-medium">Total</p>
                  <p class="text-xl md:text-2xl font-bold text-purple-700 mt-1">{{ totalOrders }}</p>
                </div>
                <i class="fas fa-shopping-cart text-2xl md:text-3xl text-purple-400"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- 🟦 Pesanan User -->
        <section v-if="statusFilter === 'all' || statusFilter === 'pending'">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Pesanan Pending</h2>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
              {{ filteredPesananUser.length }} Pesanan
            </span>
          </div>
          <div v-if="filteredPesananUser.length" class="space-y-3">
            <div
              v-for="order in filteredPesananUser"
              :key="order.id"
              @click="openOrderDetail(order)"
              class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition-all duration-200 cursor-pointer"
            >
              <!-- Mobile Layout -->
              <div class="md:hidden flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                  <div class="flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                      <span class="font-semibold text-base text-gray-900">{{ order.nama }}</span>
                      <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-medium">Pending</span>
                    </div>
                    <span class="text-gray-600 text-xs">NIK: {{ order.nik }}</span>
                  </div>
                </div>
                <div class="flex flex-col gap-2 py-2 border-t border-gray-100">
                  <div class="flex items-center gap-2 text-xs text-gray-600">
                    <i class="fas fa-calendar text-gray-400 w-4"></i>
                    <span>{{ order.createdAt }}</span>
                  </div>
                  <div class="flex items-start gap-2 text-xs text-gray-600">
                    <i class="fas fa-box text-gray-400 w-4 mt-0.5"></i>
                    <span class="flex-1">{{ order.products }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-money-bill-wave text-gray-400 w-4"></i>
                    <span class="font-bold text-blue-600">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                  </div>
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
                  <button
                    @click.stop="completeOrder(order)"
                    title="Selesaikan Pesanan"
                    class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-r from-green-600 to-green-500 text-white text-sm font-medium hover:from-green-700 hover:to-green-600 active:scale-95 transition-all shadow-sm"
                  >
                    <i class="fas fa-check mr-1.5"></i>Approve
                  </button>
                  <button
                    @click.stop="confirmDeleteOrder(order)"
                    title="Tolak Pesanan"
                    class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-r from-red-600 to-red-500 text-white text-sm font-medium hover:from-red-700 hover:to-red-600 active:scale-95 transition-all shadow-sm"
                  >
                    <i class="fas fa-times mr-1.5"></i>Tolak
                  </button>
                </div>
              </div>

              <!-- Desktop Layout -->
              <div class="hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
                <!-- Name & NIK -->
                <div class="col-span-3 min-w-0">
                  <div class="flex flex-col">
                    <span class="font-semibold text-gray-900 truncate">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <!-- Date -->
                <div class="col-span-2 min-w-0">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span class="truncate">{{ order.createdAt }}</span>
                  </div>
                </div>

                <!-- Products -->
                <div class="col-span-3 min-w-0">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-box text-gray-400"></i>
                    <span class="truncate">{{ order.products }}</span>
                  </div>
                </div>

                <!-- Price -->
                <div class="col-span-2 min-w-0">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-gray-400"></i>
                    <span class="font-bold text-blue-600 truncate">Rp {{ order.totalPrice.toLocaleString('id-ID') }}</span>
                  </div>
                </div>

                <!-- Actions -->
                <div class="col-span-2 flex items-center justify-end gap-2">
                  <button
                    @click.stop="completeOrder(order)"
                    title="Approve Pesanan"
                    class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-500 text-white rounded-lg hover:from-green-700 hover:to-green-600 hover:shadow-lg active:scale-95 transition-all duration-200 text-sm font-medium whitespace-nowrap"
                  >
                    <i class="fas fa-check mr-2"></i>Approve
                  </button>

                  <button
                    @click.stop="confirmDeleteOrder(order)"
                    title="Tolak Pesanan"
                    class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-lg hover:from-red-700 hover:to-red-600 hover:shadow-lg active:scale-95 transition-all duration-200 text-sm font-medium whitespace-nowrap"
                  >
                    <i class="fas fa-times mr-2"></i>Tolak
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium">Tidak ada pesanan pending</p>
            <p class="text-gray-400 text-sm mt-1">Pesanan yang masuk akan muncul di sini</p>
          </div>
        </section>

        <!-- 🟨 Riwayat Pesanan -->
        <section v-if="statusFilter === 'all' || statusFilter === 'completed' || statusFilter === 'rejected'">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Riwayat Pesanan</h2>
            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
              {{ filteredRiwayat.length }} Pesanan
            </span>
          </div>
          <div v-if="filteredRiwayat.length" class="space-y-3">
            <div
              v-for="order in filteredRiwayat"
              :key="order.id"
              @click="openOrderDetail(order)"
              class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-all duration-200 cursor-pointer"
              :class="{
                'hover:border-green-300': order.status === 'Selesai',
                'hover:border-red-300': order.status === 'Ditolak'
              }"
            >
              <!-- Mobile Layout -->
              <div class="md:hidden flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                  <div class="flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                      <span class="font-semibold text-base text-gray-900">{{ order.nama }}</span>
                      <span
                        class="px-2 py-0.5 rounded-full text-xs font-semibold flex-shrink-0"
                        :class="order.status === 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'"
                      >
                        {{ order.status }}
                      </span>
                    </div>
                    <span class="text-gray-600 text-xs">NIK: {{ order.nik }}</span>
                  </div>
                </div>
                <div class="flex flex-col gap-2 py-2 border-t border-gray-100">
                  <div class="flex items-center gap-2 text-xs text-gray-600">
                    <i class="fas fa-calendar text-gray-400 w-4"></i>
                    <span>{{ order.createdAt }}</span>
                  </div>
                  <div class="flex items-start gap-2 text-xs text-gray-600">
                    <i class="fas fa-box text-gray-400 w-4 mt-0.5"></i>
                    <span class="flex-1">{{ order.products }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-money-bill-wave text-gray-400 w-4"></i>
                    <span class="font-bold" :class="order.status === 'Ditolak' ? 'text-red-600' : 'text-green-600'">
                      Rp {{ order.totalPrice.toLocaleString('id-ID') }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Desktop Layout -->
              <div class="hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
                <!-- Name & NIK -->
                <div class="col-span-3 min-w-0">
                  <div class="flex flex-col">
                    <span class="font-semibold text-gray-900 truncate">{{ order.nama }}</span>
                    <span class="text-gray-500 text-sm">NIK: {{ order.nik }}</span>
                  </div>
                </div>

                <!-- Date -->
                <div class="col-span-2 min-w-0">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span class="truncate">{{ order.createdAt }}</span>
                  </div>
                </div>

                <!-- Products -->
                <div class="col-span-3 min-w-0">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <i class="fas fa-box text-gray-400"></i>
                    <span class="truncate">{{ order.products }}</span>
                  </div>
                </div>

                <!-- Price -->
                <div class="col-span-2 min-w-0">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-money-bill-wave text-gray-400"></i>
                    <span class="font-bold truncate" :class="order.status === 'Ditolak' ? 'text-red-600' : 'text-green-600'">
                      Rp {{ order.totalPrice.toLocaleString('id-ID') }}
                    </span>
                  </div>
                </div>

                <!-- Status -->
                <div class="col-span-2 flex items-center justify-end">
                  <span
                    class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap"
                    :class="order.status === 'Ditolak' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-green-100 text-green-700 border border-green-200'"
                  >
                    <i :class="order.status === 'Ditolak' ? 'fas fa-times-circle' : 'fas fa-check-circle'" class="mr-2"></i>
                    {{ order.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
            <i class="fas fa-history text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium">Tidak ada riwayat pesanan</p>
            <p class="text-gray-400 text-sm mt-1">Riwayat transaksi akan muncul di sini</p>
          </div>
        </section>

      </div>
    </main>

    <!-- Order Detail Modal -->
    <Transition name="modal">
      <div
        v-if="selectedOrder"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
        @click="closeOrderDetail"
      >
        <div
          class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden"
          @click.stop
        >
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between">
            <div>
              <h3 class="text-xl font-bold text-white">Detail Pesanan</h3>
              <p class="text-blue-100 text-sm mt-1">Order ID: #{{ selectedOrder.id }}</p>
            </div>
            <button
              @click="closeOrderDetail"
              class="w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-all"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Modal Content -->
          <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
            <!-- Customer Info -->
            <div class="mb-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-user text-blue-600"></i>
                Informasi Pelanggan
              </h4>
              <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">Nama</span>
                  <span class="font-semibold text-gray-900">{{ selectedOrder.nama }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">NIK</span>
                  <span class="font-semibold text-gray-900">{{ selectedOrder.nik }}</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">Tanggal Pesanan</span>
                  <span class="font-semibold text-gray-900">{{ selectedOrder.createdAt }}</span>
                </div>
                <div v-if="selectedOrder.status" class="flex items-center justify-between">
                  <span class="text-sm text-gray-600">Status</span>
                  <span
                    class="px-3 py-1 rounded-full text-sm font-semibold"
                    :class="selectedOrder.status === 'Ditolak' ? 'bg-red-100 text-red-700' : selectedOrder.status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'"
                  >
                    {{ selectedOrder.status || 'Pending' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Products Info -->
            <div class="mb-6">
              <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                <i class="fas fa-box text-blue-600"></i>
                Produk yang Dipesan
              </h4>
              <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700 leading-relaxed">{{ selectedOrder.products }}</p>
              </div>
            </div>

            <!-- Price Info -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
              <div class="flex items-center justify-between">
                <span class="text-gray-700 font-medium">Total Harga</span>
                <span class="text-2xl font-bold text-blue-600">
                  Rp {{ selectedOrder.totalPrice.toLocaleString('id-ID') }}
                </span>
              </div>
            </div>
          </div>

          <!-- Modal Footer with Actions -->
          <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
            <button
              @click="closeOrderDetail"
              class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition-all"
            >
              Tutup
            </button>
            <template v-if="!selectedOrder.status">
              <button
                @click="completeOrder(selectedOrder); closeOrderDetail()"
                class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-green-600 to-green-500 text-white font-medium hover:from-green-700 hover:to-green-600 transition-all shadow-sm"
              >
                <i class="fas fa-check mr-2"></i>Approve Pesanan
              </button>
              <button
                @click="confirmDeleteOrder(selectedOrder); closeOrderDetail()"
                class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-red-600 to-red-500 text-white font-medium hover:from-red-700 hover:to-red-600 transition-all shadow-sm"
              >
                <i class="fas fa-times mr-2"></i>Tolak Pesanan
              </button>
            </template>
          </div>
        </div>
      </div>
    </Transition>
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
const statusFilter = ref('all')
const sortBy = ref('newest')
const selectedOrder = ref<Order | null>(null)

// Computed stats
const riwayatCompleted = computed(() => 
  props.riwayat.filter(o => o.status === 'Selesai')
)

const riwayatRejected = computed(() => 
  props.riwayat.filter(o => o.status === 'Ditolak')
)

const totalOrders = computed(() => 
  props.pesananUser.length + props.riwayat.length
)

// Sort function
const sortOrders = (orders: Order[]) => {
  const sorted = [...orders]
  
  switch (sortBy.value) {
    case 'newest':
      return sorted.sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
    case 'oldest':
      return sorted.sort((a, b) => new Date(a.createdAt).getTime() - new Date(b.createdAt).getTime())
    case 'highest':
      return sorted.sort((a, b) => b.totalPrice - a.totalPrice)
    case 'lowest':
      return sorted.sort((a, b) => a.totalPrice - b.totalPrice)
    default:
      return sorted
  }
}

// Filtered and sorted pending orders
const filteredPesananUser = computed(() => {
  const filtered = props.pesananUser.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.nik.includes(searchQuery.value)
  )
  return sortOrders(filtered)
})

// Filtered and sorted history orders
const filteredRiwayat = computed(() => {
  let filtered = props.riwayat.filter(o =>
    o.nama.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.products.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    o.nik.includes(searchQuery.value)
  )
  
  // Apply status filter
  if (statusFilter.value === 'completed') {
    filtered = filtered.filter(o => o.status === 'Selesai')
  } else if (statusFilter.value === 'rejected') {
    filtered = filtered.filter(o => o.status === 'Ditolak')
  }
  
  return sortOrders(filtered)
})

function completeOrder(order: Order) {
  if (confirm(`Approve pesanan dari ${order.nama}?`)) {
    router.post(`/admin/orders/${order.id}/complete`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        // Optional: show success message
      }
    })
  }
}

function confirmDeleteOrder(order: Order) {
  if (confirm(`Apakah Anda yakin ingin menolak pesanan dari ${order.nama}? Tindakan ini tidak dapat dibatalkan.`)) {
    router.delete(`/admin/orders/${order.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        // Optional: show success message
      }
    })
  }
}

function openOrderDetail(order: Order) {
  selectedOrder.value = order
}

function closeOrderDetail() {
  selectedOrder.value = null
}
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";

.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active .bg-white,
.modal-leave-active .bg-white {
  transition: transform 0.3s ease;
}

.modal-enter-from .bg-white,
.modal-leave-to .bg-white {
  transform: scale(0.9);
}
</style>
