<script setup>
import { Link } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">

    <!-- Header -->
    <HeaderLayout />

    <!-- Konten -->
    <main class="flex-1">
      <h1 class="text-2xl font-semibold px-8 pt-8">Keranjangmu</h1>

      <div class="px-8 grid grid-cols-1 lg:grid-cols-2 gap-10 py-10">

        <!-- Daftar Item -->
        <div class="space-y-6">
          <div
            v-for="item in cartItems"
            :key="item.id"
            class="flex gap-4 border-b pb-4"
          >
            <img :src="item.image" class="w-24 h-24 object-cover rounded">

            <div class="flex-1">
              <p class="font-semibold text-lg">{{ item.name }}</p>
              <p class="text-sm">Jumlah : {{ item.qty }}</p>
              <p class="font-semibold">Rp. {{ item.price.toLocaleString() }}</p>
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

            <div v-for="item in cartItems" :key="item.id" class="flex justify-between">
              <span>{{ item.name }}</span>
              <span>Rp. {{ item.price.toLocaleString() }}</span>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between">
              <span>Subtotal</span>
              <span>Rp. {{ subtotal.toLocaleString() }}</span>
            </div>

            <div class="flex justify-between">
              <span>Biaya Layanan</span>
              <span>Rp. 2.000</span>
            </div>

            <hr class="my-2" />

            <div class="flex justify-between font-semibold text-lg">
              <span>Total</span>
              <span>Rp. {{ (subtotal + 2000).toLocaleString() }}</span>
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
      cartItems: [
        { id: 1, name: "Kipas Karakter", qty: 1, price: 100000, image: "/img/kipas.png" },
        { id: 2, name: "Blender", qty: 1, price: 150000, image: "/img/blender.png" },
        { id: 3, name: "Lampu Tidur", qty: 1, price: 8000, image: "/img/lampu.png" },
      ],
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
