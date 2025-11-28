<template>
  <div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 flex justify-between items-center border-b">
      <h2 class="text-lg font-semibold text-gray-800">Products List</h2>
      <button @click="$emit('open-create')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg font-medium flex items-center gap-1">
        <i class="ri-add-line"></i> Add Product
      </button>
    </div>

    <!-- Filters -->
    <div class="p-4 border-b flex flex-wrap gap-2 items-center">
      <div class="flex items-center gap-2">
        <input
          v-model.trim="searchQuery"
          type="text"
          placeholder="Search name, brand, slug..."
          class="w-64 px-3 py-2 border rounded-lg text-sm"
        />
        <select v-model="categoryFilter" class="px-3 py-2 border rounded-lg text-sm">
          <option value="">All Categories</option>
          <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
        </select>
        <select v-model="subcategoryFilter" class="px-3 py-2 border rounded-lg text-sm">
          <option value="">All Subcategories</option>
          <option v-for="sc in subcategories" :key="sc" :value="sc">{{ sc }}</option>
        </select>
        <select v-model="statusFilter" class="px-3 py-2 border rounded-lg text-sm">
          <option value="">All Status</option>
          <option value="active">Published</option>
          <option value="inactive">Inactive</option>
        </select>
        <select v-model="stockFilter" class="px-3 py-2 border rounded-lg text-sm">
          <option value="">All Stock</option>
          <option value="out">Out of Stock</option>
          <option value="low">Low (≤10)</option>
        </select>
      </div>
      <div class="ml-auto flex items-center gap-2">
        <label class="text-sm text-gray-600">Per page</label>
        <select v-model.number="perPage" class="px-3 py-2 border rounded-lg text-sm">
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
        </select>
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
      <tbody class="divide-y divide-gray-100 bg-white">
        <tr
          v-for="(product, index) in paginatedProducts"
          :key="product.id ?? index"
          class="hover:bg-gray-50 transition"
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
              product.subkategori?.name ||
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
        <tr v-if="filteredProducts.length === 0">
          <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-sm">
            No products found.
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Footer / Pagination -->
    <div class="p-4 border-t flex items-center justify-between text-sm text-gray-600">
      <span>
        Showing
        {{ startIndex + 1 }}–{{ Math.min(endIndex, filteredProducts.length) }}
        of {{ filteredProducts.length }}
      </span>
      <div class="flex items-center gap-1">
        <button
          class="px-2 py-1 border rounded text-gray-600 hover:bg-gray-50"
          :disabled="page <= 1"
          @click="goPrev"
        >Prev</button>
        <button
          v-for="p in totalPages"
          :key="p"
          class="px-2 py-1 border rounded"
          :class="p === page ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:bg-gray-50'"
          @click="goTo(p)"
        >{{ p }}</button>
        <button
          class="px-2 py-1 border rounded text-gray-600 hover:bg-gray-50"
          :disabled="page >= totalPages"
          @click="goNext"
        >Next</button>
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
});

const products = props.products;
const placeholder = 'https://via.placeholder.com/40?text=Img';

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

// Filters & pagination state
import { computed, ref, watch } from 'vue';

const searchQuery = ref('');
const categoryFilter = ref('');
const subcategoryFilter = ref('');
const statusFilter = ref(''); // '', 'active', 'inactive'
const stockFilter = ref(''); // '', 'out', 'low'

const page = ref(1);
const perPage = ref(10);

// Reset to first page when filters change
watch([searchQuery, categoryFilter, subcategoryFilter, statusFilter, stockFilter, perPage], () => {
  page.value = 1;
});

// Clear subcategory when category changes
watch(categoryFilter, () => {
  subcategoryFilter.value = '';
});

// Categories derived from relationship: subkategori.kategori.name
const categories = computed(() => {
  const names = new Set();
  products.forEach((p) => {
    const name = p.subkategori?.kategori?.name;
    if (name) names.add(String(name));
  });
  return Array.from(names).sort((a, b) => a.localeCompare(b));
});

// Subcategories: filter by selected category
const subcategories = computed(() => {
  const names = new Set();
  const normalize = (s) => (s ? String(s).trim().toLowerCase() : '');
  
  products.forEach((p) => {
    const catName = p.subkategori?.kategori?.name;
    const subName = p.subkategori?.name;
    
    // If category is selected, only include subcategories from that category
    if (categoryFilter.value) {
      if (normalize(catName) === normalize(categoryFilter.value) && subName) {
        names.add(String(subName));
      }
    } else {
      // If no category selected, show all subcategories
      if (subName) names.add(String(subName));
    }
  });
  
  return Array.from(names).sort((a, b) => a.localeCompare(b));
});

const filteredProducts = computed(() => {
  const q = searchQuery.value.toLowerCase();
  return products.filter((p) => {
    const matchesSearch = !q
      || [p.name, p.slug, p.brand]
        .filter(Boolean)
        .some((v) => String(v).toLowerCase().includes(q));

    const catName = p.subkategori?.kategori?.name;
    const subcatName = p.subkategori?.name;

    // Normalize for safer comparison
    const normalize = (s) => (s ? String(s).trim().toLowerCase() : '');
    const matchesCategory =
      !categoryFilter.value || normalize(categoryFilter.value) === normalize(catName);
    const matchesSubcategory = !subcategoryFilter.value || normalize(subcategoryFilter.value) === normalize(subcatName);

    const matchesStatus =
      !statusFilter.value ||
      (statusFilter.value === 'active' && p.is_active) ||
      (statusFilter.value === 'inactive' && !p.is_active);

    const matchesStock =
      !stockFilter.value ||
      (stockFilter.value === 'out' && Number(p.stock) <= 0) ||
      (stockFilter.value === 'low' && Number(p.stock) > 0 && Number(p.stock) <= 10);

    return matchesSearch && matchesCategory && matchesSubcategory && matchesStatus && matchesStock;
  });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredProducts.value.length / perPage.value)));
const startIndex = computed(() => (page.value - 1) * perPage.value);
const endIndex = computed(() => page.value * perPage.value);
const paginatedProducts = computed(() => filteredProducts.value.slice(startIndex.value, endIndex.value));

function goPrev() {
  if (page.value > 1) page.value -= 1;
}
function goNext() {
  if (page.value < totalPages.value) page.value += 1;
}
function goTo(p) {
  page.value = p;
}
</script>
