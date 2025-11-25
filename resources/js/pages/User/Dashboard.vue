<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import { ref, onMounted, computed, watch } from 'vue'

const form = {
  name: '',
  email: '',
  phone: '',
  province: '',
  city: '',
  address: '',
  nik: '',
  ktpFile: null,
  ktpName: ''
}

const errors = ref({
  nik: '',
  name: '',
  phone: '',
  province: '',
  city: '',
  address: ''
})

const onFileChange = async (e) => {
  const file = e.target.files[0]
  if (file) {
    form.ktpFile = file
    form.ktpName = file.name
  }
}

// Page + reactive hydration from shared props
const page = usePage()
const hydrateFromProps = () => {
  const u = page.props.auth?.user || {}
  form.name = u.name || ''
  form.email = u.email || ''
  form.phone = u.phone || ''
  form.province = u.province || ''
  form.city = u.city || ''
  form.address = u.address || ''
  form.nik = u.nik || ''
}

// Hydrate immediately to avoid blank fields on first paint
hydrateFromProps()

onMounted(hydrateFromProps)
watch(() => page.props.auth, hydrateFromProps, { deep: true })

// Fallback: fetch persisted user profile to ensure fields are hydrated after navigation
onMounted(async () => {
  try {
    const res = await fetch('/api/me', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
    if (res.ok) {
      const u = await res.json()
      form.name = u.name ?? form.name
      form.email = u.email ?? form.email
      form.phone = u.phone ?? form.phone
      form.province = u.province ?? form.province
      form.city = u.city ?? form.city
      form.address = u.address ?? form.address
      form.nik = u.nik ?? form.nik
    }
  } catch {}
})

const clearErrors = () => {
  errors.value = {
    nik: '',
    name: '',
    phone: '',
    province: '',
    city: '',
    address: ''
  }
}

const submit = async () => {
  try {
    const csrf = (() => {
      const meta = document.querySelector('meta[name="csrf-token"]')
      if (meta) return meta.getAttribute('content')
      const m = document.cookie.match(/XSRF-TOKEN=([^;]+)/)
      return m ? decodeURIComponent(m[1]) : ''
    })()
    const res = await fetch('/user/profile', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrf,
        'X-XSRF-TOKEN': csrf,
      },
      credentials: 'same-origin',
      body: JSON.stringify({
        name: form.name,
        phone: form.phone,
        province: form.province,
        city: form.city,
        address: form.address,
        nik: form.nik,
      })
    })
    let saved = null
      if (!res.ok) {
      const err = await res.json().catch(() => ({}))

      // Jika ada errors dari Laravel
      if (err?.errors) {
        clearErrors()
        Object.keys(err.errors).forEach(key => {
          errors.value[key] = err.errors[key][0]   // ambil pesan pertama
        })
        throw new Error("Periksa kembali input Anda")
      }

      const msg = err?.message || 'Gagal menyimpan profil'
      throw new Error(msg)
    }

    saved = await res.json().catch(() => null)
    if (saved && saved.user) {
      form.name = saved.user.name ?? form.name
      form.phone = saved.user.phone ?? form.phone
      form.province = saved.user.province ?? form.province
      form.city = saved.user.city ?? form.city
      form.address = saved.user.address ?? form.address
      form.nik = saved.user.nik ?? form.nik
    }
    // Profile saved; proceed to optional verification upload if a file is selected
    if (form.ktpFile) {
      const fd = new FormData()
      fd.append('ktp', form.ktpFile)
      const up = await fetch('/user/verification/submit', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': csrf,
          'X-XSRF-TOKEN': csrf,
        },
        credentials: 'same-origin',
        body: fd
      })
      if (!up.ok) {
        const uerr = await up.json().catch(() => ({}))
        const umsg = uerr?.message || (uerr?.errors ? Object.values(uerr.errors).flat().join(', ') : '')
        throw new Error(umsg || 'Gagal mengirim verifikasi')
      }
      alert('Profil tersimpan & verifikasi dikirim')
    } else {
      alert('Data profil tersimpan')
    }
    // Reload shared props so badge/form reflect server state
    router.reload({ only: ['auth'] })
  } catch (e) {
    alert(e.message || 'Gagal menyimpan profil')
  }
}

// merged verification upload into submit()

// Verification badge data from Inertia shared props
const verificationStatus = computed(() => page.props.auth?.user?.verification_status ?? 'unverified')
const verificationNote = computed(() => page.props.auth?.user?.verification_note ?? '')
const statusMeta = computed(() => {
  const map = {
    verified: { label: 'Sudah Terverifikasi', cls: 'bg-green-100 text-green-700', dot: 'bg-green-500' },
    pending: { label: 'Perlu Diverifikasi', cls: 'bg-yellow-100 text-yellow-700', dot: 'bg-yellow-500' },
    rejected: { label: 'Ditolak', cls: 'bg-red-100 text-red-700', dot: 'bg-red-500' },
    unverified: { label: 'Belum Terverifikasi', cls: 'bg-gray-100 text-gray-700', dot: 'bg-gray-400' },
  }
  return map[verificationStatus.value] ?? map.unverified
})

// no explicit refresh button
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-20">
  <HeaderLayout />
  <Head title="Profil Saya" />
  <UserLayout>
    <!-- Container full width -->
    <div class="bg-white p-8 rounded-lg shadow w-full ">
      <div class="flex items-center gap-3 mb-6">
        <h1 class="text-2xl font-semibold">Profil Saya</h1>
        <span
          :title="verificationStatus === 'rejected' && verificationNote ? verificationNote : ''"
          :class="['inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium', statusMeta.cls]"
        >
          <span :class="['h-2 w-2 rounded-full', statusMeta.dot]"></span>
          {{ statusMeta.label }}
        </span>

      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Nama Lengkap</label>
            <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>

          <div>
            <label class="text-sm font-medium">Alamat Email</label>
            <input v-model="form.email" type="email" readonly aria-readonly="true" class="mt-1 w-full border rounded px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed" />
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Nomor Telepon</label>
            <input v-model="form.phone" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="text-sm font-medium">NIK</label>
            <input v-model="form.nik" type="text" class="mt-1 w-full border rounded px-3 py-2" />
            <p v-if="errors.nik" class="text-red-600 text-sm mt-1">
              {{ errors.nik }}
            </p>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="text-sm font-medium">Provinsi</label>
            <input v-model="form.province" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
          <div>
            <label class="text-sm font-medium">Kota / Kabupaten</label>
            <input v-model="form.city" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>
        </div>

        <div>
          <label class="text-sm font-medium">Alamat Lengkap</label>
          <textarea v-model="form.address" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea>
        </div>

        <div>
        <label class="text-sm font-medium">Upload KTP</label>

        <!-- input file, tetap tampil tapi kecil (mirip ukuran KTP) -->
        <div class="mt-1">
          <input
            type="file"
            accept=".jpg,.jpeg,.png,.pdf"
            @change="onFileChange"
            class="border rounded p-2 w-90 h-13 cursor-pointer"
          />
        </div>

        <!-- info file -->
        <p v-if="form.ktpName" class="text-green-600 text-sm mt-1">
          File dipilih: {{ form.ktpName }}
        </p>
      </div>


        <div class="flex items-center justify-end gap-3">
          <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </UserLayout>
  <FooterLayout />
  </div>
</template>
