<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-[#183045] text-white flex flex-col">
      <div class="p-4 border-b border-gray-700">
        <h1 class="text-xl font-semibold text-center">User Dashboard</h1>
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

    <!-- Main content -->
    <main class="flex-1 p-8">
      <slot />
    </main>
  </div>
</template>

<script setup>
</script>
