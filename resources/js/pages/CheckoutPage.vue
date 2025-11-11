<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'

const codChecked = ref(false)

const cartItems = ref([
  {
    id: 1,
    name: "Kipas Karakter",
    spec: "...",
    qty: 1,
    price: 100000,
    image: "/img/kipas.png"
  },
  {
    id: 2,
    name: "Blender",
    spec: "...",
    qty: 1,
    price: 150000,
    image: "/img/blender.png"
  },
  {
    id: 3,
    name: "Lampu Tidur",
    spec: "...",
    qty: 1,
    price: 8000,
    image: "/img/lampu.png"
  }
])

const serviceFee = ref(2000)

const subtotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.price, 0)
)

const total = computed(() => subtotal.value + serviceFee.value)

const removeItem = (id) => {
  cartItems.value = cartItems.value.filter(i => i.id !== id)
}
</script>

<template>
  <div class="min-h-screen bg-white pt-20">
    <HeaderLayout />

    <h1 class="text-2xl font-semibold px-8 pt-8">Pesanan Anda</h1>

    <div class="px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 py-10">

      <!-- Bagian Keranjang -->
      <div class="space-y-6">
        <div
          v-for="item in cartItems"
          :key="item.id"
          class="flex gap-4 border-b pb-4"
        >
          <img :src="item.image" class="w-24 h-24 object-cover rounded" />

          <div class="flex-1">
            <p class="font-semibold text-lg">{{ item.name }}</p>
            <p class="text-sm text-gray-500">Spesifikasi : {{ item.spec }}</p>
            <p class="text-sm">Jumlah : {{ item.qty }}</p>
            <p class="font-semibold mt-1">Rp. {{ item.price.toLocaleString() }}</p>
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
