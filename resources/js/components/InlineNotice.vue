<template>
  <transition name="notice-fade" appear>
    <div v-if="notice" :class="['inline-flex items-center gap-3 rounded px-3 py-2 text-sm max-w-xs shadow-sm', notice.cls]" role="status" aria-live="polite">
      <div class="flex items-center gap-2">
        <span class="text-lg" aria-hidden v-html="iconSvg"></span>
        <div class="leading-tight mr-2">
          <div class="font-medium">{{ notice.title }}</div>
          <div class="text-xs">{{ notice.text }}</div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <button v-if="notice.ctaLabel" @click="$emit('action')" class="bg-white text-xs py-1 px-2 rounded border hover:bg-gray-50">{{ notice.ctaLabel }}</button>
        <button v-if="dismissable" @click="$emit('dismiss')" class="text-xs px-2 py-1 text-gray-600 hover:text-gray-800">✕</button>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  notice: { type: Object, default: null },
  dismissable: { type: Boolean, default: true },
})

const icons = {
  success: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414L8.414 14.999a1 1 0 01-1.414 0L3.295 11.295a1 1 0 011.414-1.414L7 12.17l8.29-8.29a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>',
  error: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-4h2v2h-2v-2zm0-8h2v6h-2V6z" clip-rule="evenodd"/></svg>',
  info: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zM9 9V7h2v2H9zm0 2v4h2v-4H9z" clip-rule="evenodd"/></svg>',
  warn: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M8.257 3.099c.764-1.36 2.725-1.36 3.489 0l6.516 11.612c.746 1.33-.213 2.99-1.744 2.99H3.485c-1.531 0-2.49-1.66-1.744-2.99L8.257 3.1zM10 13a1 1 0 100 2 1 1 0 000-2zM9 6h2v5H9V6z"/></svg>'
}

const iconSvg = computed(() => {
  if (!props.notice) return ''
  const type = (props.notice.type || 'info')
  if (type === 'success') return icons.success
  if (type === 'error') return icons.error
  if (type === 'warn' || type === 'warning') return icons.warn
  return icons.info
})
</script>

<style scoped>
.notice-fade-enter-active, .notice-fade-leave-active { transition: opacity 200ms ease, transform 200ms ease; }
.notice-fade-enter-from, .notice-fade-leave-to { opacity: 0; transform: translateY(-6px); }
</style>
