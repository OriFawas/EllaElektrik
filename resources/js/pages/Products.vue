<template>
  <AdminLayout>
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-semibold">Products List</h1>
      <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
        + Add Product
      </button>
    </div>

    <ProductTable />
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import ProductTable from '@/components/ProductTable.vue';
</script>
