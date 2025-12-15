<script setup>
import { ref, computed, onMounted} from 'vue'
import { router } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'

const codChecked = ref(true)

// Cart state loaded from backend
const loading = ref(false)
const error = ref(null)
const cart = ref({ items: [], subtotal: 0, item_count: 0 })

const serviceFee = ref(2000)
const showConfirm = ref(false)


const fetchCart = async () => {
  loading.value = true
  error.value = null
  try {
  const res = await fetch('/api/cart', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
    if (res.status === 401) { window.location.href = '/login'; return }
    if (!res.ok) throw new Error(`Gagal memuat keranjang (${res.status})`)
    const data = await res.json()
    cart.value = {
      items: Array.isArray(data.items) ? data.items : [],
      subtotal: Number(data.subtotal || 0),
      item_count: Number(data.item_count || 0),
    }
  } catch (e) {
    error.value = e.message || String(e)
  } finally {
    loading.value = false
  }
}

const subtotal = computed(() => Number(cart.value.subtotal || 0))
const total = computed(() => subtotal.value + serviceFee.value)

const createOrder = () => {
  if (!codChecked.value || cart.value.items.length === 0) return
  loading.value = true
  router.post('/orders', {}, {
    onFinish: () => (loading.value = false),
    onError: (errors) => {
      error.value = errors.order || errors.cart || 'Gagal membuat pesanan'
    }
  })
}

const removeItem = async (id) => {
  try {
    const res = await fetch(`/api/cart/items/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      credentials: 'same-origin',
    })
    if (res.status === 401) { window.location.href = '/login'; return }
    if (res.status === 419) { window.location.reload(); return }
    if (!res.ok) throw new Error('Gagal menghapus item')
    const data = await res.json()
    cart.value = { items: data.items, subtotal: data.subtotal, item_count: data.item_count }
  } catch (e) {
    console.error(e)
    error.value = e.message || String(e)
  }
}

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(value)
}

onMounted(fetchCart)
</script>

<template>
  <div class="min-h-screen bg-white pt-20">
    <HeaderLayout />

    <h1 class="text-2xl font-semibold px-8 pt-8">Pesanan Anda</h1>

    <div class="px-8 grid grid-cols-1 lg:grid-cols-3 gap-10 py-10">

      <!-- Bagian Keranjang (Table) -->
      <div v-if="!loading && cart.items.length > 0" class="lg:col-span-2 overflow-x-auto bg-white border rounded-lg shadow-sm">
        <table class="w-full text-sm">
          <thead class="bg-[#183045] border-b">
            <tr>
              <th class="px-4 py-3 text-left font-semibold text-white">Produk</th>
              <th class="px-4 py-3 text-center font-semibold w-24 text-white">Jumlah</th>
              <th class="px-4 py-3 text-right font-semibold w-28 text-white">Harga Satuan</th>
              <th class="px-4 py-3 text-right font-semibold w-32 text-white">Total</th>
              <th class="px-4 py-3 text-center font-semibold w-16 text-white">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in cart.items" :key="item.id" class="border-b hover:bg-gray-50">
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <img :src="item.image" class="w-16 h-16 object-cover rounded" />
                  <span class="font-medium">{{ item.name }}</span>
                </div>
              </td>
              <td class="px-4 py-3 text-center font-medium">{{ item.qty }}</td>
              <td class="px-4 py-3 text-right">Rp. {{ formatCurrency(Number(item.unit_price || item.unit_price_snapshot || 0)) }}</td>
              <td class="px-4 py-3 text-right font-semibold">Rp. {{ formatCurrency(Number((item.unit_price || item.unit_price_snapshot || 0) * item.qty)) }}</td>
              <td class="px-4 py-3 text-center">
                <button @click="removeItem(item.id)" class="text-red-500 hover:underline text-sm">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else-if="loading" class="lg:col-span-2 text-center text-gray-500 py-6">Memuat keranjang…</div>
      <div v-else-if="error" class="lg:col-span-2 text-center text-red-500 py-6">{{ error }}</div>
      <div v-else class="lg:col-span-2 text-center text-gray-500 py-6">Keranjangmu kosong.</div>

      <!-- Bagian Informasi Pemesanan -->
      <div class="bg-white border rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-6">Informasi Pemesanan</h2>

        <!-- Checkbox -->
        <label class="flex items-start gap-3 border p-4 rounded cursor-pointer mb-6">
          <input type="checkbox" checked disabled class="mt-1" />
          <div>
            <p class="font-medium">Cash On Delivery</p>
            <p class="text-sm text-gray-600">Bayar Barang di Toko</p>
          </div>
        </label>

        <!-- Perhitungan (Table) -->
        <div class="space-y-4 mb-6">
          <div class="flex justify-between items-center">
            <span class="text-gray-700 font-medium">Subtotal</span>
            <span class="text-lg font-bold text-gray-800">Rp. {{ formatCurrency(subtotal) }}</span>
          </div>
          <div class="flex justify-between items-center">
            <span class="text-gray-700 font-medium">Biaya Layanan</span>
            <span class="text-lg font-bold text-gray-800">Rp. {{ formatCurrency(serviceFee) }}</span>
          </div>
          <div class="flex justify-between items-center border-t pt-4">
            <span class="text-lg font-bold text-gray-800">Total</span>
            <span class="text-2xl font-bold text-blue-900">Rp. {{ formatCurrency(total) }}</span>
          </div>
        </div>

        <!-- Tombol Pesan -->
        <button
          @click="showConfirm = true"
          :disabled="!codChecked || loading || cart.items.length === 0"
          :class="codChecked && cart.items.length > 0 ? 'bg-[#183045] hover:bg-gray-800' : 'bg-gray-400 cursor-not-allowed'"
          class="w-full py-3 text-center text-white font-semibold rounded-lg"
        >
          {{ loading ? 'Memproses...' : 'Pesan Sekarang' }}
        </button>

      </div>
    </div>

<!-- Modal -->
<div
  v-if="showConfirm"
  class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
>
  <div class="bg-white w-80 p-6 rounded-lg shadow-lg text-center">
    <h3 class="text-lg font-semibold mb-4">Konfirmasi Pesanan</h3>
    <p class="text-sm text-gray-600 mb-6">
      Yakin untuk melanjutkan pemesanan?
    </p>

    <div class="flex gap-4">
      <button
        class="flex-1 py-2 bg-gray-300 rounded"
        @click="showConfirm = false"
      >
        Batal
      </button>

      <button
        class="flex-1 py-2 bg-[#183045] text-white rounded"
        @click="() => { showConfirm = false; createOrder(); }"
      >
        Ya, Lanjutkan
      </button>
    </div>
  </div>
</div>


    <FooterLayout />
  </div>
</template>