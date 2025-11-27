<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 flex justify-between items-center border-b">
      <h2 class="text-lg font-semibold text-gray-800">Products List</h2>
      <button @click="$emit('open-create')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg font-medium flex items-center gap-1">
        <i class="ri-add-line"></i> Add Product
      </button>
    </div>

    <table class="min-w-full divide-y divide-gray-100">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Product Name</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Category</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Stock</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Price</th>
          <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Status</th>
          <th class="px-4 py-3 text-right text-sm font-semibold text-gray-600">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 bg-white">
        <tr
          v-for="(product, index) in products"
          :key="product.id ?? index"
          class="hover:bg-gray-50 transition"
        >
          <td class="px-4 py-3 flex items-center gap-3">
            <input type="checkbox" class="rounded border-gray-300" />
            <img
              :src="product.image_url || placeholder"
              class="w-10 h-10 rounded-md object-cover bg-gray-100"
              alt="Product image"
            />
            <div class="flex flex-col">
              <span class="font-medium text-sm text-gray-900">{{ product.name }}</span>
              <span class="text-xs text-gray-500">{{ product.slug }}</span>
            </div>
          </td>

          <td class="px-4 py-3 text-sm text-gray-700">
            {{
              product.subkategori_product?.name ||
              product.subkategori?.name ||
              product.category ||
              '-'
            }}
          </td>

          <td class="px-4 py-3 text-sm">
            <span
              v-if="product.stock <= 0"
              class="text-red-600 font-medium"
            >Out of Stock</span>
            <span
              v-else-if="product.stock <= 10"
              class="text-yellow-600 font-medium"
            >{{ product.stock }} Low Stock</span>
            <span v-else>{{ product.stock }}</span>
          </td>

          <td class="px-4 py-3 text-sm font-semibold text-gray-800">
            {{ formatCurrency(product.price) }}
          </td>

          <td class="px-4 py-3">
            <span
              :class="[
                'px-2 py-1 rounded-full text-xs font-medium',
                product.is_active
                  ? 'bg-green-100 text-green-700'
                  : 'bg-red-100 text-red-600'
              ]"
            >
              {{ product.is_active ? 'Published' : 'Inactive' }}
            </span>
          </td>

          <td class="px-4 py-3 text-right">
            <div class="flex justify-end gap-2">
              <button
                class="p-1 text-gray-500 hover:text-blue-600"
                title="Edit"
                @click="$emit('open-edit', product)"
              >
                <i class="ri-edit-line"></i>
              </button>
              <button
                class="p-1 text-gray-500 hover:text-red-600"
                title="Delete"
                @click="$emit('open-delete', product)"
              >
                <i class="ri-delete-bin-line"></i>
              </button>
              <button
                class="p-1 text-gray-500 hover:text-gray-700"
                title="More"
                @click="$emit('open-full', product)"
              >
                <span class="text-lg">⋯</span>
              </button>
            </div>
          </td>
        </tr>

        <tr v-if="!products || products.length === 0">
          <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm">
            No products found.
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Footer / Pagination -->
    <div class="p-4 border-t flex items-center justify-between text-sm text-gray-600">
      <span>
        Showing {{ pagination.from }}–{{ pagination.to }} of {{ pagination.total }}
      </span>

      <div class="flex items-center gap-1">
        <button
          v-for="(link, i) in pagination.links"
          :key="i"
          :disabled="!link.url"
          @click="changePage(link.url)"
          class="px-3 py-1 border rounded text-sm"
          :class="[
            link.active ? 'bg-indigo-600 text-white' : 'hover:bg-gray-100',
            !link.url ? 'opacity-50 cursor-not-allowed' : ''
          ]"
        >
          {{ formatLabel(link.label) }}
        </button>
      </div>
    </div>

  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
  products: { type: Array, default: () => [] },
  pagination: { type: Object, default: () => ({}) }
});

const products = props.products;
const placeholder = 'https://via.placeholder.com/40?text=Img';

function changePage(url) {
  if (!url) return;
  router.visit(url, {
    preserveScroll: true,
    preserveState: true,
  });
}

function formatLabel(label) {
  return label
    .replace(/&laquo;/g, '«')
    .replace(/&raquo;/g, '»')
    .replace(/(<([^>]+)>)/gi, ""); // bersihkan semua HTML tag
}

function formatCurrency(value) {
  if (value === null || value === undefined) return '-';
  try {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(Number(value));
  } catch {
    return `${value}`;
  }
}
</script>
