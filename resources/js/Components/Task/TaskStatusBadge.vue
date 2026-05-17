<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true,
  },
})

const classes = computed(() => {
  switch (props.status) {
    case 'completed':
      return 'bg-emerald-100 text-emerald-700 ring-1 ring-inset ring-emerald-200'
    case 'inprogress':
      return 'bg-sky-100 text-sky-700 ring-1 ring-inset ring-sky-200'
    default:
      return 'bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-200'
  }
})

const label = computed(() => {
  if (props.status === 'inprogress') return 'In Progress'
  if (props.status === 'completed') return 'Completed'
  return 'Pending'
})
</script>

<template>
  <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold" :class="classes">
    {{ label }}
  </span>
</template>