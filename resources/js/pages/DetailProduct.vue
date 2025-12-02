<template>
    <div class="min-h-screen bg-[#F5F7FA]">

        <Head>
            <title>{{ pageTitle }}</title>
            <meta name="description" :content="metaDescription" />
            <meta property="og:title" :content="pageTitle" />
            <meta property="og:description" :content="metaDescription" />
            <meta property="og:image" :content="mainImage" />
        </Head>

        <!-- Header -->
        <HeaderLayout />

        <!-- Main Content -->
        <main class="container mx-auto px-8 pt-28 pb-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Kolom Kiri: Gambar Produk -->
                <div class="flex flex-col items-center justify-center">
                    <img :src="mainImage" :alt="product.name"
                        class="w-full max-w-md rounded-xl shadow-md object-contain mb-6" />

                    <!-- Gambar Lain / Thumbnail -->
                    <div v-if="product.gallery && product.gallery.length > 1" class="flex space-x-4 mt-4">
                        <img v-for="(img, i) in product.gallery" :key="i" :src="img" alt="Thumbnail"
                            @click="selectImage(i)"
                            :class="['w-28 h-28 rounded-lg object-cover cursor-pointer border transition',
                                     selectedIndex === i ? 'border-black' : 'border-gray-200 hover:border-gray-400']" />
                    </div>
                </div>

                <!-- Kolom Kanan: Detail Produk -->
                <div class="flex flex-col justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ product.name }}</h1>
                        <p class="text-2xl font-semibold text-gray-800 mb-1">
                            Rp. {{ formatPrice(product.price) }}
                        </p>
                        <p class="text-sm mb-4">
                            <span :class="[
                                'px-2 py-0.5 rounded-full',
                                product.stock <= 0 ? 'bg-red-100 text-red-700' : (product.stock <= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700')
                            ]">
                                Stok: {{ product.stock ?? 0 }}
                            </span>
                        </p>

                        <p class="text-gray-600 mb-6 leading-relaxed">
                            {{ product.description }}
                        </p>

                        <!-- Spesifikasi Produk -->
                        <div v-if="product.specs && product.specs.length" class="mb-6">
                            <h3 class="font-semibold mb-3 text-lg">Spesifikasi Produk</h3>
                            <ul class="list-disc list-inside text-gray-700 leading-relaxed space-y-1">
                                <li v-for="(spec, i) in product.specs" :key="i">{{ spec }}</li>
                            </ul>
                        </div>

                        <!-- Bagian Tombol & Jumlah -->
                        <div class="flex flex-col gap-4">
                            <!-- Baris: Masukkan Keranjang + Jumlah -->
                            <div class="flex flex-wrap items-center gap-4">
                                <!-- Tombol Masukkan Keranjang -->
                                <button
                                    @click="addToCart"
                                    :disabled="(product.stock ?? 0) <= 0"
                                    :class="['flex items-center justify-center gap-2 px-6 py-3 rounded-md transition',
                                             (product.stock ?? 0) <= 0 ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-black text-white hover:bg-gray-800']">
                                    <i class="fas fa-cart-plus"></i>
                                    {{ (product.stock ?? 0) <= 0 ? 'Stok Habis' : 'Masukkan Keranjang' }}
                                </button>

                                <!-- Jumlah -->
                                <div class="flex items-center space-x-3 border border-gray-300 rounded-md">
                                    <button @click="decreaseQty" class="px-3 py-1 text-lg font-bold hover:bg-gray-200">
                                        −
                                    </button>
                                    <span class="px-4">{{ quantity }}</span>
                                    <button @click="increaseQty" class="px-3 py-1 text-lg font-bold hover:bg-gray-200">
                                        +
                                    </button>
                                </div>
                            </div>

                            <!-- Tombol Bandingkan Produk -->
                            <Link :href="`/compare-products?left=${product.id}`"
                                class="flex items-center justify-center gap-2 border bg-black text-white px-6 py-3 rounded-md hover:bg-gray-800 transition w-fit">
                            <i class="fas fa-scale-balanced"></i> Bandingkan Produk
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Toast Notification -->
        <div v-if="toast.show" class="fixed top-24 left-1/2 -translate-x-1/2 z-[60]">
            <div :class="['px-5 py-3 rounded-md shadow-md text-white', toast.type === 'error' ? 'bg-red-600' : 'bg-emerald-600']" role="status" aria-live="polite">
                {{ toast.message }}
            </div>
        </div>

        <!-- Footer -->
        <FooterLayout />
    </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, computed } from 'vue'

// Receive product from Inertia
const props = defineProps({
    product: {
        type: Object,
        required: true,
        default: () => ({})
    }
})

// Quantity state
const quantity = ref(1)
const increaseQty = () => {
    const max = Number.isFinite(props.product?.stock) ? Number(props.product.stock) : Infinity
    if (quantity.value < max) quantity.value++
}
const decreaseQty = () => { if (quantity.value > 1) quantity.value-- }

// Image gallery state
const selectedIndex = ref(0)
const selectImage = (i) => { selectedIndex.value = i }
const mainImage = computed(() => {
    const g = props.product?.gallery || []
    if (g.length > 0) return g[selectedIndex.value] || g[0]
    return props.product?.image || '/images/placeholder.png'
})

// SEO
const pageTitle = computed(() => `Detail Produk - ${props.product?.name || 'Produk'} | Ella Elektrik`)
const metaDescription = computed(() => (props.product?.description || '').toString().slice(0, 160))

// Price formatting
const formatPrice = (value) => {
    if (value == null) return '-'
    try {
        return new Intl.NumberFormat('id-ID').format(Number(value))
    } catch (e) {
        return value
    }
}

// Alias for template access
const product = props.product

// Add to cart API integration
const adding = ref(false)
const toast = ref({ show: false, message: '', type: 'success' })
let toastTimer = null

const showToast = (message, type = 'success') => {
    clearTimeout(toastTimer)
    toast.value = { show: true, message, type }
    toastTimer = setTimeout(() => { toast.value.show = false }, 2500)
}

const addToCart = async () => {
    if ((product?.stock ?? 0) <= 0 || adding.value) return
    adding.value = true
    try {
        const res = await fetch('/api/cart/items', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ product_id: product.id, qty: quantity.value })
        })

        if (res.status === 401) { router.visit('/login'); return }
        if (res.status === 419) {
            // CSRF/session expired — reload to refresh tokens and session
            window.location.reload()
            return
        }
        if (!res.ok) {
            const text = await res.text()
            throw new Error(text || `Gagal menambahkan (${res.status})`)
        }

        showToast('Barang telah dimasukkan ke keranjang', 'success')
    } catch (e) {
        console.error(e)
        showToast('Gagal menambahkan ke keranjang', 'error')
    } finally {
        adding.value = false
    }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
</style>
