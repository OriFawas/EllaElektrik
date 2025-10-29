<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 text-xl font-semibold">Ella Elektrik</div>
            <nav class="flex-1">
                <ul>
                    <li>
                        <Link href="/admin/dashboard" class="block px-4 py-2 hover:bg-gray-700">Dashboard</Link>
                    </li>
                    <li>
                        <Link href="/admin/products" class="block px-4 py-2 hover:bg-gray-700">Produk</Link>
                    </li>
                    <li>
                        <Link href="/admin/orders" class="block px-4 py-2 hover:bg-gray-700">Pesanan</Link>
                    </li>
                    <li>
                        <Link href="/admin/users" class="block px-4 py-2 hover:bg-gray-700">User</Link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow flex items-center justify-between px-6 py-4">
                <!-- Search -->
                <div class="flex items-center gap-2 w-1/3">
                    <i class="ri-search-line text-gray-400 text-lg"></i>
                    <input type="text" placeholder="Search..."
                        class="w-full border rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4">
                    <!-- Notifikasi -->
                    <button class="relative">
                        <img src="/images/notifadmin.png" alt="Notifikasi"
                            class="w-6 h-6 object-contain hover:opacity-80" />
                        <!-- Badge (opsional) -->
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            3
                        </span>
                    </button>

                    <!-- Profil -->
                    <div class="relative">
                        <div class="flex items-center gap-2 cursor-pointer" @click="showDropdown = !showDropdown">
                            <img src="/images/profileadmin.png" alt="Profile"
                                class="w-9 h-9 rounded-full border border-gray-300 object-cover" />
                            <span class="font-medium text-gray-700">Admin</span>
                            <i class="ri-arrow-down-s-line text-gray-600"></i>
                        </div>

                        <!-- Dropdown menu -->
                        <transition name="fade">
                            <div v-if="showDropdown"
                                class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200 z-50">
                                <Link href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Lihat
                                Profil</Link>

                                <button @click="logout"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </div>
                        </transition>
                    </div>

                </div>
            </header>


            <!-- Page content -->
            <main class="p-6 flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const showDropdown = ref(false)

// fungsi logout
const logout = () => {
    router.post('/logout', {}, {
        onFinish: () => {
            // Setelah logout berhasil, arahkan ke landing page
            router.visit('/')
        }
    })
}
</script>


<style scoped>
a.active {
    background-color: #1f2937;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
