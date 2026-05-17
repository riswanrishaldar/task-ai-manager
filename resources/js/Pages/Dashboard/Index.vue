<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import StatCard from '@/Components/Ui/StatCard.vue'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  stats: {
    type: Object,
    required: true,
  },
})

// Calculate real metrics for the SVG Graph UI matching the stats object directly
const total = computed(() => props.stats.total_tasks || 0)
const completed = computed(() => props.stats.completed_tasks || 0)
const pending = computed(() => props.stats.pending_tasks || 0)

const completionRate = computed(() => {
  if (total.value === 0) return 0
  return Math.round((completed.value / total.value) * 180) 
})

// Calculate the stroke-dasharray properties matching a circular arc length loop parameter
const radius = 50
const circumference = 2 * Math.PI * radius // ~314.16

const completedDashOffset = computed(() => {
  if (total.value === 0) return circumference
  const ratio = completed.value / total.value
  return circumference * (1 - ratio)
})
</script>

<template>
  <AppLayout>
    <div class="space-y-8">
      <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Dashboard</h1>
        <p class="text-sm text-slate-500">
          Real-time metrics visualization and task status reporting.
        </p>
      </div>

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard title="Total Tasks" :value="total" description="All tasks available" />
        <StatCard title="Completed Tasks" :value="completed" description="Finished workflows" tone="success" />
        <StatCard title="Pending Tasks" :value="pending" description="Waiting execution" tone="warning" />
        <StatCard title="High Priority" :value="stats.high_priority_tasks || 0" description="Urgent attention items" tone="danger" />
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2 flex flex-col justify-between">
          <div>
            <h2 class="text-base font-bold text-slate-900">Task Completion Analysis</h2>
            <p class="text-xs text-slate-400 mt-0.5">Live database proportions representation</p>
          </div>

          <div class="my-6 flex flex-col items-center justify-center gap-8 sm:flex-row">
            <div class="relative flex h-36 w-36 items-center justify-center">
              <svg class="h-full w-full -rotate-90" viewBox="0 0 120 120">
                <circle cx="60" cy="60" :r="radius" class="stroke-slate-100 fill-none" stroke-width="12" />
                <circle
                  cx="60"
                  cy="60"
                  :r="radius"
                  class="stroke-slate-900 fill-none transition-all duration-1000 ease-out"
                  stroke-width="12"
                  stroke-linecap="round"
                  :stroke-dasharray="circumference"
                  :stroke-dashoffset="completedDashOffset"
                />
              </svg>
              <div class="absolute text-center">
                <span class="text-2xl font-extrabold text-slate-900">
                  {{ total > 0 ? Math.round((completed / total) * 100) : 0 }}%
                </span>
                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mt-0.5">Done</p>
              </div>
            </div>

            <div class="flex-1 space-y-3.5 w-full max-w-xs">
              <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-1.5">
                  <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-slate-900"></span>Completed</span>
                  <span>{{ completed }} / {{ total }} Tasks</span>
                </div>
                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-slate-900 transition-all duration-500" :style="{ width: (total > 0 ? (completed/total)*100 : 0) + '%' }"></div>
                </div>
              </div>

              <div>
                <div class="flex items-center justify-between text-xs font-semibold text-slate-700 mb-1.5">
                  <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Remaining Pending</span>
                  <span>{{ pending }} / {{ total }} Tasks</span>
                </div>
                <div class="h-2 w-full rounded-full bg-slate-100 overflow-hidden">
                  <div class="h-full bg-amber-500 transition-all duration-500" :style="{ width: (total > 0 ? (pending/total)*100 : 0) + '%' }"></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col justify-between">
          <div>
            <h2 class="text-base font-bold text-slate-900">System Controls</h2>
            <p class="text-xs text-slate-400 mt-0.5">Shortcut paths mapping</p>
            
            <div class="mt-5 space-y-2.5">
              <Link
                :href="route('tasks.index')"
                class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50 hover:border-slate-300"
              >
                <span>Browse Task Records</span>
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
              </Link>
              
              <Link
                :href="route('tasks.create')"
                class="flex items-center justify-between rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800"
              >
                <span>Initialize New Task</span>
                <span class="text-xs bg-white/20 px-1.5 py-0.5 rounded font-mono">+ N</span>
              </Link>
            </div>
          </div>

          <div class="mt-6 border-t border-slate-100 pt-4">
            <span class="text-[11px] font-medium text-slate-400 tracking-wide uppercase block">Integration State</span>
            <div class="flex items-center gap-2 mt-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200/60 rounded-xl px-3 py-2">
              <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
              
            </div>
          </div>
        </div>

      </div>
    </div>
  </AppLayout>
</template>