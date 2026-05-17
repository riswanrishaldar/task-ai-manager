<script setup>
defineProps({
  form: {
    type: Object,
    required: true,
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
</script>

<template>
  <div class="grid grid-cols-1 gap-6">
    <div>
      <label class="mb-2 block text-sm font-medium text-slate-700">Title</label>
      <input
        v-model="form.title"
        type="text"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        placeholder="Enter task title"
      />
      <p v-if="form.errors.title" class="mt-2 text-sm text-rose-600">{{ form.errors.title }}</p>
    </div>

    <div>
      <label class="mb-2 block text-sm font-medium text-slate-700">Description</label>
      <textarea
        v-model="form.description"
        rows="5"
        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        placeholder="Describe the task details"
      />
      <p v-if="form.errors.description" class="mt-2 text-sm text-rose-600">{{ form.errors.description }}</p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Priority</label>
        <select
          v-model="form.priority"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">Select priority</option>
          <option v-for="priority in priorities" :key="priority" :value="priority">
            {{ priority }}
          </option>
        </select>
        <p v-if="form.errors.priority" class="mt-2 text-sm text-rose-600">{{ form.errors.priority }}</p>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
        <select
          v-model="form.status"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">Select status</option>
          <option v-for="status in statuses" :key="status" :value="status">
            {{ status }}
          </option>
        </select>
        <p v-if="form.errors.status" class="mt-2 text-sm text-rose-600">{{ form.errors.status }}</p>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Due Date</label>
        <input
          v-model="form.due_date"
          type="date"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        />
        <p v-if="form.errors.due_date" class="mt-2 text-sm text-rose-600">{{ form.errors.due_date }}</p>
      </div>

      <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Assigned User</label>
        <select
          v-model="form.assigned_to"
          class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
        >
          <option value="">Select user</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <p v-if="form.errors.assigned_to" class="mt-2 text-sm text-rose-600">{{ form.errors.assigned_to }}</p>
      </div>
    </div>
  </div>
</template>