<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TaskFilters from '@/Components/Task/TaskFilters.vue'
import TaskTable from '@/Components/Task/TaskTable.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
  tasks: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  users: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-5">
        <div class="space-y-0.5">
          <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Operations Hub</span>
          <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Task Workspace</h1>
          <p class="text-sm text-slate-500">
            Monitor, isolate, and audit background workflow operations.
          </p>
        </div>

        <div>
          <Link
            :href="route('tasks.create')"
            class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-95 sm:w-auto"
          >
            + Create New Task
          </Link>
        </div>
      </div>

      <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <TaskFilters :filters="filters" :users="users" />
      </div>

      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <TaskTable :tasks="tasks" />
      </div>
    </div>
  </AppLayout>
</template>