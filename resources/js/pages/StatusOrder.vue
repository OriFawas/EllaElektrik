<script setup>
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import InlineNotice from '@/components/InlineNotice.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const props = defineProps({
  order: Object
})

const flashSuccess = computed(() => page.props.flash?.success ?? null)
const flashError = computed(() => page.props.flash?.error ?? null)
const queryNotice = computed(() => { try { return new URL(window.location.href).searchParams.get('notice') } catch (e) { return null } })

const noticeData = computed(() => {
  if (flashSuccess.value) return { title: 'Berhasil', text: flashSuccess.value, type: 'success', cls: 'bg-green-50 text-green-700' }
  if (flashError.value) return { title: 'Gagal', text: flashError.value, type: 'error', cls: 'bg-red-50 text-red-700' }
  if (queryNotice.value === 'order_success') return { title: 'Pesanan Berhasil', text: 'Pesanan Anda berhasil dibuat.', type: 'success', cls: 'bg-green-50 text-green-700' }
  return null
})

// Use days_left from backend
const daysLeft = computed(() => {
  if (props.order.status !== "ST1") return null
  return props.order.days_left
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 text-gray-800 mt-20">
    <HeaderLayout />

    <section class="container mx-auto text-center py-16 px-6">
      <div class="flex justify-center mb-6">
        <InlineNotice v-if="noticeData" :notice="noticeData" @dismiss="() => {}" />
      </div>
      <h2 class="text-2xl font-semibold mb-6">Informasi Pemesanan</h2>

      <p>ID. Order : {{ props.order.id }}</p>
      <p>Tanggal Pemesanan : {{ props.order.tanggal }}</p>

      <div class="max-w-lg mx-auto text-sm mt-10">
        <div class="flex justify-between mb-2">
          <span>Subtotal</span>
          <span>Rp. {{ Number(props.order.subtotal).toLocaleString() }}</span>
        </div>

        <div class="flex justify-between mb-2">
          <span>Biaya Layanan</span>
          <span>Rp. {{ Number(props.order.biayaLayanan).toLocaleString() }}</span>
        </div>

        <div class="border-t my-4"></div>

        <div class="flex justify-between font-semibold">
          <span>Total</span>
          <span>Rp. {{ Number(props.order.total).toLocaleString() }}</span>
        </div>
      </div>

      <!-- STATUS -->
      <div class="mt-14">
        <p class="font-medium mb-4">Status Pesanan :</p>

        <div class="flex justify-center items-center gap-6 text-lg">

          <span :class="props.order.status === 'ST1' ? 'font-extrabold' : 'text-gray-400'">
            Pesanan Bisa Diambil
          </span>

          <div class="w-16 h-[2px] bg-gray-400"></div>

          <span :class="props.order.status === 'ST2' ? 'font-extrabold' : 'text-gray-400'">
            Pesanan Selesai
          </span>

        </div>

        <!-- Hitung Mundur (hanya saat ST1) -->
        <p v-if="props.order.status === 'ST1'" class="mt-4 text-sm text-gray-600">
          {{ daysLeft }} hari tersisa
        </p>

      </div>
    </section>

    <FooterLayout />
  </div>
</template>