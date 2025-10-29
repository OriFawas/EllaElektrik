<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 flex flex-col gap-3 border-b">
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-800">Products List</h2>
        <button @click="$emit('open-create')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg font-medium flex items-center gap-1">
          <i class="ri-add-line"></i> Add Product
        </button>
      </div>

      <!-- Filters -->
      <div class="flex items-center gap-3">
        <div class="w-64">
          <label class="block text-sm text-gray-600">Category</label>
          <select v-model="selectedCategoryId" class="mt-1 block w-full border rounded px-2 py-1">
            <option value="">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>

        <div class="w-64">
          <label class="block text-sm text-gray-600">Subcategory</label>
          <select v-model="selectedSubcategoryId" class="mt-1 block w-full border rounded px-2 py-1">
            <option value="">All Subcategories</option>
            <option v-for="sub in availableSubcategories" :key="sub.id" :value="sub.id">{{ sub.name }}</option>
          </select>
        </div>

        <div class="flex items-end">
          <button class="px-3 py-1 border rounded text-sm" @click.prevent="clearFilters">Clear</button>
        </div>
      </div>
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
      <transition-group tag="tbody" name="list" class="divide-y divide-gray-100 bg-white">
        <tr
          v-for="(product, index) in displayedProducts"
          :key="product.id ?? index"
          class="hover:bg-gray-50 transition-transform duration-200 ease-out group"
        >
          <!-- Product name & image -->
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

          <!-- Category -->
          <td class="px-4 py-3 text-sm text-gray-700">
            {{
              product.subkategori_product?.name ||
              product.subkategori?.name ||
              product.category ||
              '-'
            }}
          </td>

          <!-- Stock -->
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

          <!-- Price -->
          <td class="px-4 py-3 text-sm font-semibold text-gray-800">
            {{ formatCurrency(product.price) }}
          </td>

          <!-- Status -->
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

          <!-- Actions -->
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

              <!-- Ellipsis button to open full product modal (view/edit/remove) -->
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

        <!-- Empty state -->
        <tr v-if="!displayedProducts || displayedProducts.length === 0">
          <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm">
            No products found.
          </td>
        </tr>
      </transition-group>
    </table>

    <!-- Footer / Pagination -->
    <div class="p-4 border-t flex items-center justify-between text-sm text-gray-600">
      <span>Showing {{ displayedProducts.length || 0 }} result(s)</span>
      <div class="flex items-center gap-1">
        <button class="px-2 py-1 border rounded text-gray-600 hover:bg-gray-50">Prev</button>
        <button class="px-2 py-1 border rounded bg-indigo-600 text-white">1</button>
        <button class="px-2 py-1 border rounded text-gray-600 hover:bg-gray-50">Next</button>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  products: {
    type: Array,
    default: () => [],
  },
  categories: {
    type: Array,
    default: () => [],
  },
});

import { ref, computed } from 'vue';

const products = props.products;
const categories = props.categories;
const placeholder = 'https://via.placeholder.com/40?text=Img';

// Filter state
const selectedCategoryId = ref('');
const selectedSubcategoryId = ref('');

const availableSubcategories = computed(() => {
  if (!selectedCategoryId.value) return [];
  const cat = categories.find((c) => String(c.id) === String(selectedCategoryId.value));
  return cat?.subkategories ?? [];
});

const displayedProducts = computed(() => {
  return (products || []).filter((p) => {
    // no filters
    if (!selectedCategoryId.value && !selectedSubcategoryId.value) return true;

    const prodCategoryId = (
      p.subkategori?.kategori?.id ??
      p.subkategori_product?.kategori?.id ??
      p.subkategori?.kategori_id ??
      p.kategori_id ??
      null
    );

    const prodSubId = (
      p.subkategori_product_id ??
      p.subkategori_product?.id ??
      p.subkategori?.id ??
      null
    );

    if (selectedSubcategoryId.value) {
      return String(prodSubId) === String(selectedSubcategoryId.value);
    }

    if (selectedCategoryId.value) {
      return String(prodCategoryId) === String(selectedCategoryId.value);
    }

    return true;
  });
});

function clearFilters() {
  selectedCategoryId.value = '';
  selectedSubcategoryId.value = '';
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

<style scoped>
/* Row enter/leave/move animations for transition-group 'list' */
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(8px) scale(0.995);
}
.list-enter-to,
.list-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}
.list-enter-active,
.list-leave-active {
  transition: transform 220ms cubic-bezier(.2,.8,.2,1), opacity 220ms ease;
}
.list-move {
  transition: transform 220ms cubic-bezier(.2,.8,.2,1);
}

/* Hover micro-interactions */
.group:hover td img {
  transform: scale(1.05);
}
td img {
  transition: transform 180ms ease;
}

/* Subtle button focus/active states */
button:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99,102,241,0.12);
}
</style>
