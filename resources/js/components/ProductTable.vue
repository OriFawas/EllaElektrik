<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <table class="min-w-full">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Product</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Created At</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Brand</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Category</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Stock</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Status</th>
          <th class="px-4 py-2 text-left text-sm font-semibold text-black">Watt</th>
          <th class="px-4 py-2 text-left text-sm font-semibold">Price</th>
          <th class="px-4 py-2 text-right text-sm font-semibold">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(product, index) in products" :key="product.id ?? index" class="border-t hover:bg-gray-50">
          <td class="px-4 py-2 flex items-center gap-2">
            <img :src="product.image_url || placeholder" alt="" class="w-8 h-8 rounded-full object-cover bg-gray-100" />
            <div class="flex flex-col">
              <span class="font-medium text-sm text-black">{{ product.name }}</span>
              <span class="text-xs text-gray-500">{{ product.slug }}</span>
            </div>
          </td>
          <td class="px-4 py-2 text-sm text-gray-600">{{ formatDate(product.created_at) }}</td>
          <td class="px-4 py-2 text-sm">{{ product.brand }}</td>
          <td class="px-4 py-2 text-sm">
            {{
              product.subkategori_product?.name ||
              product.subkategori?.name ||
              product.category ||
              '-' 
            }}
          </td>
          <td class="px-4 py-2 text-sm">{{ product.stock }}</td>
          <td class="px-4 py-2">
            <span :class="(product.is_active ? 'text-green-600' : 'text-red-600') + ' text-sm font-medium'">
              {{ product.is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td class="px-4 py-2 text-sm">{{ product.watt ?? '-' }}</td>
          <td class="px-4 py-2 text-sm font-semibold">{{ formatCurrency(product.price) }}</td>
          <td class="px-4 py-2 text-right flex justify-end gap-3">
            <button class="text-blue-600"><i class="ri-edit-line"></i></button>
            <button class="text-red-600"><i class="ri-delete-bin-line"></i></button>
          </td>
        </tr>
        <tr v-if="!products || products.length === 0">
          <td colspan="8" class="px-4 py-6 text-center text-gray-500 text-sm">No products found.</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
// Accept products from parent (Inertia page should provide these)
const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
});

const products = props.products;

const placeholder = 'https://via.placeholder.com/40?text=Img';

function formatCurrency(value) {
  if (value === null || value === undefined) return '-';
  try {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(value));
  } catch (e) {
    return `${value}`;
  }
}

function formatDate(value) {
  if (!value) return '-';
  try {
    const d = new Date(value);
    return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: '2-digit' });
  } catch (e) {
    return value;
  }
}
</script>
