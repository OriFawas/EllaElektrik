<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref, onMounted } from 'vue'

const search = ref('')

const users = ref([
  { id: 1, name: 'User A', email: 'userA@mail.com', phone: '0888888888', address: 'Jakarta', avatar:'/images/avatar1.png', attachment:'KTP_UserA.pdf', status: 'Sudah Terverifikasi' },
  { id: 2, name: 'User B', email: 'userB@mail.com', phone: '0899999999', address: 'Bandung', avatar:'/images/avatar2.png', attachment:'KTP_UserB.pdf', status: 'Perlu Diverifikasi' },
  { id: 3, name: 'User C', email: 'userC@mail.com', phone: '0877777777', address: 'Surabaya', avatar:'/images/avatar3.png', attachment:'- ', status: 'Inactive' },
])

onMounted(() => {
  if (window.lucide) window.lucide.createIcons()
})
</script>

<template>
  <AdminLayout>
    <div class="bg-white rounded-xl shadow-sm p-6">
      <h2 class="text-xl font-semibold mb-6">Profil User</h2>

      <div class="overflow-x-auto rounded-lg border">
        <table class="w-full text-sm min-w-max">
          <thead class="bg-gray-100 text-gray-600">
            <tr>
              <th class="px-4 py-3 w-10"></th>
              <th class="px-4 py-3 text-left">Nama Lengkap</th>
              <th class="px-4 py-3 text-left">Email</th>
              <th class="px-4 py-3 text-left">Nomor Telp</th>
              <th class="px-4 py-3 text-left">Alamat</th>
              <th class="px-4 py-3 text-left">Lampiran</th>
              <th class="px-4 py-3 text-left">Status</th>
              <th class="px-4 py-3 text-center w-24">Action</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b hover:bg-gray-50 transition">
              <td class="px-4 py-4">
                <input type="checkbox" class="rounded" />
              </td>

              <td class="px-4 py-4 flex items-center gap-3">
                <img :src="user.avatar" class="w-10 h-10 rounded-full object-cover border" />
                <div>
                  <div class="font-medium">{{ user.name }}</div>
                  <div class="text-xs text-gray-400">{{ user.id }}</div>
                </div>
              </td>

              <td class="px-4 py-4">{{ user.email }}</td>
              <td class="px-4 py-4">{{ user.phone }}</td>
              <td class="px-4 py-4">{{ user.address }}</td>

              <td class="px-4 py-4 text-blue-500 underline cursor-pointer">
                {{ user.attachment }}
              </td>

              <td class="px-4 py-4">
                <span
                  class="text-sm font-medium flex items-center gap-1"
                  :class="{
                    'text-green-600': user.status === 'Sudah Terverifikasi',
                    'text-yellow-500': user.status === 'Perlu Diverifikasi',
                    'text-red-500': user.status === 'Inactive'
                  }"
                >
                  <i data-lucide="check-circle"></i>
                  {{ user.status }}
                </span>
              </td>

              <td class="px-4 py-4 text-center flex items-center gap-3 justify-center">
                <button class="text-blue-500 hover:text-blue-700">
                  <i data-lucide="edit" class="w-5 h-5"></i>
                </button>

                <button class="text-red-500 hover:text-red-600">
                  <i data-lucide="trash" class="w-5 h-5"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </AdminLayout>
</template>

<style scoped>
table th {
  font-weight: 600;
}
</style>
