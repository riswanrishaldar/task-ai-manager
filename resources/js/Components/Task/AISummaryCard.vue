<script setup>
import TaskPriorityBadge from './TaskPriorityBadge.vue'

defineProps({
  summary: {
    type: String,
    default: '',
  },
  priority: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
      <div>
        <p class="text-sm font-semibold text-slate-900">AI Summary</p>
        <p class="mt-1 text-sm text-slate-500">Auto-generated task context and suggested priority.</p>
      </div>

      <TaskPriorityBadge v-if="priority" :priority="priority" />
    </div>

    <div class="mt-5 rounded-xl bg-slate-50 p-4">
      <p v-if="summary" class="text-sm leading-7 text-slate-700">
        {{ summary }}
      </p>
      <p v-else class="text-sm italic text-slate-400">
        AI summary has not been generated yet.
      </p>
    </div>
  </div>
</template>