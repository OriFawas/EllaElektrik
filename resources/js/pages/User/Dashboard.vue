<script setup>
import { Head, usePage, router } from '@inertiajs/vue3'
import UserLayout from '@/layouts/UserLayout.vue'
import HeaderLayout from '@/components/HeaderLayout.vue'
import FooterLayout from '@/components/FooterLayout.vue'
import InlineNotice from '@/components/InlineNotice.vue'
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
  ktpName: '',
  ktpExisting: null,
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
  form.ktpExisting = u.ktp_path ? u.ktp_path : null
}

const showKtpPreview = ref(false)
const ktpPreviewUrl = computed(() => form.ktpExisting)

// Hydrate immediately to avoid blank fields on first paint
hydrateFromProps()

onMounted(hydrateFromProps)
watch(() => page.props.auth, () => {
  hydrateFromProps()
  // Also refresh KTP path when auth props update
  const u = page.props.auth?.user || {}
  if (u.ktp_path) form.ktpExisting = u.ktp_path
}, { deep: true })

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
      form.ktpExisting = u.ktp_path ?? form.ktpExisting
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
      form.ktpExisting = saved.user.ktp_path ?? form.ktpExisting
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
      // Update KTP path from upload response
      const uploadResult = await up.json().catch(() => null)
      if (uploadResult?.ktp_path) {
        form.ktpExisting = uploadResult.ktp_path
        form.ktpFile = null
        form.ktpName = ''
      }
      // Use the inline displayed notice rather than alert
      // show a local flash for immediate feedback
      // After successful upload, redirect user to dashboard with `verification_sent` notice
      router.visit('/user/dashboard?notice=verification_sent')
      return
    } else {
      // Redirect to dashboard so inline success notice can be displayed
      router.visit('/user/dashboard?notice=profile_saved')
      return
    }
  } catch (e) {
    const uErr = e.message || 'Gagal menyimpan profil'
    // show inline notice using query param and server-side flash is not possible here, so fallback to alert for now
    alert(uErr)
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

  // Unified inline notification: query, flash, server errors, account status
  const serverOrderError = computed(() => page.props.errors?.order ?? null)
  const flashSuccess = computed(() => page.props.flash?.success ?? null)
  const flashError = computed(() => page.props.flash?.error ?? null)
  const queryNotice = computed(() => {
    try { return new URL(window.location.href).searchParams.get('notice') } catch (e) { return null }
  })

  const displayedNotice = computed(() => {
    if (flashSuccess.value) return { title: 'Berhasil', text: flashSuccess.value, type: 'success', cls: 'bg-green-50 text-green-700' }
    if (flashError.value) return { title: 'Gagal', text: flashError.value, type: 'error', cls: 'bg-red-50 text-red-700' }
    if (serverOrderError.value) {
      const lower = String(serverOrderError.value).toLowerCase(); const needsUpload = lower.includes('upload') || lower.includes('ktp') || lower.includes('nik')
      return { title: 'Perhatian', text: serverOrderError.value, type: 'error', cls: 'bg-red-50 text-red-700', ctaLabel: needsUpload ? 'Unggah KTP' : null }
    }
    const q = queryNotice.value
    if (q === 'verification_required') return { title: 'Perhatian', text: 'Unggah KTP untuk bisa melakukan pesanan.', type: 'error', cls: 'bg-red-50 text-red-700', ctaLabel: 'Unggah KTP' }
    if (q === 'verification_pending') return {title: 'Menunggu Verifikasi', text: 'KTP Anda sudah dikirim, menunggu verifikasi admin.', type: 'warn', cls: 'bg-yellow-50 text-yellow-700'}
    if (verificationStatus.value === 'unverified') return { title: 'Belum Terverifikasi', text: 'Unggah KTP untuk memverifikasi akun Anda dan bisa melakukan pemesanan.', type: 'info', cls: 'bg-red-50 text-red-700', ctaLabel: 'Unggah KTP' }
    if (verificationStatus.value === 'pending') return { title: 'Menunggu Verifikasi', text: verificationNote.value || 'KTP menunggu verifikasi admin.', type: 'warn', cls: 'bg-yellow-50 text-yellow-700' }
    if (q === 'profile_saved') return { title: 'Profil Tersimpan', text: 'Perubahan profil disimpan', type: 'success', cls: 'bg-green-50 text-green-700' }
    if (q === 'verification_sent') return { title: 'Verifikasi Dikirim', text: 'KTP Anda dikirim, tunggu verifikasi admin.', type: 'success', cls: 'bg-green-50 text-green-700' }
    return null
  })

  const dismissNotification = () => { /* no-op for now; inline notice emits dismiss but we don't persist */ }

  const handleNoticeAction = () => {
    const n = displayedNotice.value
    if (!n) return
    if (n.ctaLabel === 'Unggah KTP') {
      const el = document.getElementById('ktp-file-input')
      if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); try { el.focus(); el.click(); } catch(e){} }
      else router.visit('/user/dashboard?notice=verification_required')
    }
  }

// no explicit refresh button
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col pt-15">
  <HeaderLayout />
  <Head title="Profil Saya" />
  <UserLayout>
    <!-- Container full width -->
    <div class="bg-white p-4 md:p-8 rounded-lg shadow w-full">
      <div class="flex flex-col md:flex-row md:items-center md:gap-3 mb-6">
        <h1 class="text-2xl font-semibold">Profil Saya</h1>

        <span
          :title="verificationStatus === 'rejected' && verificationNote ? verificationNote : ''"
          :class="['inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium', statusMeta.cls]"
        >
          <span :class="['h-2 w-2 rounded-full', statusMeta.dot]"></span>
          {{ statusMeta.label }}
        </span>
        <div class="ml-0 md:ml-3 mt-3 md:mt-0 w-full md:w-auto">
          <InlineNotice :notice="displayedNotice" @dismiss="dismissNotification" @action="handleNoticeAction" />
        </div>

      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <div>
            <label class="text-sm font-medium">Nama Lengkap</label>
            <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" />
          </div>

          <div>
            <label class="text-sm font-medium">Alamat Email</label>
            <input v-model="form.email" type="email" readonly aria-readonly="true" class="mt-1 w-full border rounded px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed" />
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
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
          <!-- Existing file indicator -->
  <div v-if="form.ktpExisting && !form.ktpName" class="mt-2 p-3 bg-gray-50 border rounded text-sm">
    <p class="text-gray-700">
      KTP sudah diupload:
      <button
        type="button"
        @click="showKtpPreview = true"
        class="text-blue-600 underline ml-1"
      >
        Lihat File
      </button>

    </p>
    <p class="text-gray-500 text-xs">Upload baru akan menggantikan file ini.</p>
  </div>

  <!-- input file -->
  <div class="mt-2">
    <input
      id="ktp-file-input"
      type="file"
      accept=".jpg,.jpeg,.png,.pdf"
      @change="onFileChange"
      class="border rounded p-2 w-full sm:w-72 cursor-pointer"
    />
  </div>

  <!-- new file selected -->
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

    <!-- Modal Preview KTP -->
<div
  v-if="showKtpPreview"
  class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
  <div class="bg-white rounded-lg p-4 max-w-full max-h-full">
    <div class="flex justify-end">
      <button @click="showKtpPreview = false" class="text-gray-600 text-lg font-bold">×</button>
    </div>

    <!-- Jika file berupa gambar -->
    <img
      v-if="ktpPreviewUrl && ktpPreviewUrl.match(/\.(jpg|jpeg|png)$/i)"
      :src="ktpPreviewUrl"
      class="max-w-[90vw] max-h-[80vh] object-contain rounded"
    />

        <!-- Jika file PDF -->
        <iframe
          v-else
          :src="ktpPreviewUrl"
          class="w-[80vw] h-[80vh] rounded"
        ></iframe>
      </div>
    </div>

  </UserLayout>
  <FooterLayout />
  </div>
</template>