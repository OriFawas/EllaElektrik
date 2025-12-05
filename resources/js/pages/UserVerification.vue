<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { ref, computed, onMounted, watch } from 'vue'

const loading = ref(false)
const error = ref('')
const users = ref([])
const meta = ref({ current_page: 1, last_page: 1, total: 0, from: 0, to: 0 })
const status = ref('all') // all | pending | verified | rejected | unverified
const q = ref('')
const perPage = ref(10)

let searchTimer = null

const fetchUsers = async (page = 1) => {
  loading.value = true
  error.value = ''
  try {
    const params = new URLSearchParams()
    if (status.value !== 'all') params.set('status', status.value)
    if (q.value.trim()) params.set('q', q.value.trim())
    params.set('per_page', String(perPage.value))
    params.set('page', String(page))
    const res = await fetch(`/api/admin/users?${params.toString()}`, {
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    })
    if (!res.ok) throw new Error(`Gagal memuat data (${res.status})`)
    const data = await res.json()
    users.value = Array.isArray(data.data) ? data.data : []
    meta.value = data.meta || meta.value
  } catch (e) {
    error.value = e.message || String(e)
  } finally {
    loading.value = false
  }
}

const debouncedSearch = () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchUsers(1), 300)
}

const allChecked = ref(false)
const selected = ref(new Set())
function toggleAll() {
  allChecked.value = !allChecked.value
  selected.value = new Set(allChecked.value ? users.value.map(r => r.id) : [])
}
function toggleRow(id) {
  if (selected.value.has(id)) selected.value.delete(id)
  else selected.value.add(id)
}

const statusBadge = (s) => {
  switch (s) {
    case 'verified':
      return { label: 'Sudah Terverifikasi', class: 'bg-green-100 text-green-700', dot: 'bg-green-500' }
    case 'pending':
      return { label: 'Perlu Diverifikasi', class: 'bg-yellow-100 text-yellow-700', dot: 'bg-yellow-500' }
    case 'rejected':
      return { label: 'Ditolak', class: 'bg-red-100 text-red-700', dot: 'bg-red-500' }
    default:
      return { label: 'Belum Terverifikasi', class: 'bg-gray-100 text-gray-700', dot: 'bg-gray-400' }
  }
}

// KTP preview modal
const preview = ref({ open: false, url: '', type: 'image' })
const openPreview = (url) => {
  if (!url) return
  const lower = url.toLowerCase()
  const isImage = lower.endsWith('.jpg') || lower.endsWith('.jpeg') || lower.endsWith('.png')
  preview.value = { open: true, url, type: isImage ? 'image' : 'pdf' }
}
const closePreview = () => { preview.value.open = false; preview.value.url = '' }

// Toast state
const toast = ref({ show: false, message: '', type: 'success' })
let toastTimer = null
const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type }
  clearTimeout(toastTimer)
  toastTimer = setTimeout(() => (toast.value.show = false), 2500)
}

// Confirm modal state
const confirmModal = ref({ open: false, mode: 'approve', id: null, reason: '' })
const openConfirm = (mode, id) => {
  confirmModal.value = { open: true, mode, id, reason: '' }
}
const closeConfirm = () => {
  confirmModal.value.open = false
  confirmModal.value.reason = ''
}

const confirmAction = async () => {
  const { mode, id, reason } = confirmModal.value
  if (!id) return
  try {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    if (mode === 'approve') {
      const res = await fetch(`/api/admin/users/${id}/approve`, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        credentials: 'same-origin'
      })
      if (!res.ok) throw new Error('Gagal memverifikasi user')
      users.value = users.value.map(u => u.id === id ? { ...u, verification_status: 'verified', verification_note: null } : u)
      showToast('User berhasil diverifikasi', 'success')
    } else {
      if (!reason.trim()) return showToast('Alasan penolakan wajib diisi', 'error')
      const res = await fetch(`/api/admin/users/${id}/reject`, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        credentials: 'same-origin',
        body: JSON.stringify({ reason: reason.trim() })
      })
      if (!res.ok) throw new Error('Gagal menolak user')
      users.value = users.value.map(u => u.id === id ? { ...u, verification_status: 'rejected', verification_note: reason.trim() } : u)
      showToast('User ditolak', 'success')
    }
  } catch (e) {
    showToast(e.message || 'Terjadi kesalahan', 'error')
  } finally {
    closeConfirm()
  }
}

onMounted(() => fetchUsers(1))
watch(status, () => fetchUsers(1))
</script>

<template>
  <AdminLayout>
    <div class="space-y-4 p-2 md:p-0">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-3 md:gap-0">
        <h1 class="text-2xl md:text-2xl font-semibold">Profil User</h1>
        <div class="w-full md:w-auto flex flex-col sm:flex-row items-stretch md:items-center gap-2">
          <input 
            v-model="q" 
            @input="debouncedSearch" 
            type="text" 
            placeholder="Cari nama/email/nik" 
            class="border rounded px-3 py-2 text-sm flex-1 md:flex-none md:w-64"
          />
          <select v-model="status" class="border rounded px-3 py-2 text-sm">
            <option value="all">Semua</option>
            <option value="pending">Perlu Diverifikasi</option>
            <option value="verified">Sudah Terverifikasi</option>
            <option value="rejected">Ditolak</option>
            <option value="unverified">Belum Terverifikasi</option>
          </select>
        </div>
      </div>

      <!-- Table Section -->
      <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <!-- Loading/Error State -->
        <div v-if="loading" class="p-6 text-gray-500 text-center">Memuat…</div>
        <div v-else-if="error" class="p-6 text-red-600 text-center">{{ error }}</div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden md:block overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
              <tr>
                <th class="w-10 px-4 py-3">
                  <input type="checkbox" :checked="allChecked" @change="toggleAll" class="h-4 w-4 rounded border-gray-300" />
                </th>
                <th class="px-4 py-3 text-left font-medium">Nama Lengkap</th>
                <th class="px-4 py-3 text-left font-medium">Email</th>
                <th class="px-4 py-3 text-left font-medium">Nomor. Telp</th>
                <th class="px-4 py-3 text-left font-medium">Alamat</th>
                <th class="px-4 py-3 text-left font-medium">Lampiran</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white" v-if="!loading && !error">
              <tr v-for="row in users" :key="row.id" class="hover:bg-gray-50">
                <td class="px-4 py-3">
                  <input type="checkbox" :checked="selected.has(row.id)" @change="toggleRow(row.id)" class="h-4 w-4 rounded border-gray-300" />
                </td>
                <td class="px-4 py-3">
                  <div class="leading-tight">
                    <div class="font-medium text-gray-800">{{ row.name }}</div>
                    <div class="text-xs text-gray-500">{{ row.nik || '-' }}</div>
                  </div>
                </td>
                <td class="px-4 py-3 text-gray-700 text-sm">{{ row.email }}</td>
                <td class="px-4 py-3 text-gray-700 text-sm">{{ row.phone }}</td>
                <td class="px-4 py-3 text-gray-700 text-sm">{{ row.address }}</td>
                <td class="px-4 py-3 text-gray-700">
                  <button
                    v-if="row.ktp_url"
                    type="button"
                    class="text-blue-600 underline text-sm hover:text-blue-800"
                    @click="openPreview(row.ktp_url)"
                  >Lihat KTP</button>
                  <span v-else class="text-gray-400">—</span>
                </td>
                <td class="px-4 py-3">
                  <span :class="['inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium', statusBadge(row.verification_status).class]">
                    <span :class="['h-2 w-2 rounded-full', statusBadge(row.verification_status).dot]"></span>
                    {{ statusBadge(row.verification_status).label }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-2">
                    <button v-if="row.verification_status !== 'verified'" class="px-2 py-1 rounded text-xs md:text-sm bg-green-600 text-white hover:bg-green-700" @click="openConfirm('approve', row.id)">Approve</button>
                    <button v-if="row.verification_status !== 'rejected'" class="px-2 py-1 rounded text-xs md:text-sm bg-red-600 text-white hover:bg-red-700" @click="openConfirm('reject', row.id)">Reject</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Mobile Card Layout (visible on mobile only) -->
        <div v-if="!loading && !error" class="md:hidden divide-y">
          <div v-for="row in users" :key="row.id" class="p-4 space-y-3 hover:bg-gray-50">
            <div class="flex items-start justify-between gap-2">
              <div class="flex-1">
                <h3 class="font-medium text-gray-800 text-sm">{{ row.name }}</h3>
                <p class="text-xs text-gray-500">NIK: {{ row.nik || '-' }}</p>
              </div>
              <input type="checkbox" :checked="selected.has(row.id)" @change="toggleRow(row.id)" class="h-4 w-4 rounded border-gray-300 mt-1" />
            </div>

            <div class="space-y-1 text-xs text-gray-600">
              <p><span class="font-medium">Email:</span> {{ row.email }}</p>
              <p><span class="font-medium">Telp:</span> {{ row.phone }}</p>
              <p><span class="font-medium">Alamat:</span> {{ row.address }}</p>
            </div>

            <div class="flex items-center gap-2 pt-2">
              <button
                v-if="row.ktp_url"
                type="button"
                class="text-xs text-blue-600 underline hover:text-blue-800"
                @click="openPreview(row.ktp_url)"
              >Lihat KTP</button>
              <span v-else class="text-xs text-gray-400">Tidak ada KTP</span>
            </div>

            <div class="pt-2">
              <span :class="['inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium', statusBadge(row.verification_status).class]">
                <span :class="['h-1.5 w-1.5 rounded-full', statusBadge(row.verification_status).dot]"></span>
                {{ statusBadge(row.verification_status).label }}
              </span>
            </div>

            <div class="flex gap-2 pt-2">
              <button v-if="row.verification_status !== 'verified'" class="flex-1 px-2 py-2 rounded text-xs bg-green-600 text-white hover:bg-green-700 font-medium" @click="openConfirm('approve', row.id)">Approve</button>
              <button v-if="row.verification_status !== 'rejected'" class="flex-1 px-2 py-2 rounded text-xs bg-red-600 text-white hover:bg-red-700 font-medium" @click="openConfirm('reject', row.id)">Reject</button>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3 bg-gray-50 border-t text-xs md:text-sm text-gray-600 gap-2 md:gap-0">
          <div class="text-center sm:text-left">
            <span v-if="meta.total">Showing {{ meta.from }}–{{ meta.to }} of {{ meta.total }}</span>
          </div>
          <div class="flex items-center gap-1 md:gap-2 justify-center">
            <button :disabled="meta.current_page <= 1" @click="fetchUsers(meta.current_page - 1)" class="px-2 py-1 rounded border text-xs md:text-sm hover:bg-white disabled:opacity-50">Prev</button>
            <span class="text-xs md:text-sm">Page {{ meta.current_page }} / {{ meta.last_page }}</span>
            <button :disabled="meta.current_page >= meta.last_page" @click="fetchUsers(meta.current_page + 1)" class="px-2 py-1 rounded border text-xs md:text-sm hover:bg-white disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
      
      <!-- Confirm Modal: Responsive -->
      <div v-if="confirmModal.open" class="fixed inset-0 z-50 flex items-end md:items-center justify-center p-2 md:p-0">
        <div class="absolute inset-0 bg-black/40" @click="closeConfirm"></div>
        <div class="relative bg-white rounded-t-lg md:rounded-lg shadow-xl w-full md:w-full md:max-w-md p-5">
          <h3 class="text-lg font-semibold mb-3">Konfirmasi {{ confirmModal.mode === 'approve' ? 'Verifikasi' : 'Penolakan' }}</h3>
          <p class="text-sm text-gray-600 mb-4" v-if="confirmModal.mode==='approve'">Yakin ingin menyetujui verifikasi pengguna ini?</p>
          <div v-else class="space-y-2">
            <label class="text-sm text-gray-700">Alasan Penolakan</label>
            <textarea v-model="confirmModal.reason" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Tuliskan alasan dengan jelas"></textarea>
          </div>
          <div class="mt-5 flex flex-col-reverse sm:flex-row justify-end gap-2">
            <button @click="closeConfirm" class="px-3 py-2 rounded border text-sm font-medium">Batal</button>
            <button @click="confirmAction" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 text-sm font-medium">Konfirmasi</button>
          </div>
        </div>
      </div>

      <!-- Toast: Responsive -->
      <div v-if="toast.show" class="fixed bottom-4 right-4 z-50 max-w-xs">
        <div :class="['px-4 py-2 rounded shadow text-white text-sm', toast.type==='success' ? 'bg-green-600' : 'bg-red-600']">
          {{ toast.message }}
        </div>
      </div>
      
      <!-- KTP Preview Modal: Responsive -->
      <div v-if="preview.open" class="fixed inset-0 z-50 flex items-end md:items-center justify-center p-2 md:p-0">
        <div class="absolute inset-0 bg-black/60" @click="closePreview"></div>
        <div class="relative bg-white rounded-t-lg md:rounded-lg shadow-xl w-full md:w-full md:max-w-3xl md:max-h-[90vh] p-4 flex flex-col max-h-[90vh]">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Pratinjau KTP</h3>
            <button @click="closePreview" class="px-3 py-1 rounded border text-sm">Tutup</button>
          </div>
          <div class="flex-1 overflow-auto">
            <img v-if="preview.type==='image'" :src="preview.url" alt="KTP" class="max-w-full h-auto mx-auto" />
            <iframe v-else :src="preview.url" class="w-full h-[60vh] md:h-[70vh]" />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
