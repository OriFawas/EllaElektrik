<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue';
import ProductTable from '@/components/ProductTable.vue';
import { ref, watch, computed, onMounted } from 'vue';
import ProductModal from '@/components/ProductModal.vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();

// =========== DATA BACKEND ===========
const products = computed(() => page.props.products?.data ?? []);
const pagination = computed(() => page.props.products ?? {});
const categories = computed(() => page.props.categories ?? []);

// =========== FILTER STATE ===========
const selectedCategory = ref('');
const selectedSubcategory = ref('');
const subcategories = ref([]);

// Restore filter dari backend
const initialFilters = page.props.filters || {};
selectedCategory.value = initialFilters.category || "";
selectedSubcategory.value = initialFilters.subcategory || "";

// =========== LOAD SUBKATEGORI ===========
function loadSubcategories() {
  if (!selectedCategory.value) {
    subcategories.value = [];
    selectedSubcategory.value = "";
    return;
  }

  const cat = categories.value.find(c => c.id == selectedCategory.value);
  subcategories.value = cat ? cat.subkategories : [];
}

// 🔥 WAJIB — agar subkategori muncul setelah reload
onMounted(() => {
  loadSubcategories();

  // Jika subkategori sudah pernah dipilih, pastikan tetap terset
  if (selectedSubcategory.value) {
    const exists = subcategories.value.find(s => s.id == selectedSubcategory.value);
    if (!exists) selectedSubcategory.value = "";
  }
});

// Watch kategori → update subkategori + filter
watch(selectedCategory, () => {
  loadSubcategories();
  selectedSubcategory.value = "";
  applyFilter();
});

// Watch subkategori → filter
watch(selectedSubcategory, () => {
  applyFilter();
});

// =========== APPLY FILTER ===========
function applyFilter() {
  router.get(
    "/admin/products",
    {
      category: selectedCategory.value,
      subcategory: selectedSubcategory.value,
    },
    {
      preserveState: true,
      replace: true,
    }
  );
}

// =========== MODAL ===========
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
    <!-- FILTER AREA -->
    <div class="flex items-center gap-4 mb-6 p-4 bg-white rounded shadow">
      <!-- Kategori -->
      <div>
        <label class="block text-sm font-semibold mb-1">Kategori</label>
        <select v-model="selectedCategory" class="border rounded p-2 w-48">
          <option value="">Semua Kategori</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">
            {{ c.name }}
          </option>
        </select>
      </div>

      <!-- Subkategori -->
      <div v-if="subcategories.length > 0">
        <label class="block text-sm font-semibold mb-1">Subkategori</label>
        <select v-model="selectedSubcategory" class="border rounded p-2 w-48">
          <option value="">Semua Subkategori</option>
          <option v-for="s in subcategories" :key="s.id" :value="s.id">
            {{ s.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- PRODUCT TABLE -->
    <ProductTable 
      :products="products" 
      :pagination="pagination"
      @open-create="openCreate" 
      @open-full="openEdit" 
    />

    <!-- CREATE MODAL -->
    <ProductModal
      v-if="showCreateModal"
      :categories="categories"
      mode="create"
      @close="closeModal"
    />

    <!-- EDIT MODAL -->
    <ProductModal
      v-if="showEditModal"
      :categories="categories"
      :product="editingProduct"
      mode="edit"
      @close="closeModal"
    />
  </AdminLayout>
</template>
