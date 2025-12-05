<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside
            class="w-64 bg-[#183045] text-white flex flex-col min-h-screen fixed md:static top-0 left-0
                    transform transition-transform duration-300
                    md:translate-x-0 z-50 md:z-auto overflow-y-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            >
            <div class="p-4 pt-6 text-xl font-semibold flex justify-between items-center flex-shrink-0">
            Ella Elektrik
            <button class="md:hidden" @click="sidebarOpen = false">✕</button>
            </div>

            <nav class="flex-1 overflow-y-auto">
                <ul>
                    <li>
                        <Link
                            href="/admin/dashboard"
                            :class="[
                                'block px-4 py-2 hover:bg-gray-700 transition-all duration-150',
                                $page.url.startsWith('/admin/dashboard')
                                    ? 'bg-blue-600 font-semibold border-l-4 border-blue-500'
                                    : ''
                            ]"
                        >
                            Dashboard
                        </Link>
                    </li>

                    <li>
                        <Link
                            href="/admin/products"
                            :class="[
                                'block px-4 py-2 hover:bg-gray-700 transition-all duration-150',
                                $page.url.startsWith('/admin/products')
                                    ? 'bg-blue-600 font-semibold border-l-4 border-blue-500'
                                    : ''
                            ]"
                        >
                            Produk
                        </Link>
                    </li>

                    <li>
                        <Link
                            href="/admin/orders"
                            :class="[
                                'block px-4 py-2 hover:bg-gray-700 transition-all duration-150',
                                $page.url.startsWith('/admin/orders')
                                    ? 'bg-blue-600 font-semibold border-l-4 border-blue-500'
                                    : ''
                            ]"
                        >
                            Pesanan
                        </Link>
                    </li>

                    <li>
                        <Link
                            href="/admin/users"
                            :class="[
                                'block px-4 py-2 hover:bg-gray-700 transition-all duration-150',
                                 $page.url.startsWith('/admin/users')
                                    ? 'bg-blue-600 font-semibold border-l-4 border-blue-500'
                                    : ''
                            ]"
                        >
                            User
                        </Link>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="sticky top-0 z-50 bg-white shadow flex items-center justify-between px-6 py-4">
                <button class="md:hidden text-2xl" @click="sidebarOpen = true">
                ☰
                </button>


                <!-- Search -->
                <div class="hidden sm:flex items-center gap-2 w-1/3">
                    <i class="ri-search-line text-gray-400 text-lg"></i>
                    <input
                        type="text"
                        placeholder="Search..."
                        class="w-full border rounded-lg px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- Right side -->
                <div class="flex items-center gap-4">
                    <!-- Notifikasi -->
                    <button class="relative">
                        <img
                            src="/images/notifadmin.png"
                            alt="Notifikasi"
                            class="w-6 h-6 object-contain hover:opacity-80"
                        />
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full"
                        >
                            3
                        </span>
                    </button>

                    <!-- Profil -->
                    <div class="relative">
                        <div
                            class="flex items-center gap-2 cursor-pointer"
                            @click="showDropdown = !showDropdown"
                        >
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-gray-500 text-sm font-medium">
                                AD
                            </span>
                            <span class="hidden sm:block font-medium text-gray-700">Admin</span>
                            <i class="ri-arrow-down-s-line text-gray-600"></i>
                        </div>

                        <!-- Dropdown menu -->
                        <transition name="fade">
                            <div
                                v-if="showDropdown"
                                class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg border border-gray-200 z-50"
                            >
                                

                                <button
                                    @click="logout"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
                                >
                                    Logout
                                </button>
                            </div>
                        </transition>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 md:p-6 flex-1 overflow-x-auto">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const sidebarOpen = ref(false)
const showDropdown = ref(false)
const page = usePage()
const currentUrl = computed(() => page.url.split('?')[0]) 

const logout = () => {
    router.post('/logout', {}, {
        onFinish: () => router.visit('/')
    })
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
