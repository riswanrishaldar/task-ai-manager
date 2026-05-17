<script setup>
import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({}),
  },
  users: {
    type: Array,
    default: () => [],
  },
})

const form = reactive({
  search: props.filters.search || '',
  status: props.filters.status || '',
  priority: props.filters.priority || '',
  assigned_to: props.filters.assigned_to || '',
})

let debounceTimer = null

watch(
  () => ({ ...form }),
  () => {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
      router.get(route('tasks.index'), form, {
        preserveState: true,
        replace: true,
      })
    }, 400)
  },
  { deep: true }
)

const resetFilters = () => {
  form.search = ''
  form.status = ''
  form.priority = ''
  form.assigned_to = ''
}
</script>

<template>
  <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Search</label>
        <input
          v-model="form.search"
          type="text"
          placeholder="Search title or description"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        />
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
        <select
          v-model="form.status"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">All Statuses</option>
          <option value="pending">Pending</option>
          <option value="inprogress">In Progress</option>
          <option value="completed">Completed</option>
        </select>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Priority</label>
        <select
          v-model="form.priority"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">All Priorities</option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Assigned User</label>
        <select
          v-model="form.assigned_to"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">All Users</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>
    </div>

    <div class="mt-4 flex justify-end">
      <button
        type="button"
        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
        @click="resetFilters"
      >
        Reset Filters
      </button>
    </div>
  </div>
</template>