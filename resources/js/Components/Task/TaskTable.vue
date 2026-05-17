<script setup>
import { Link } from '@inertiajs/vue3'
import TaskStatusBadge from './TaskStatusBadge.vue'
import TaskPriorityBadge from './TaskPriorityBadge.vue'
import EmptyState from '../Ui/EmptyState.vue'

defineProps({
  tasks: {
    type: Object,
    required: true,
  },
})

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString()
}
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h3 class="text-lg font-semibold text-slate-900">Tasks</h3>
          <p class="text-sm text-slate-500">Manage assigned work and review AI-generated insights.</p>
        </div>

        <Link
          :href="route('tasks.create')"
          class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
        >
          Create Task
        </Link>
      </div>
    </div>

    <div v-if="tasks.data?.length" class="overflow-x-auto">
      <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
          <tr>
            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Task</th>
            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Priority</th>
            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Assigned To</th>
            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Due Date</th>
            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-slate-200 bg-white">
          <tr v-for="task in tasks.data" :key="task.id" class="hover:bg-slate-50">
            <td class="px-5 py-4 align-top">
              <div>
                <p class="font-semibold text-slate-900">{{ task.title }}</p>
                <p class="mt-1 max-w-md text-sm text-slate-500">
                  {{ task.description || 'No description provided.' }}
                </p>
              </div>
            </td>

            <td class="px-5 py-4 align-top">
              <TaskPriorityBadge :priority="task.priority" />
            </td>

            <td class="px-5 py-4 align-top">
              <TaskStatusBadge :status="task.status" />
            </td>

            <td class="px-5 py-4 align-top text-sm text-slate-600">
              {{ task.assignee?.name || 'Unassigned' }}
            </td>

            <td class="px-5 py-4 align-top text-sm text-slate-600">
              {{ formatDate(task.due_date) }}
            </td>

            <td class="px-5 py-4 align-top text-right">
              <Link
                :href="route('tasks.show', task.id)"
                class="text-sm font-semibold text-slate-700 transition hover:text-slate-900"
              >
                View
              </Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="p-5">
      <EmptyState
        title="No tasks found"
        description="Try changing the filters or create your first task."
        button-text="Create Task"
        :button-href="route('tasks.create')"
      />
    </div>

    <div
      v-if="tasks.links?.length > 3"
      class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-5 py-4"
    >
      <p class="text-sm text-slate-500">
        Showing {{ tasks.from }} to {{ tasks.to }} of {{ tasks.total }} tasks
      </p>

      <div class="flex flex-wrap items-center gap-2">
        <component
          :is="link.url ? Link : 'span'"
          v-for="(link, index) in tasks.links"
          :key="index"
          :href="link.url || undefined"
          class="rounded-lg px-3 py-2 text-sm transition"
          :class="[
            link.active
              ? 'bg-slate-900 text-white'
              : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50',
            !link.url ? 'cursor-not-allowed opacity-50' : ''
          ]"
        >
          <span v-html="link.label" />
        </component>
      </div>
    </div>
  </div>
</template>