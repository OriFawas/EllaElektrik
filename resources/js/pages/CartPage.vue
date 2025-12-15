<script setup>
import { Link } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, onMounted } from 'vue'

const loading = ref(false)
const error = ref(null)
const cart = ref({ items: [], subtotal: 0, item_count: 0 })

const fetchCart = async () => {
  loading.value = true
  error.value = null
  try {
  const res = await fetch('/api/cart', {
    credentials: 'same-origin',
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
  })
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

const updateQty = async (itemId, qty) => {
  try {
    const res = await fetch(`/api/cart/items/${itemId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      credentials: 'same-origin',
      body: JSON.stringify({ qty })
    })
    if (res.status === 401) { window.location.href = '/login'; return }
    if (res.status === 419) { window.location.reload(); return }
    if (!res.ok) throw new Error('Gagal memperbarui jumlah')
    const data = await res.json()
    cart.value = { items: data.items, subtotal: data.subtotal, item_count: data.item_count }
  } catch (e) { console.error(e) }
}

const removeItem = async (itemId) => {
  try {
    const res = await fetch(`/api/cart/items/${itemId}`, {
      method: 'DELETE',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
      },
      credentials: 'same-origin',
    })
    if (res.status === 401) { window.location.href = '/login'; return }
    if (res.status === 419) { window.location.reload(); return }
    if (!res.ok) throw new Error('Gagal menghapus item')
    const data = await res.json()
    cart.value = { items: data.items, subtotal: data.subtotal, item_count: data.item_count }
  } catch (e) { console.error(e) }
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
  <div class="min-h-screen bg-white flex flex-col pt-20">

    <!-- Header -->
    <HeaderLayout />

    <!-- Konten -->
    <main class="flex-1">
      <h1 class="text-2xl font-semibold px-8 pt-8">Keranjangmu</h1>
      <div class="px-8 mt-2">
      <Link 
        href="/shop/Elektronik-Rumah-Tangga" 
        class="text-blue-600 hover:underline text-lg font-medium"
      >
        Lanjut Belanja
      </Link>
    </div>


      <!-- Loading / Error / Empty states -->
      <div v-if="loading" class="px-8 py-16 text-center text-gray-500">Memuat keranjang…</div>
      <div v-else-if="error" class="px-8 py-16 text-center text-red-500">{{ error }}</div>
      <div v-else-if="cart.items.length === 0" class="px-8 py-16 text-center text-gray-500">
        Keranjangmu kosong.
      </div>

      <div class="px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10 py-8 auto-rows-max">
        <!-- Daftar Item (Table) -->
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
                <td class="px-4 py-3 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <button @click="updateQty(item.id, Math.max(1, item.qty - 1))" class="px-2 py-1 border rounded hover:bg-gray-100">-</button>
                    <span class="w-8 text-center font-medium">{{ item.qty }}</span>
                    <button @click="updateQty(item.id, Math.min(99, item.qty + 1))" class="px-2 py-1 border rounded hover:bg-gray-100">+</button>
                  </div>
                </td>
                <td class="px-4 py-3 text-right">Rp. {{ formatCurrency(Number(item.unit_price || item.unit_price_snapshot || 0)) }}</td>
                <td class="px-4 py-3 text-right font-semibold">Rp. {{ formatCurrency(Number((item.unit_price || item.unit_price_snapshot || 0) * item.qty)) }}</td>
                <td class="px-4 py-3 text-center">
                  <button @click="removeItem(item.id)" class="text-red-500 hover:underline text-sm">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Ringkasan Pesanan (Table) -->
        <div v-if="!loading && cart.items.length > 0" class="bg-white border rounded-lg shadow-sm p-6 h-84">
          <h2 class="text-lg font-semibold mb-6">Ringkasan Pesanan</h2>

          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <span class="text-gray-700 font-medium">Subtotal</span>
              <span class="text-lg font-bold text-gray-800">Rp. {{ formatCurrency(Number(cart.subtotal)) }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-gray-700 font-medium">Biaya Layanan</span>
              <span class="text-lg font-bold text-gray-800">Rp. 2.000</span>
            </div>

            <div class="flex justify-between items-center border-t pt-6">
              <span class="text-lg font-bold text-gray-800">Total</span>
              <span class="text-2xl font-bold text-blue-900">Rp. {{ formatCurrency(Number(cart.subtotal) + 2000) }}</span>
            </div>
          </div>

          <Link
            href="/checkout"
            class="block w-full py-3 text-center text-white font-semibold bg-[#183045] hover:bg-gray-800 rounded-lg mt-6"
          >
            Selanjutnya
          </Link>
        </div>

      </div>
    </main>

    <!-- Footer -->
    <FooterLayout />

  </div>
</template>

<script>
export default {
  name: "CartPage",
  data() {
    return {
      cartItems: [],
    }
  },
  computed: {
    subtotal() {
      return this.cartItems.reduce((t, i) => t + i.price, 0)
    }
  },
  methods: {
    removeItem(id) {
      this.cartItems = this.cartItems.filter(i => i.id !== id)
    }
  }
}
</script>
