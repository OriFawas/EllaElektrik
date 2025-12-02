<script setup lang="ts">
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
  <div class="min-h-screen w-full flex">

    <!-- LEFT SIDE -->
    <div
      class="hidden lg:flex w-1/2 bg-[#123458] text-white px-40 py-15 flex-col justify-center items-center pr-6"
    >

      <!-- Logo produk -->
      <img
        src="/images/produklogo.png"
        alt="Logo Produk"
        class="w-[550px] drop-shadow-xl mb-2"
      />

      <!-- Title & Description -->
      <div class="space-y-4 text-left">
    <h1 class="text-3xl font-bold">
        {{ title ?? "Solusi Elektronik Terlengkap" }}
    </h1>

    <p class="text-white/80 text-sm leading-relaxed max-w-md">
        {{ description ?? "Belanja Elektronik Mudah & Terpercaya, semua ada di Ella Elektrik." }}
    </p>
</div>

    </div>

    <!-- RIGHT SIDE (Slot / Form Login atau Register) -->
    <div class="flex-1 flex items-center justify-center bg-[#123458] px-6 py-10">
      <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
        <slot />
        </div>
      </div>
    </div>

  </div>
</template>
