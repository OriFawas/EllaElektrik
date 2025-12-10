<template>
  <AdminLayout>
    <div class="space-y-6">

      <!-- Judul -->
      <h1 class="text-xl md:text-2xl font-semibold">Dashboard Admin</h1>

      <!-- ================= TOP STATS ================= -->
      <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <!-- Orders Stats -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Total Pesanan</p>
              <h2 v-if="loading" class="h-7 w-16 bg-blue-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalOrders }}</h2>
            </div>
            <i class="fas fa-shopping-cart text-2xl text-blue-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Siap Diambil</p>
              <h2 v-if="loading" class="h-7 w-12 bg-yellow-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.activeOrders }}</h2>
            </div>
            <i class="fas fa-box-open text-2xl text-yellow-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Selesai</p>
              <h2 v-if="loading" class="h-7 w-12 bg-green-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.completedOrders }}</h2>
            </div>
            <i class="fas fa-check-circle text-2xl text-green-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-lg p-4 border border-red-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Ditolak</p>
              <h2 v-if="loading" class="h-7 w-12 bg-red-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.canceledOrders }}</h2>
            </div>
            <i class="fas fa-times-circle text-2xl text-red-400"></i>
          </div>
        </div>

        <!-- User Stats -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Total User</p>
              <h2 v-if="loading" class="h-7 w-12 bg-purple-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalUsers }}</h2>
            </div>
            <i class="fas fa-users text-2xl text-purple-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Perlu Verifikasi</p>
              <h2 v-if="loading" class="h-7 w-12 bg-orange-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.pendingVerifications }}</h2>
            </div>
            <i class="fas fa-user-clock text-2xl text-orange-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-4 border border-teal-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Terverifikasi</p>
              <h2 v-if="loading" class="h-7 w-12 bg-teal-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.verifiedUsers }}</h2>
            </div>
            <i class="fas fa-user-check text-2xl text-teal-400"></i>
          </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200 shadow-sm">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-600">Total Produk</p>
              <h2 v-if="loading" class="h-7 w-12 bg-indigo-200 animate-pulse rounded mt-1"></h2>
              <h2 v-else class="text-2xl font-bold text-gray-900 mt-1">{{ stats.totalProducts }}</h2>
            </div>
            <i class="fas fa-box text-2xl text-indigo-400"></i>
          </div>
        </div>
      </div>

      <!-- ================= GRAFIK ORDERS ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Orders Chart -->
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Pesanan</h3>
          <div v-if="loading" class="h-64 bg-gray-100 animate-pulse rounded"></div>
          <div v-else-if="stats.totalOrders === 0" class="h-64 flex items-center justify-center bg-gray-50 rounded">
            <div class="text-center text-gray-500">
              <i class="fas fa-chart-pie text-4xl mb-3 text-gray-300"></i>
              <p class="text-sm">Belum ada data pesanan</p>
            </div>
          </div>
          <div v-else class="h-64">
            <canvas ref="ordersChart"></canvas>
          </div>
        </div>

        <!-- Users Chart -->
        <div class="bg-white shadow-sm rounded-lg p-4 border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Verifikasi User</h3>
          <div v-if="loading" class="h-64 bg-gray-100 animate-pulse rounded"></div>
          <div v-else-if="stats.totalUsers === 0" class="h-64 flex items-center justify-center bg-gray-50 rounded">
            <div class="text-center text-gray-500">
              <i class="fas fa-chart-bar text-4xl mb-3 text-gray-300"></i>
              <p class="text-sm">Belum ada data user</p>
            </div>
          </div>
          <div v-else class="h-64">
            <canvas ref="usersChart"></canvas>
          </div>
        </div>
      </div>

      <!-- ================= MANAJEMEN PESANAN ================= -->
      <section>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-900">Pesanan Siap Diambil</h2>
          <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
            {{ pendingOrders.length }} Pesanan
          </span>
        </div>
        
        <div v-if="loading" class="space-y-3">
          <div v-for="i in 3" :key="i" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="h-4 w-3/4 bg-gray-200 animate-pulse rounded mb-2"></div>
            <div class="h-3 w-1/2 bg-gray-200 animate-pulse rounded"></div>
          </div>
        </div>
        <div v-else-if="pendingOrders.length" class="space-y-3">
          <div
            v-for="order in pendingOrders"
            :key="order.id"
            @click="openOrderDetail(order)"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-blue-300 transition-all duration-200 cursor-pointer"
          >
            <!-- Mobile Layout -->
            <div class="md:hidden flex flex-col gap-3">
              <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900">{{ order.nama }}</h3>
                  <p class="text-xs text-gray-500">NIK: {{ order.nik }}</p>
                </div>
                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Siap Diambil</span>
              </div>
              <div class="flex flex-col gap-2 py-2 border-t border-gray-100">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Tanggal:</span>
                  <span class="font-medium">{{ order.createdAt }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Produk:</span>
                  <span class="font-medium text-right">{{ order.products }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Total:</span>
                  <span class="font-bold text-blue-600">{{ order.totalPriceFormatted }}</span>
                </div>
              </div>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
              <div class="col-span-3">
                <p class="font-semibold text-gray-900">{{ order.nama }}</p>
                <p class="text-xs text-gray-500">NIK: {{ order.nik }}</p>
              </div>
              <div class="col-span-2 text-sm text-gray-600">{{ order.createdAt }}</div>
              <div class="col-span-3 text-sm text-gray-700 truncate">{{ order.products }}</div>
              <div class="col-span-2 text-sm font-bold text-blue-600">{{ order.totalPriceFormatted }}</div>
              <div class="col-span-2 flex items-center justify-end gap-2">
                <button @click.stop="completeOrder(order)" class="px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs hover:bg-green-700">
                  <i class="fas fa-check mr-1"></i>Approve
                </button>
                <button @click.stop="rejectOrder(order)" class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">
                  <i class="fas fa-times mr-1"></i>Reject
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
          <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg font-medium">Tidak ada pesanan siap diambil</p>
        </div>
      </section>

      <!-- ================= MANAJEMEN VERIFIKASI USER ================= -->
      <section>
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-gray-900">Verifikasi User Pending</h2>
          <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
            {{ pendingVerifications.length }} User
          </span>
        </div>
        
        <div v-if="loading" class="space-y-3">
          <div v-for="i in 3" :key="i" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="h-4 w-3/4 bg-gray-200 animate-pulse rounded mb-2"></div>
            <div class="h-3 w-1/2 bg-gray-200 animate-pulse rounded"></div>
          </div>
        </div>
        <div v-else-if="pendingVerifications.length" class="space-y-3">
          <div
            v-for="user in pendingVerifications"
            :key="user.id"
            class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md hover:border-orange-300 transition-all duration-200"
          >
            <!-- Mobile Layout -->
            <div class="md:hidden flex flex-col gap-3">
              <div class="flex items-start justify-between gap-2">
                <div class="flex-1">
                  <h3 class="font-semibold text-gray-900">{{ user.name }}</h3>
                  <p class="text-xs text-gray-500">{{ user.email }}</p>
                  <p class="text-xs text-gray-500">NIK: {{ user.nik }}</p>
                </div>
                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-medium">Pending</span>
              </div>
              <div class="flex flex-col gap-2 py-2 border-t border-gray-100">
                <div class="text-sm">
                  <span class="text-gray-600">Telp:</span>
                  <span class="font-medium ml-2">{{ user.phone }}</span>
                </div>
                <div class="text-sm">
                  <span class="text-gray-600">Diupdate:</span>
                  <span class="font-medium ml-2">{{ user.updated_at }}</span>
                </div>
                <button v-if="user.ktp_url" @click="openKtpPreview(user.ktp_url)" class="text-blue-600 text-xs underline text-left">
                  <i class="fas fa-image mr-1"></i>Lihat KTP
                </button>
              </div>
              <div class="flex gap-2 pt-2">
                <button @click="approveUser(user)" class="flex-1 px-3 py-2 rounded-lg bg-green-600 text-white text-xs hover:bg-green-700 font-medium">
                  <i class="fas fa-check mr-1"></i>Approve
                </button>
                <button @click="openRejectModal(user)" class="flex-1 px-3 py-2 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700 font-medium">
                  <i class="fas fa-times mr-1"></i>Reject
                </button>
              </div>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
              <div class="col-span-3">
                <p class="font-semibold text-gray-900">{{ user.name }}</p>
                <p class="text-xs text-gray-500">{{ user.email }}</p>
                <p class="text-xs text-gray-500">NIK: {{ user.nik }}</p>
              </div>
              <div class="col-span-2 text-sm text-gray-600">{{ user.phone }}</div>
              <div class="col-span-2 text-sm text-gray-600">{{ user.updated_at }}</div>
              <div class="col-span-2">
                <button v-if="user.ktp_url" @click="openKtpPreview(user.ktp_url)" class="text-blue-600 text-sm underline hover:text-blue-800">
                  <i class="fas fa-image mr-1"></i>Lihat KTP
                </button>
                <span v-else class="text-gray-400 text-sm">—</span>
              </div>
              <div class="col-span-3 flex items-center justify-end gap-2">
                <button @click="approveUser(user)" class="px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs hover:bg-green-700">
                  <i class="fas fa-check mr-1"></i>Approve
                </button>
                <button @click="openRejectModal(user)" class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs hover:bg-red-700">
                  <i class="fas fa-times mr-1"></i>Reject
                </button>
              </div>
            </div>
          </div>
        </div>
        <div v-else class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
          <i class="fas fa-user-check text-5xl text-gray-300 mb-4"></i>
          <p class="text-gray-500 text-lg font-medium">Tidak ada verifikasi pending</p>
        </div>
      </section>

    </div>

    <!-- Order Detail Modal -->
    <div v-if="selectedOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50" @click="closeOrderDetail">
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden" @click.stop>
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between">
          <div>
            <h3 class="text-xl font-bold text-white">Detail Pesanan</h3>
            <p class="text-blue-100 text-sm mt-1">Order ID: #{{ selectedOrder.id }}</p>
          </div>
          <button @click="closeOrderDetail" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
          <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
              <i class="fas fa-user text-blue-600"></i>Informasi Pelanggan
            </h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
              <div class="flex justify-between"><span class="text-gray-600">Nama:</span><span class="font-medium">{{ selectedOrder.nama }}</span></div>
              <div class="flex justify-between"><span class="text-gray-600">NIK:</span><span class="font-medium">{{ selectedOrder.nik }}</span></div>
              <div class="flex justify-between"><span class="text-gray-600">Tanggal:</span><span class="font-medium">{{ selectedOrder.createdAt }}</span></div>
            </div>
          </div>
          <div class="mb-6">
            <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
              <i class="fas fa-box text-blue-600"></i>Produk
            </h4>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-gray-700">{{ selectedOrder.products }}</p>
            </div>
          </div>
          <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
            <div class="flex items-center justify-between">
              <span class="text-lg font-semibold text-gray-900">Total:</span>
              <span class="text-2xl font-bold text-blue-600">{{ selectedOrder.totalPriceFormatted }}</span>
            </div>
          </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
          <button @click="closeOrderDetail" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-100">Tutup</button>
          <button @click="completeOrder(selectedOrder)" class="px-5 py-2.5 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700">
            <i class="fas fa-check mr-2"></i>Approve
          </button>
          <button @click="rejectOrder(selectedOrder)" class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700">
            <i class="fas fa-times mr-2"></i>Reject
          </button>
        </div>
      </div>
    </div>

    <!-- KTP Preview Modal -->
    <div v-if="ktpPreview.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" @click="closeKtpPreview">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] p-4 flex flex-col" @click.stop>
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold">Pratinjau KTP</h3>
          <button @click="closeKtpPreview" class="px-3 py-1 rounded border text-sm hover:bg-gray-100">Tutup</button>
        </div>
        <div class="flex-1 overflow-auto">
          <img v-if="ktpPreview.type === 'image'" :src="ktpPreview.url" alt="KTP" class="max-w-full h-auto mx-auto rounded" />
          <iframe v-else :src="ktpPreview.url" class="w-full h-[70vh] rounded" />
        </div>
      </div>
    </div>

    <!-- Reject User Modal -->
    <div v-if="rejectModal.open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" @click="closeRejectModal">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-5" @click.stop>
        <h3 class="text-lg font-semibold mb-3">Konfirmasi Penolakan</h3>
        <div class="space-y-3">
          <div>
            <label class="text-sm text-gray-700 font-medium">Alasan Penolakan</label>
            <textarea v-model="rejectModal.reason" rows="4" class="w-full border rounded px-3 py-2 text-sm mt-1" placeholder="Tuliskan alasan penolakan dengan jelas"></textarea>
          </div>
        </div>
        <div class="mt-5 flex justify-end gap-2">
          <button @click="closeRejectModal" class="px-4 py-2 rounded border text-sm font-medium hover:bg-gray-100">Batal</button>
          <button @click="confirmReject" class="px-4 py-2 rounded bg-red-600 text-white text-sm font-medium hover:bg-red-700">Konfirmasi</button>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div v-if="toast.show" class="fixed bottom-4 right-4 z-50 max-w-sm">
      <div :class="['px-4 py-3 rounded-lg shadow-lg text-white', toast.type === 'success' ? 'bg-green-600' : 'bg-red-600']">
        {{ toast.message }}
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'

const loading = ref(true)
const stats = ref({
  totalOrders: 0,
  activeOrders: 0,
  completedOrders: 0,
  canceledOrders: 0,
  totalUsers: 0,
  pendingVerifications: 0,
  verifiedUsers: 0,
  totalProducts: 0,
})
const pendingOrders = ref([])
const pendingVerifications = ref([])

const ordersChart = ref(null)
const usersChart = ref(null)
let ordersChartInstance = null
let usersChartInstance = null

const selectedOrder = ref(null)
const ktpPreview = ref({ open: false, url: '', type: 'image' })
const rejectModal = ref({ open: false, user: null, reason: '' })
const toast = ref({ show: false, message: '', type: 'success' })

let toastTimer = null

async function fetchDashboard() {
  loading.value = true
  try {
    const res = await fetch('/admin/dashboard/data', {
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    })
    if (!res.ok) throw new Error('Gagal memuat data dashboard')
    const data = await res.json()
    stats.value = data.stats || stats.value
    pendingOrders.value = data.pendingOrders || []
    pendingVerifications.value = data.pendingVerifications || []
    
    // Wait for DOM update then initialize charts
    await nextTick()
    loadChartsAndInit()
  } catch (e) {
    showToast(e.message || 'Terjadi kesalahan', 'error')
  } finally {
    loading.value = false
  }
}

function loadChartsAndInit() {
  // Check if Chart.js is already loaded
  if (window.Chart) {
    initCharts()
    return
  }

  // Load Chart.js dynamically
  const script = document.createElement('script')
  script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js'
  script.onload = () => {
    initCharts()
  }
  script.onerror = () => {
    console.error('Failed to load Chart.js')
  }
  document.head.appendChild(script)
}

function initCharts() {
  if (!window.Chart) return

  // Orders Chart (Doughnut) - only if there's data
  if (ordersChart.value && stats.value.totalOrders > 0) {
    if (ordersChartInstance) ordersChartInstance.destroy()
    const ctx = ordersChart.value.getContext('2d')
    
    // Use at least 1 for empty categories to show something
    const activeOrders = stats.value.activeOrders || 0
    const completedOrders = stats.value.completedOrders || 0
    const canceledOrders = stats.value.canceledOrders || 0
    
    ordersChartInstance = new window.Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Siap Diambil', 'Selesai', 'Dibatalkan'],
        datasets: [{
          data: [activeOrders, completedOrders, canceledOrders],
          backgroundColor: ['#fbbf24', '#10b981', '#ef4444'],
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { padding: 15, font: { size: 12 } }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.label + ': ' + context.parsed + ' pesanan'
              }
            }
          }
        }
      }
    })
  }

  // Users Chart (Bar) - only if there's data
  if (usersChart.value && stats.value.totalUsers > 0) {
    if (usersChartInstance) usersChartInstance.destroy()
    const ctx = usersChart.value.getContext('2d')
    
    usersChartInstance = new window.Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Total User', 'Pending', 'Terverifikasi'],
        datasets: [{
          label: 'Jumlah User',
          data: [
            stats.value.totalUsers || 0,
            stats.value.pendingVerifications || 0,
            stats.value.verifiedUsers || 0
          ],
          backgroundColor: ['#8b5cf6', '#f97316', '#14b8a6'],
          borderRadius: 6,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                return context.parsed.y + ' user'
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        }
      }
    })
  }
}

function showToast(message, type = 'success') {
  toast.value = { show: true, message, type }
  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => (toast.value.show = false), 3000)
}

function openOrderDetail(order) {
  selectedOrder.value = order
}

function closeOrderDetail() {
  selectedOrder.value = null
}

function openKtpPreview(url) {
  if (!url) return
  const lower = url.toLowerCase()
  const isImage = lower.endsWith('.jpg') || lower.endsWith('.jpeg') || lower.endsWith('.png')
  ktpPreview.value = { open: true, url, type: isImage ? 'image' : 'pdf' }
}

function closeKtpPreview() {
  ktpPreview.value = { open: false, url: '', type: 'image' }
}

function completeOrder(order) {
  if (confirm(`Approve pesanan dari ${order.nama}?`)) {
    router.post(`/admin/orders/${order.id}/complete`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        showToast('Pesanan berhasil diapprove', 'success')
        closeOrderDetail()
        fetchDashboard()
      }
    })
  }
}

function rejectOrder(order) {
  if (confirm(`Yakin menolak pesanan dari ${order.nama}?`)) {
    router.delete(`/admin/orders/${order.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        showToast('Pesanan ditolak', 'success')
        closeOrderDetail()
        fetchDashboard()
      }
    })
  }
}

function openRejectModal(user) {
  rejectModal.value = { open: true, user, reason: '' }
}

function closeRejectModal() {
  rejectModal.value = { open: false, user: null, reason: '' }
}

async function approveUser(user) {
  if (!confirm(`Approve verifikasi untuk ${user.name}?`)) return
  try {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const res = await fetch(`/api/admin/users/${user.id}/approve`, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
      credentials: 'same-origin'
    })
    if (!res.ok) throw new Error('Gagal memverifikasi user')
    showToast('User berhasil diverifikasi', 'success')
    fetchDashboard()
  } catch (e) {
    showToast(e.message || 'Terjadi kesalahan', 'error')
  }
}

async function confirmReject() {
  const { user, reason } = rejectModal.value
  if (!user || !reason.trim()) {
    showToast('Alasan penolakan wajib diisi', 'error')
    return
  }
  try {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    const res = await fetch(`/api/admin/users/${user.id}/reject`, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
      credentials: 'same-origin',
      body: JSON.stringify({ reason: reason.trim() })
    })
    if (!res.ok) throw new Error('Gagal menolak user')
    showToast('User ditolak', 'success')
    closeRejectModal()
    fetchDashboard()
  } catch (e) {
    showToast(e.message || 'Terjadi kesalahan', 'error')
  }
}

onMounted(fetchDashboard)

onUnmounted(() => {
  if (ordersChartInstance) ordersChartInstance.destroy()
  if (usersChartInstance) usersChartInstance.destroy()
})
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css";
</style>
