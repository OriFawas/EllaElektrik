<template>
  <AdminLayout>
    <div class="space-y-6">

      <!-- Judul -->
      <h1 class="text-2xl font-semibold">Dashboard</h1>

      <!-- Top Stats -->
      <div class="grid grid-cols-4 gap-4">
        <div class="bg-white shadow p-4 rounded-lg">
          <p class="text-sm text-gray-500">Total Pesanan</p>
          <h2 class="text-3xl font-bold mt-1">120</h2>
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

      <!-- Grafik & Best Seller -->
      <div class="grid grid-cols-3 gap-4">
        
        <!-- Grafik Placeholder -->
        <div class="bg-white shadow p-4 rounded-lg col-span-2">
          <div class="flex justify-between mb-3">
            <h3 class="font-semibold">Sale Graph</h3>
            <div class="space-x-2">
              <button class="px-3 py-1 text-sm border rounded-lg">Weekly</button>
              <button class="px-3 py-1 text-sm border rounded-lg bg-blue-600 text-white">Monthly</button>
              <button class="px-3 py-1 text-sm border rounded-lg">Yearly</button>
            </div>
          </div>
          <div class="h-48 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm">
            Grafik Nanti Disini
          </div>
        </div>

        <!-- Best Sellers -->
        <div class="bg-white shadow p-4 rounded-lg">
          <h3 class="font-semibold mb-3">Best Sellers</h3>

          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 bg-gray-300 rounded"></div>
            <div>
              <p>Lampu LED</p>
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

          <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg w-full">Report</button>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white shadow rounded-lg p-4">
        <h3 class="font-semibold mb-4">Recent Orders</h3>

        <table class="min-w-full text-left">
          <thead>
            <tr class="border-b text-sm text-gray-600">
              <th class="py-2">Product</th>
              <th>Order ID</th>
              <th>Date</th>
              <th>Customer</th>
              <th>Status</th>
              <th>Amount</th>
            </tr>
          </thead>

          <tbody class="text-sm">
            <tr class="border-b hover:bg-gray-50">
              <td class="py-2">xxxxx</td>
              <td>#25426</td>
              <td>Nov 8th, 2023</td>
              <td>Kavin</td>
              <td class="text-blue-600">Proses</td>
              <td>Rp 200.000</td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
              <td class="py-2">xxxxx</td>
              <td>#25425</td>
              <td>Nov 7th, 2023</td>
              <td>Komael</td>
              <td class="text-green-600">Selesai</td>
              <td>Rp 200.000</td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
</script>
