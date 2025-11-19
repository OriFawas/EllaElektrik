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
    <div class="space-y-4">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Profil User</h1>
        <div class="flex items-center gap-2">
          <input v-model="q" @input="debouncedSearch" type="text" placeholder="Cari nama/email/nik" class="border rounded px-3 py-1.5" />
          <select v-model="status" class="border rounded px-3 py-1.5">
            <option value="all">Semua</option>
            <option value="pending">Perlu Diverifikasi</option>
            <option value="verified">Sudah Terverifikasi</option>
            <option value="rejected">Ditolak</option>
            <option value="unverified">Belum Terverifikasi</option>
          </select>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <div v-if="loading" class="p-6 text-gray-500">Memuat…</div>
          <div v-else-if="error" class="p-6 text-red-600">{{ error }}</div>
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
            <tbody class="divide-y divide-gray-100 bg-white">
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
                <td class="px-4 py-3 text-gray-700">{{ row.email }}</td>
                <td class="px-4 py-3 text-gray-700">{{ row.phone }}</td>
                <td class="px-4 py-3 text-gray-700">{{ row.address }}</td>
                <td class="px-4 py-3 text-gray-700">
                  <button
                    v-if="row.ktp_url"
                    type="button"
                    class="text-blue-600 underline"
                    @click="openPreview(row.ktp_url)"
                  >Lihat KTP</button>
                  <span v-else>—</span>
                </td>
                <td class="px-4 py-3">
                  <span :class="['inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-medium', statusBadge(row.verification_status).class]">
                    <span :class="['h-2 w-2 rounded-full', statusBadge(row.verification_status).dot]"></span>
                    {{ statusBadge(row.verification_status).label }}
                  </span>
                </td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-3">
                    <button v-if="row.verification_status !== 'verified'" class="px-2 py-1 rounded bg-green-600 text-white hover:bg-green-700" @click="openConfirm('approve', row.id)">Approve</button>
                    <button v-if="row.verification_status !== 'rejected'" class="px-2 py-1 rounded bg-red-600 text-white hover:bg-red-700" @click="openConfirm('reject', row.id)">Reject</button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Footer: pagination -->
        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-t text-sm text-gray-600">
          <div>
            <span v-if="meta.total">Showing {{ meta.from }}–{{ meta.to }} of {{ meta.total }}</span>
          </div>
          <div class="flex items-center gap-2">
            <button :disabled="meta.current_page <= 1" @click="fetchUsers(meta.current_page - 1)" class="px-2 py-1 rounded border hover:bg-white disabled:opacity-50">Prev</button>
            <span>Page {{ meta.current_page }} / {{ meta.last_page }}</span>
            <button :disabled="meta.current_page >= meta.last_page" @click="fetchUsers(meta.current_page + 1)" class="px-2 py-1 rounded border hover:bg-white disabled:opacity-50">Next</button>
          </div>
        </div>
      </div>
      
      <!-- Confirm Modal -->
      <div v-if="confirmModal.open" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" @click="closeConfirm"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md p-5">
          <h3 class="text-lg font-semibold mb-3">Konfirmasi {{ confirmModal.mode === 'approve' ? 'Verifikasi' : 'Penolakan' }}</h3>
          <p class="text-sm text-gray-600 mb-4" v-if="confirmModal.mode==='approve'">Yakin ingin menyetujui verifikasi pengguna ini?</p>
          <div v-else class="space-y-2">
            <label class="text-sm text-gray-700">Alasan Penolakan</label>
            <textarea v-model="confirmModal.reason" rows="3" class="w-full border rounded px-3 py-2" placeholder="Tuliskan alasan dengan jelas"></textarea>
          </div>
          <div class="mt-5 flex justify-end gap-2">
            <button @click="closeConfirm" class="px-3 py-1.5 rounded border">Batal</button>
            <button @click="confirmAction" class="px-3 py-1.5 rounded bg-blue-600 text-white hover:bg-blue-700">Konfirmasi</button>
          </div>
        </div>
      </div>

      <!-- Toast -->
      <div v-if="toast.show" class="fixed bottom-6 right-6 z-50">
        <div :class="['px-4 py-2 rounded shadow text-white', toast.type==='success' ? 'bg-green-600' : 'bg-red-600']">
          {{ toast.message }}
        </div>
      </div>
      
      <!-- KTP Preview Modal -->
      <div v-if="preview.open" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/60" @click="closePreview"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-[95vw] max-w-3xl max-h-[90vh] p-4 flex flex-col">
          <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold">Pratinjau KTP</h3>
            <button @click="closePreview" class="px-2 py-1 rounded border">Tutup</button>
          </div>
          <div class="flex-1 overflow-auto">
            <img v-if="preview.type==='image'" :src="preview.url" alt="KTP" class="max-w-full h-auto mx-auto" />
            <iframe v-else :src="preview.url" class="w-full h-[70vh]" />
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
