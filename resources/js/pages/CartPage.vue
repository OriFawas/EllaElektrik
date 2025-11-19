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

onMounted(fetchCart)
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">

    <!-- Header -->
    <HeaderLayout />

    <!-- Konten -->
    <main class="flex-1">
      <h1 class="text-2xl font-semibold px-8 pt-8">Keranjangmu</h1>

      <!-- Loading / Error / Empty states -->
      <div v-if="loading" class="px-8 py-16 text-center text-gray-500">Memuat keranjang…</div>
      <div v-else-if="error" class="px-8 py-16 text-center text-red-500">{{ error }}</div>
      <div v-else-if="cart.items.length === 0" class="px-8 py-16 text-center text-gray-500">
        Keranjangmu kosong.
      </div>

      <div v-else class="px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 py-10">

        <!-- Daftar Item -->
        <div class="space-y-6">
          <div
            v-for="item in cart.items"
            :key="item.id"
            class="flex gap-4 border-b pb-4"
          >
            <img :src="item.image" class="w-24 h-24 object-cover rounded">

            <div class="flex-1">
              <p class="font-semibold text-lg">{{ item.name }}</p>
              <div class="flex items-center gap-3 text-sm">
                <button @click="updateQty(item.id, Math.max(1, item.qty - 1))" class="px-2 py-1 border rounded">-</button>
                <span>Jumlah : {{ item.qty }}</span>
                <button @click="updateQty(item.id, Math.min(99, item.qty + 1))" class="px-2 py-1 border rounded">+</button>
              </div>
              <p class="font-semibold">Rp. {{ Number(item.unit_price || item.unit_price_snapshot || 0).toLocaleString() }}</p>
            </div>

            <button @click="removeItem(item.id)" class="text-red-500 text-sm hover:underline">
              Hapus
            </button>
          </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="border-l px-10">
          <h2 class="text-xl font-semibold mb-6 text-center">Ringkasan Pesanan</h2>

          <div class="text-sm space-y-2 mb-6">

            <div v-for="item in cart.items" :key="item.id" class="flex justify-between">
              <span>{{ item.name }}</span>
              <span>Rp. {{ Number(item.unit_price || item.unit_price_snapshot || 0).toLocaleString() }} × {{ item.qty }}</span>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>Rp. {{ Number(cart.subtotal).toLocaleString() }}</span>
            </div>

            <div class="flex justify-between">
              <span>Biaya Layanan</span>
              <span>Rp. 2.000</span>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between font-semibold text-lg">
              <span>Total</span>
              <span>Rp. {{ (Number(cart.subtotal) + 2000).toLocaleString() }}</span>
            </div>
          </div>

          <Link
            href="/checkout"
            class="block w-full py-3 text-center text-white font-semibold bg-black hover:bg-gray-800 rounded"
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
