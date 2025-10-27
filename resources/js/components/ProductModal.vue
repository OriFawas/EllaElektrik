<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="fixed inset-0 bg-black/40" @click="close"></div>

    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl z-10 overflow-hidden">
          <div class="p-4 flex items-center justify-between border-b">
            <h3 class="text-lg font-semibold">{{ props.mode === 'edit' ? 'Edit Product' : 'Add Product' }}</h3>
            <button class="text-gray-500" @click="close">✕</button>
          </div>

      <form @submit.prevent="submit" class="p-4 space-y-4" enctype="multipart/form-data">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="mt-1 block w-full border rounded px-2 py-1" />
            <p v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Price (IDR)</label>
            <input v-model="form.price" type="number" class="mt-1 block w-full border rounded px-2 py-1" />
            <p v-if="form.errors.price" class="text-red-600 text-sm">{{ form.errors.price }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Stock</label>
            <input v-model="form.stock" type="number" class="mt-1 block w-full border rounded px-2 py-1" />
            <p v-if="form.errors.stock" class="text-red-600 text-sm">{{ form.errors.stock }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Active</label>
            <select v-model="form.is_active" class="mt-1 block w-full border rounded px-2 py-1">
              <option :value="true">Published</option>
              <option :value="false">Inactive</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Category</label>
          <select v-model="selectedCategoryId" @change="onCategoryChange" class="mt-1 block w-full border rounded px-2 py-1">
            <option value="">-- Select category --</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Subcategory</label>
          <select v-model="form.subkategori_product_id" class="mt-1 block w-full border rounded px-2 py-1">
            <option value="">-- Select subcategory --</option>
            <option v-for="sub in subkategories" :key="sub.id" :value="sub.id">{{ sub.name }}</option>
          </select>
          <p v-if="form.errors.subkategori_product_id" class="text-red-600 text-sm">{{ form.errors.subkategori_product_id }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Image</label>
          <input type="file" @change="onFileChange" accept="image/*" class="mt-1 block w-full" />
          <p v-if="form.errors.image" class="text-red-600 text-sm">{{ form.errors.image }}</p>
          <div v-if="props.mode === 'edit' && props.product && props.product.image_url" class="mt-2">
            <img :src="props.product.image_url" class="w-28 h-20 object-cover rounded" alt="current" />
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea v-model="form.description" class="mt-1 block w-full border rounded px-2 py-1"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Watt</label>
            <input v-model="form.watt" type="number" class="mt-1 block w-full border rounded px-2 py-1" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Brand</label>
            <input v-model="form.brand" type="text" class="mt-1 block w-full border rounded px-2 py-1" />
          </div>
        </div>

        <div class="flex justify-end gap-2 items-center">
          <button type="button" class="px-4 py-2 border rounded" @click="close">Cancel</button>
          <button v-if="props.mode === 'edit'" type="button" class="px-4 py-2 bg-red-600 text-white rounded" @click="removeProduct">Remove</button>
          <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">{{ props.mode === 'edit' ? 'Update' : 'Save' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  categories: { type: Array, default: () => [] },
  product: { type: Object, default: null },
  mode: { type: String, default: 'create' },
});

const emit = defineEmits(['close']);

const form = useForm({
  name: props.product?.name ?? '',
  description: props.product?.description ?? '',
  price: props.product?.price ?? 0,
  stock: props.product?.stock ?? 0,
  is_active: props.product?.is_active ?? true,
  subkategori_product_id: props.product?.subkategori_product_id ?? null,
  watt: props.product?.watt ?? null,
  brand: props.product?.brand ?? '',
  image: null,
});

const selectedCategoryId = ref(props.product?.subkategori?.kategori?.id ?? '');
const subkategories = ref((() => {
  const cat = props.categories.find((c) => String(c.id) === String(selectedCategoryId.value));
  return cat?.subkategories ?? [];
})());

watch(() => props.categories, (n) => {
  // update subcategories when categories prop changes
  if (selectedCategoryId.value) {
    const cat = props.categories.find((c) => String(c.id) === String(selectedCategoryId.value));
    subkategories.value = cat?.subkategories ?? [];
  }
});

watch(() => props.product, (p) => {
  if (!p) return;
  // reset form values when editing a different product
  form.reset({
    name: p.name ?? '',
    description: p.description ?? '',
    price: p.price ?? 0,
    stock: p.stock ?? 0,
    is_active: p.is_active ?? true,
    subkategori_product_id: p.subkategori_product_id ?? null,
    watt: p.watt ?? null,
    brand: p.brand ?? '',
    image: null,
  });
  selectedCategoryId.value = p.subkategori?.kategori?.id ?? '';
  const cat = props.categories.find((c) => String(c.id) === String(selectedCategoryId.value));
  subkategories.value = cat?.subkategories ?? [];
});

function onCategoryChange() {
  const cat = props.categories.find((c) => String(c.id) === String(selectedCategoryId.value));
  subkategories.value = cat?.subkategories ?? [];
  // clear selected subcategory when category changes
  form.subkategori_product_id = null;
}

function onFileChange(e) {
  const file = e.target.files && e.target.files[0];
  if (file) {
    form.image = file;
  }
}

function close() {
  emit('close');
}

function submit() {
  // attach subkategori_product_id is already bound
  if (props.mode === 'edit' && props.product) {
    form.put(`/admin/products/${props.product.id}`, {
      onSuccess: () => {
        close();
      },
    });
  } else {
    form.post('/admin/products', {
      onSuccess: () => {
        close();
      },
    });
  }
}

function removeProduct() {
  if (!props.product) return;
  if (!confirm('Are you sure you want to remove this product?')) return;
  form.delete(`/admin/products/${props.product.id}`, {
    onSuccess: () => {
      close();
    },
  });
}
</script>

<style scoped>
/* keep minimal styling, tailwind is used in markup */
</style>
