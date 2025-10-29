<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import ProductTable from '@/components/ProductTable.vue';
import { ref } from 'vue';
import ProductModal from '@/components/ProductModal.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const products = page.props.products ?? [];
const categories = page.props.categories ?? [];

const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingProduct = ref(null);

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
</script>

<template>
  <AdminLayout>
    <ProductTable :products="products" @open-create="openCreate" @open-full="openEdit" />

    <ProductModal v-if="showCreateModal" :categories="categories" mode="create" @close="closeModal" />

    <ProductModal v-if="showEditModal" :categories="categories" :product="editingProduct" mode="edit" @close="closeModal" />
  </AdminLayout>
</template>


