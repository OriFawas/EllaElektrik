<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold">Product</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Created At</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Brand</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Category</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Qty</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Status</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Price</th>
          <th class="px-4 py-2 text-right text-sm font-semibold">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in products" :key="index" class="border-t hover:bg-gray-50">
          <td class="px-4 py-2 flex items-center gap-2">
            <img :src="product.image" alt="" class="w-8 h-8 rounded-full" />
            {{ product.name }}
          </td>
          <td class="px-4 py-2 text-sm text-gray-600">{{ product.created_at }}</td>
          <td class="px-4 py-2 text-sm">{{ product.brand }}</td>
          <td class="px-4 py-2 text-sm">{{ product.category }}</td>
          <td class="px-4 py-2 text-sm">{{ product.qty }}</td>
          <td class="px-4 py-2">
            <span :class="product.status === 'Active' ? 'text-green-600' : 'text-red-600'">
              {{ product.status }}
            </span>
          </td>
          <td class="px-4 py-2 text-sm font-semibold">{{ product.price }}</td>
          <td class="px-4 py-2 text-right flex justify-end gap-3">
            <button class="text-blue-600"><i class="ri-edit-line"></i></button>
            <button class="text-red-600"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const products = [
  {
    name: 'Product 1',
    image: 'https://via.placeholder.com/40',
    created_at: 'Mon, Aug 21, 2025',
    brand: 'Brand 1',
    category: 'Category 1',
    qty: 12467,
    status: 'Active',
    price: '$50',
  },
  {
    name: 'Product 2',
    image: 'https://via.placeholder.com/40',
    created_at: 'Mon, Aug 21, 2025',
    brand: 'Brand 2',
    category: 'Category 2',
    qty: 654,
    status: 'Inactive',
    price: '$40',
  },
];
</script>
