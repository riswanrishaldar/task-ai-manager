<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  value: {
    type: [String, Number],
    required: true,
  },
  description: {
    type: String,
    default: '',
  },
  tone: {
    type: String,
    default: 'default',
  },
})

const toneClasses = {
  default: 'bg-white border-slate-200',
  success: 'bg-emerald-50 border-emerald-200',
  warning: 'bg-amber-50 border-amber-200',
  danger: 'bg-rose-50 border-rose-200',
}
</script>

<template>
  <div
    class="rounded-2xl border p-5 shadow-sm"
    :class="toneClasses[tone] || toneClasses.default"
  >
    <p class="text-sm font-medium text-slate-500">{{ title }}</p>
    <p class="mt-3 text-3xl font-bold tracking-tight text-slate-900">{{ value }}</p>
    <p v-if="description" class="mt-2 text-sm text-slate-600">{{ description }}</p>
  </div>
</template>