<script setup>
import HeaderLayout from '../components/HeaderLayout.vue'
import MainContent from '../components/MainContent.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
</script>

<template>
  <div>
    <HeaderLayout :isLoggedIn="!!user" />
    <!-- konten lainnya -->

     <!-- Main content -->
    <main class="flex-grow">
      <MainContent />
    </main>

    <!-- Footer -->
    <FooterLayout />
  </div>
</template>
