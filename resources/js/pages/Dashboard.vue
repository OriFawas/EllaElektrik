<template>
  <AdminLayout>
    <div class="space-y-6">

      <!-- Judul -->
      <h1 class="text-xl md:text-2xl font-semibold">Dashboard</h1>

      <!-- ================= TOP STATS ================= -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white shadow p-4 rounded-lg">
          <p class="text-sm text-gray-500">Total Pesanan</p>
          <h2 class="text-2xl md:text-3xl font-bold mt-1">120</h2>
          <p class="text-sm text-gray-500">from last month</p>
        </div>

        <div class="bg-white shadow p-4 rounded-lg flex flex-col justify-center">
          <p class="text-sm text-gray-500">Active Orders</p>
          <h2 class="text-xl font-semibold">1</h2>
        </div>

        <div class="bg-white shadow p-4 rounded-lg flex flex-col justify-center">
          <p class="text-sm text-gray-500">Completed Orders</p>
          <h2 class="text-xl font-semibold">108</h2>
        </div>

        <div class="bg-white shadow p-4 rounded-lg flex flex-col justify-center">
          <p class="text-sm text-gray-500">Canceled Orders</p>
          <h2 class="text-xl font-semibold">108</h2>
        </div>
      </div>

      <!-- ================= GRAFIK + BEST SELLER ================= -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">

        <!-- Grafik -->
        <div class="bg-white shadow p-4 rounded-lg lg:col-span-2">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-3 gap-3">
            <h3 class="font-semibold">Sale Graph</h3>
            <div class="flex flex-wrap gap-2">
              <button class="px-3 py-1 text-sm border rounded-lg">Weekly</button>
              <button class="px-3 py-1 text-sm border rounded-lg bg-blue-600 text-white">Monthly</button>
              <button class="px-3 py-1 text-sm border rounded-lg">Yearly</button>
            </div>
          </div>

          <div class="h-36 sm:h-40 md:h-48 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm">
            Grafik Nanti Disini
          </div>
        </div>

        <!-- Best Sellers -->
        <div class="bg-white shadow p-4 rounded-lg">
          <h3 class="font-semibold mb-3">Best Sellers</h3>

          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-300 rounded"></div>
            <div>
              <p class="text-sm md:text-base">Lampu LED</p>
              <span class="text-sm text-gray-500">100 terjual</span>
            </div>
          </div>

          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-300 rounded"></div>
            <div>
              <p>Kabel</p>
              <span class="text-sm text-gray-500">90 terjual</span>
            </div>
          </div>

          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-300 rounded"></div>
            <div>
              <p>Stop Kontak</p>
              <span class="text-sm text-gray-500">80 terjual</span>
            </div>
          </div>

          <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg w-full">
            Report
          </button>
        </div>
      </div>

      <!-- ================= RECENT ORDERS ================= -->
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="font-semibold mb-4">Recent Orders</h3>

        <!-- Wrapper agar table bisa scroll di HP -->
        <div class="overflow-x-auto">
          <table class="min-w-full text-left text-sm">
            <thead>
              <tr class="border-b text-gray-600 whitespace-nowrap">
                <th class="py-2 px-2">Product</th>
                <th class="px-2">Order ID</th>
                <th class="px-2">Date</th>
                <th class="px-2">Customer</th>
                <th class="px-2">Status</th>
                <th class="px-2">Amount</th>
              </tr>
            </thead>

            <tbody>
              <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                <td class="py-2 px-2">xxxxx</td>
                <td class="px-2">#25426</td>
                <td class="px-2">Nov 8th, 2023</td>
                <td class="px-2">Kavin</td>
                <td class="px-2 text-blue-600">Proses</td>
                <td class="px-2">Rp 200.000</td>
              </tr>

              <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                <td class="py-2 px-2">xxxxx</td>
                <td class="px-2">#25425</td>
                <td class="px-2">Nov 7th, 2023</td>
                <td class="px-2">Komael</td>
                <td class="px-2 text-green-600">Selesai</td>
                <td class="px-2">Rp 200.000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
</script>
