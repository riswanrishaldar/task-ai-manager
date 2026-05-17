<script setup>
import { computed } from 'vue'

const props = defineProps({
  priority: {
    type: String,
    required: true,
  },
})

const classes = computed(() => {
  switch (props.priority) {
    case 'high':
      return 'bg-rose-100 text-rose-700 ring-1 ring-inset ring-rose-200'
    case 'medium':
      return 'bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-200'
    default:
      return 'bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-200'
  }
})
</script>

<template>
  <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold capitalize" :class="classes">
    {{ priority }}
  </span>
</template>