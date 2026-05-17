<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  title: {
    type: String,
    default: 'No records found',
  },
  description: {
    type: String,
    default: 'There is nothing to display right now.',
  },
  buttonText: {
    type: String,
    default: '',
  },
  buttonHref: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center shadow-sm">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
      <svg class="h-7 w-7 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
        <path d="M4 7a2 2 0 0 1 2-2h3l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7Z" />
      </svg>
    </div>

    <h3 class="mt-5 text-lg font-semibold text-slate-900">{{ title }}</h3>
    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">{{ description }}</p>

    <div v-if="buttonText && buttonHref" class="mt-6">
      <Link
        :href="buttonHref"
        class="inline-flex rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800"
      >
        {{ buttonText }}
      </Link>
    </div>
  </div>
</template>