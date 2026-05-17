<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TaskFormFields from '@/Components/Task/TaskFormFields.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  task: {
    type: Object,
    default: null,
  },
  users: {
    type: Array,
    default: () => [],
  },
  priorities: {
    type: Array,
    default: () => [],
  },
  statuses: {
    type: Array,
    default: () => [],
  },
})

const isEdit = !!props.task
const taskData = props.task?.data ? props.task.data : props.task

const form = useForm({
  title: taskData?.title || '',
  description: taskData?.description || '',
  priority: taskData?.priority || '',
  status: taskData?.status || 'pending',
  due_date: taskData?.due_date || '',
  assigned_to: taskData?.assigned_to || '',
})

const submit = () => {
  if (isEdit) {
    form.put(route('tasks.update', taskData.id))
    return
  }
  form.post(route('tasks.store'))
}
</script>

<template>
  <AppLayout>
    <div class="mx-auto max-w-3xl space-y-6">
      <div class="border-b border-slate-200 pb-5">
        <span class="text-xs font-bold uppercase tracking-widest text-slate-400 font-mono">
          {{ isEdit ? 'Modification State' : 'Creation Stage' }}
        </span>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl mt-0.5">
          {{ isEdit ? 'Edit Task Details' : 'Create New Task' }}
        </h1>
        <p class="mt-1.5 text-sm text-slate-500">
          Set execution parameters, assign resources, and establish project priority rules.
        </p>
      </div>

      <form 
        @submit.prevent="submit" 
        class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6"
      >
        <div class="space-y-5">
          <TaskFormFields
            :form="form"
            :users="users"
            :priorities="priorities"
            :statuses="statuses"
          />
        </div>

        <div class="mt-8 flex flex-col-reverse gap-2 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end sm:gap-3">
          <Link
            :href="route('tasks.index')"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 active:scale-95"
          >
            Cancel Execution
          </Link>

          <button
            type="submit"
            :disabled="form.processing"
            class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50 active:scale-95"
          >
            <svg 
              v-if="form.processing" 
              class="mr-2 h-4 w-4 animate-spin text-white" 
              fill="none" 
              viewBox="0 0 24 24"
            >
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            {{ form.processing ? 'Saving Records...' : (isEdit ? 'Update Task Parameters' : 'Deploy Task') }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>