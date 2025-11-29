<script setup>
import { Head, Link } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'

const props = defineProps({
  activeOrders: Array,
  historyOrders: Array,
  rejectedOrders: Array,
})
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">
    <HeaderLayout />
    <Head title="Riwayat Pesanan" />

    <UserLayout>
      <div class="bg-white p-8 rounded-lg shadow w-full space-y-10">

        <!-- Bagian 1: Pesanan Aktif -->
        <div>
          <h1 class="text-2xl font-semibold mb-6">Pesanan Aktif</h1>

          <div v-if="props.activeOrders.length" class="space-y-4">
            <Link
              v-for="order in props.activeOrders"
              :key="order.id"
              :href="`/orders/${order.id}`"
              class="flex items-center justify-between border-b pb-4 hover:bg-gray-50 transition-colors cursor-pointer"
            >
              <div class="flex items-center space-x-4">
                <img
                  :src="order.image"
                  alt="Produk"
                  class="w-20 h-20 object-cover rounded-md"
                />
                <div>
                  <p class="text-lg font-medium">{{ order.name }}</p>
                  <p class="text-gray-600 text-sm">{{ order.date }}</p>

                  <!-- Status hanya Siap Diambil -->
                  <p class="text-green-600 text-sm font-semibold mt-1">
                    {{ order.status }} ({{ order.days_left }} hari tersisa)
                  </p>
                </div>
              </div>

              <p class="text-gray-800 font-semibold">{{ order.price }}</p>
            </Link>
          </div>

          <div v-else class="text-center text-gray-500 py-10">
            Tidak ada pesanan aktif.
          </div>
        </div>

        <!-- Bagian 2: Riwayat Pesanan -->
        <div>
          <h1 class="text-2xl font-semibold mb-6">Pesanan Selesai</h1>

          <div v-if="props.historyOrders.length" class="space-y-4">
            <div
              v-for="order in props.historyOrders"
              :key="order.id"
              class="flex items-center justify-between border-b pb-4"
            >
              <div class="flex items-center space-x-4">
                <img
                  :src="order.image"
                  alt="Produk"
                  class="w-20 h-20 object-cover rounded-md"
                />
                <div>
                  <p class="text-lg font-medium">{{ order.name }}</p>
                  <p class="text-gray-600 text-sm">{{ order.date }}</p>
                </div>
              </div>

              <p class="text-gray-800 font-semibold">{{ order.price }}</p>
            </div>
          </div>

          <div v-else class="text-center text-gray-500 py-10">
            Belum ada riwayat pesanan.
          </div>
        </div>

        <!-- Bagian 3: Pesanan Ditolak -->
        <div>
          <h1 class="text-2xl font-semibold mb-6">Pesanan Ditolak</h1>

          <div v-if="props.rejectedOrders && props.rejectedOrders.length" class="space-y-4">
            <div
              v-for="order in props.rejectedOrders"
              :key="order.id"
              class="flex items-center justify-between border-b pb-4 bg-red-50 rounded-md px-4 py-3"
            >
              <div class="flex items-center space-x-4">
                <img
                  :src="order.image"
                  alt="Produk"
                  class="w-20 h-20 object-cover rounded-md ring-2 ring-red-200"
                />
                <div>
                  <p class="text-lg font-medium">{{ order.name }}</p>
                  <p class="text-gray-600 text-sm">{{ order.date }}</p>
                  <span class="inline-flex items-center gap-2 mt-2 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                    <i class="fas fa-ban"></i> {{ order.status }}
                  </span>
                </div>
              </div>

              <div class="text-right">
                <p class="text-gray-800 font-semibold">{{ order.price }}</p>
                <p v-if="order.reason" class="text-xs text-red-600 mt-1">Alasan: {{ order.reason }}</p>
              </div>
            </div>
          </div>

          <div v-else class="text-center text-gray-500 py-10">
            Tidak ada pesanan ditolak.
          </div>
        </div>

      </div>
    </UserLayout>

    <FooterLayout />
  </div>
</template>
