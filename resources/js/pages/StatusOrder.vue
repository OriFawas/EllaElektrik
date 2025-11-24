<script setup>
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed } from 'vue'

const order = ref({
  id: "ORD-2025-001",
  tanggal: "2025-11-19", // pakai format YYYY-MM-DD untuk hitung tanggal
  subtotal: 258000,
  biayaLayanan: 0,
  total: 258000,
  status: "ST1" // ST1 = dibuat, ST2 = selesai
})

// Hitung sisa hari untuk ST1 (7 hari)
const daysLeft = computed(() => {
  if (order.value.status !== "ST1") return null

  const today = new Date()
  const startDate = new Date(order.value.tanggal)

  const endDate = new Date(startDate)
  endDate.setDate(endDate.getDate() + 7)

  const difference = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24))
  return difference > 0 ? difference : 0
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 mt-20">
    <HeaderLayout />

    <section class="container mx-auto text-center py-16 px-6">
      <h2 class="text-2xl font-semibold mb-6">Informasi Pemesanan</h2>

      <p>ID. Order : {{ order.id }}</p>
      <p>Tanggal Pemesanan : {{ order.tanggal }}</p>

      <div class="max-w-lg mx-auto text-sm mt-10">
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span>Rp. {{ order.subtotal.toLocaleString() }}</span>
        </div>

        <div class="flex justify-between mb-2">
          <span>Biaya Layanan</span>
          <span>Rp. {{ order.biayaLayanan.toLocaleString() }}</span>
        </div>

        <div class="border-t my-4"></div>

        <div class="flex justify-between font-semibold">
          <span>Total</span>
          <span>Rp. {{ order.total.toLocaleString() }}</span>
        </div>
      </div>

      <!-- STATUS -->
      <div class="mt-14">
        <p class="font-medium mb-4">Status Pesanan :</p>

        <div class="flex justify-center items-center gap-6 text-lg">

          <span :class="order.status === 'ST1' ? 'font-extrabold' : 'text-gray-400'">
            Pesanan Bisa Diambil
          </span>

          <div class="w-16 h-[2px] bg-gray-400"></div>

          <span :class="order.status === 'ST2' ? 'font-extrabold' : 'text-gray-400'">
            Pesanan Selesai
          </span>

        </div>

        <!-- Hitung Mundur (hanya saat ST1) -->
        <p v-if="order.status === 'ST1'" class="mt-4 text-sm text-gray-600">
          {{ daysLeft }} hari tersisa
        </p>

      </div>

      <button class="mt-10 bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition">
        Batalkan Pesanan
      </button>
    </section>

    <FooterLayout />
  </div>
</template>