<script setup>
import { ref, computed, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'

const codChecked = ref(false)

// Cart state loaded from backend
const loading = ref(false)
const error = ref(null)
const cart = ref({ items: [], subtotal: 0, item_count: 0 })

const serviceFee = ref(2000)

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

onMounted(fetchCart)
</script>

<template>
  <div class="min-h-screen bg-white pt-20">
    <HeaderLayout />

    <h1 class="text-2xl font-semibold px-8 pt-8">Pesanan Anda</h1>

    <div class="px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 py-10">

      <!-- Bagian Keranjang -->
      <div class="space-y-6">
        <div v-if="loading" class="text-gray-500">Memuat keranjang…</div>
        <div v-else-if="error" class="text-red-500">{{ error }}</div>
        <div v-else-if="cart.items.length === 0" class="text-gray-500">Keranjangmu kosong.</div>

        <div
          v-for="item in cart.items"
          :key="item.id"
          class="flex gap-4 border-b pb-4"
        >
          <img :src="item.image" class="w-24 h-24 object-cover rounded" />

          <div class="flex-1">
            <p class="font-semibold text-lg">{{ item.name }}</p>
            <p class="text-sm">Jumlah : {{ item.qty }}</p>
            <p class="font-semibold mt-1">Rp. {{ Number(item.unit_price || item.unit_price_snapshot || 0).toLocaleString() }}</p>
          </div>

          <button @click="removeItem(item.id)" class="text-sm text-red-500 hover:underline">
            Hapus
          </button>
        </div>
      </div>

      <!-- Bagian Informasi Pemesanan -->
      <div class="border-l px-10">
        <h2 class="text-xl font-semibold mb-6 text-center">Informasi Pemesanan</h2>

        <!-- Checkbox -->
        <label class="flex items-start gap-3 border p-4 cursor-pointer">
          <input type="checkbox" v-model="codChecked" class="mt-1" />
          <div>
            <p class="font-medium">Cash On Delivery</p>
            <p class="text-sm text-gray-600">Bayar Barang di Toko</p>
          </div>
        </label>

        <!-- Perhitungan -->
        <div class="mt-6 space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Subtotal</span>
            <span>Rp. {{ subtotal.toLocaleString() }}</span>
          </div>
          <div class="flex justify-between">
            <span>Biaya Layanan</span>
            <span>Rp. {{ serviceFee.toLocaleString() }}</span>
          </div>
          <hr />
          <div class="flex justify-between font-semibold text-lg">
            <span>Total</span>
            <span>Rp. {{ total.toLocaleString() }}</span>
          </div>
        </div>

        <!-- Tombol Pesan -->
        <Link
          href="/checkout/confirm"
          :class="codChecked ? 'bg-black hover:bg-gray-800' : 'bg-gray-400 cursor-not-allowed'"
          class="mt-8 w-full py-3 text-center text-white font-semibold block"
          :disabled="!codChecked"
        >
          Pesan Sekarang
        </Link>

      </div>
    </div>

    <FooterLayout />
  </div>
</template>
