<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue'; // pastikan path-nya benar

// optional: breadcrumb
const breadcrumbs = [
  { title: 'Dashboard', href: '/admin/dashboard' },
];
</script>

<template>
  <Head title="Admin Dashboard" />

  <AdminLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4 overflow-x-auto rounded-xl h-full flex-1">
      <div class="grid md:grid-cols-3 gap-4 auto-rows-min">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-300">
          <PlaceholderPattern />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-300">
          <PlaceholderPattern />
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-gray-300">
          <PlaceholderPattern />
        </div>
      </div>
      <div class="relative flex-1 min-h-[100vh] rounded-xl border border-gray-300">
        <PlaceholderPattern />
      </div>
    </div>
  </AdminLayout>
</template>
