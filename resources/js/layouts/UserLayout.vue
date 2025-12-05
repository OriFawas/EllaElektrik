<template>
  <div class="flex min-h-screen bg-gray-100">

    <!-- Sidebar (desktop) -->
    <aside class="hidden md:flex w-64 bg-[#183045] text-white flex-col">
      <div class="p-4 border-b border-gray-700">
        <h1 class="text-xl font-semibold text-center pt-7">User Dashboard</h1>
      </div>

      <nav class="p-4 space-y-2">
        <a
          href="/user/dashboard"
          class="block px-4 py-2 rounded font-medium text-white hover:bg-gray-700 transition"
          :class="{ 'bg-blue-600': $page.url.startsWith('/user/dashboard') }"
        >
          Profile
        </a>

        <a
          href="/user/orders"
          class="block px-4 py-2 rounded font-medium text-white hover:bg-gray-700 transition"
          :class="{ 'bg-blue-600': $page.url.startsWith('/user/orders') }"
        >
          Pesanan
        </a>
      </nav>
    </aside>


    <!-- Mobile sidebar overlay -->
    <div
      v-if="openSidebar"
      class="fixed inset-0 bg-black bg-opacity-40 z-40 md:hidden"
      @click="openSidebar = false"
    ></div>

    <!-- Sidebar mobile (slide in) -->
    <aside
      class="fixed inset-y-0 left-0 w-64 bg-[#183045] text-white flex flex-col transform transition-transform duration-300 z-50 md:hidden"
      :class="{ '-translate-x-full': !openSidebar, 'translate-x-0': openSidebar }"
    >
      <div class="p-4 border-b border-gray-700 flex items-center justify-between">
        <h1 class="text-xl font-semibold">User Dashboard</h1>
        <button @click="openSidebar = false" class="text-white text-2xl">&times;</button>
      </div>

      <nav class="p-4 space-y-2">
        <a
          href="/user/dashboard"
          class="block px-4 py-2 rounded font-medium text-white hover:bg-gray-700 transition"
          :class="{ 'bg-blue-600': $page.url.startsWith('/user/dashboard') }"
        >
          Profile
        </a>

        <a
          href="/user/orders"
          class="block px-4 py-2 rounded font-medium text-white hover:bg-gray-700 transition"
          :class="{ 'bg-blue-600': $page.url.startsWith('/user/orders') }"
        >
          Pesanan
        </a>
      </nav>
    </aside>


    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      
      <!-- Top bar (mobile only) -->
      <header class="md:hidden bg-[#183045] text-white p-4 flex items-center">
        <button
          @click="openSidebar = true"
          class="mr-4 text-2xl focus:outline-none"
        >
          ☰
        </button>
        <h1 class="text-lg font-semibold">Dashboard</h1>
      </header>

      <main class="flex-1 p-8">
        <slot />
      </main>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const openSidebar = ref(false)
</script>
