<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import ProductTable from '@/components/ProductTable.vue';
import { ref, computed, watch } from 'vue';
import ProductModal from '@/components/ProductModal.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const products = page.props.products ?? [];
const categories = page.props.categories ?? [];

// UI state for create/edit modals
const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingProduct = ref(null);

// Filter state
const selectedCategoryId = ref('');
const selectedSubcategoryId = ref('');

// Derived list of subcategories for the selected category
const subcategories = computed(() => {
  if (!selectedCategoryId.value) return [];
  const cat = categories.find((c) => String(c.id) === String(selectedCategoryId.value));
  return cat?.subkategories ?? cat?.subkategories ?? [];
});

// Compute filtered products by selected category/subcategory
const filteredProducts = computed(() => {
  return (products || []).filter((p) => {
    // If no filter selected, include all
    if (!selectedCategoryId.value && !selectedSubcategoryId.value) return true;

    // helper to get product's category id (try a few common shapes)
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

    // If subcategory filter set, it must match
    if (selectedSubcategoryId.value) {
      return String(prodSubId) === String(selectedSubcategoryId.value);
    }

    // If only category filter set, match category id
    if (selectedCategoryId.value) {
      return String(prodCategoryId) === String(selectedCategoryId.value);
    }

    return true;
  });
});

// Reset subcategory when category changes
watch(selectedCategoryId, (val) => {
  if (!val) selectedSubcategoryId.value = '';
});

function openCreate() {
  showCreateModal.value = true;
}

function openEdit(product) {
  editingProduct.value = product;
  showEditModal.value = true;
}

function closeModal() {
  showCreateModal.value = false;
  showEditModal.value = false;
  editingProduct.value = null;
}

function clearFilters() {
  selectedCategoryId.value = '';
  selectedSubcategoryId.value = '';
}
</script>

<template>
  <AdminLayout>
    <ProductTable :products="products" :categories="categories" @open-create="openCreate" @open-full="openEdit" />

    <ProductModal v-if="showCreateModal" :categories="categories" mode="create" @close="closeModal" />

    <ProductModal v-if="showEditModal" :categories="categories" :product="editingProduct" mode="edit" @close="closeModal" />
  </AdminLayout>
</template>


