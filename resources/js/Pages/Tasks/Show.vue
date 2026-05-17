<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TaskStatusBadge from '@/Components/Task/TaskStatusBadge.vue'
import TaskPriorityBadge from '@/Components/Task/TaskPriorityBadge.vue'
import AISummaryCard from '@/Components/Task/AISummaryCard.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  task: {
    type: Object,
    required: true,
  },
})

const statusForm = useForm({
  status: props.task.status || 'pending',
})

const formatDate = (date) => {
  if (!date) return '—'
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const updateStatus = () => {
  statusForm.patch(route('tasks.updateStatus', props.task.id))
}
</script>

<template>
  <AppLayout>
    <div class="space-y-6">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-5">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">Task Scope / #{{ task.id }}</span>
          <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl mt-0.5">Task Inspection</h1>
        </div>

        <div class="flex items-center gap-2">
          <Link
            :href="route('tasks.index')"
            class="rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 transition"
          >
            Back to List
          </Link>
          <Link
            v-if="task?.id"
            :href="route('tasks.edit', { task: task.id })"
            class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition"
          >
            Edit Fields
          </Link>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <div class="space-y-6 lg:col-span-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
              <div class="space-y-1 max-w-xl">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ task.title }}</h2>
                <div class="pt-2 text-sm leading-relaxed text-slate-600 whitespace-pre-wrap">
                  {{ task.description || 'No descriptive parameters compiled.' }}
                </div>
              </div>

              <div class="flex items-center gap-1.5 self-start">
                <TaskPriorityBadge :priority="task.priority" />
                <TaskStatusBadge :status="task.status" />
              </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 border-t border-slate-100 pt-5">
              <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Assigned Resource</span>
                <p class="mt-1 text-sm font-semibold text-slate-800 truncate">{{ task.assignee?.name || 'Unassigned Status' }}</p>
              </div>

              <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Target Deadline</span>
                <p class="mt-1 text-sm font-semibold text-slate-800">{{ formatDate(task.due_date) }}</p>
              </div>

              <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-3.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">AI Verified Urgency</span>
                <div class="mt-1">
                  <TaskPriorityBadge v-if="task.ai_priority" :priority="task.ai_priority" />
                  <span v-else class="text-xs text-slate-400 font-medium">Pending Processing</span>
                </div>
              </div>
            </div>
          </div>

          <AISummaryCard :summary="task.ai_summary" :priority="task.ai_priority" />
        </div>

        <div class="space-y-6">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            <div>
              <h3 class="text-sm font-bold text-slate-900">Workflow Routing Control</h3>
              <p class="text-xs text-slate-400 mt-0.5">Update stage records in real-time</p>
            </div>

            <form @submit.prevent="updateStatus" class="space-y-3">
              <select
                v-model="statusForm.status"
                class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-4 focus:ring-slate-100"
              >
                <option value="pending">Pending Review</option>
                <option value="inprogress">Active In Progress</option>
                <option value="completed">Completed Stage</option>
              </select>

              <button
                type="submit"
                :disabled="statusForm.processing"
                class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-slate-800 disabled:opacity-55 disabled:cursor-not-allowed"
              >
                {{ statusForm.processing ? 'Saving changes...' : 'Commit Workflow Transition' }}
              </button>
            </form>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-3.5">Audit Metrics Metadata</h3>
            <div class="space-y-2.5 text-xs font-medium text-slate-500">
              <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                <span>System Manifest Identity</span>
                <span class="font-mono text-slate-900 font-bold">#{{ task.id }}</span>
              </div>
              <div class="flex items-center justify-between border-b border-slate-50 pb-2">
                <span>Initial Database Entry</span>
                <span class="text-slate-900 font-semibold">{{ formatDate(task.created_at) }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span>Last Modified Snapshot</span>
                <span class="text-slate-900 font-semibold">{{ formatDate(task.updated_at) }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>