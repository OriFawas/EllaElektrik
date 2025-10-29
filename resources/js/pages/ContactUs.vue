<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  message: '',
});

function handleSubmit() {
  console.log('Data form:', form.value);
  alert('Pesan terkirim! (Belum ke server)');
}
</script>

<template>
  <Head title="Contact Us - Ella Elektrik" />

  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
      <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Contact Us</h1>

      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2">
          <!-- KIRI -->
          <div class="bg-[#183045] text-white p-8">
            <h2 class="text-xl font-semibold mb-6">Contact Information</h2>
            <div class="space-y-4">
              <div class="flex items-center space-x-3">
                <i class="fas fa-phone"></i>
                <span>+1012 3456 789</span>
              </div>
              <div class="flex items-center space-x-3">
                <i class="fas fa-envelope"></i>
                <span>demo@gmail.com</span>
              </div>
            </div>
          </div>

          <!-- KANAN -->
          <div class="p-8">
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <!-- First Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input
                  v-model="form.firstName"
                  type="text"
                  placeholder=""
                  class=" text-black w-full border-b border-gray-300 focus:border-blue-500 outline-none pb-1"
                />
              </div>

              <!-- Last Name -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input
                  v-model="form.lastName"
                  type="text"
                  placeholder=""
                  class="text-black w-full border-b border-gray-300 focus:border-blue-500 outline-none pb-1"
                />
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  placeholder=""
                  class="text-black w-full border-b border-gray-300 focus:border-blue-500 outline-none pb-1"
                />
              </div>

              <!-- Message -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea
                  v-model="form.message"
                  placeholder="Write your message.."
                  rows="4"
                  class="text-black w-full border-b border-gray-300 focus:border-blue-500 outline-none pb-1 resize-none"
                ></textarea>
              </div>

              <div class="pt-4">
                <button
                  type="submit"
                  class="w-full bg-[#183045] text-white py-2 rounded hover:bg-[#1c3b55] transition"
                >
                  Send Message
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>