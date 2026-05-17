<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const navigation = [
  { name: 'Dashboard', href: route('dashboard'), key: 'dashboard' },
  { name: 'Tasks', href: route('tasks.index'), key: 'tasks.index' },
]

const isActive = (key) => {
  if (key === 'dashboard') return route().current('dashboard')
  if (key === 'tasks.index') return route().current('tasks.*')
  return false
}

const profileOpen = ref(false)
const mobileMenuOpen = ref(false)
const profileMenuRef = ref(null)

const userInitials = computed(() => {
  const name = user.value?.name?.trim()
  if (!name) return 'U'
  return name
    .split(' ')
    .filter(Boolean)
    .slice(0, 2)
    .map((part) => part.charAt(0).toUpperCase())
    .join('')
})

const toggleProfileMenu = () => { profileOpen.value = !profileOpen.value }
const closeMenus = () => {
  profileOpen.value = false
  mobileMenuOpen.value = false
}

const handleClickOutside = (event) => {
  if (profileMenuRef.value && !profileMenuRef.value.contains(event.target)) {
    profileOpen.value = false
  }
}

const handleKeydown = (event) => {
  if (event.key === 'Escape') closeMenus()
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside)
  document.addEventListener('keydown', handleKeydown)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside)
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased">
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-md">
      <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8">
        
        <div class="flex items-center gap-8">
          <Link :href="route('dashboard')" class="flex items-center gap-3 active:scale-95 transition-transform">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-xs font-bold text-white shadow-sm">
              TM
            </div>
            <span class="text-sm font-bold tracking-tight text-slate-900">TaskManager</span>
          </Link>

          <nav class="hidden items-center gap-1 md:flex">
            <Link
              v-for="item in navigation"
              :key="item.key"
              :href="item.href"
              class="rounded-lg px-3.5 py-1.5 text-sm font-medium transition-all duration-200"
              :class="isActive(item.key)
                ? 'bg-slate-900 text-white shadow-sm'
                : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'"
            >
              {{ item.name }}
            </Link>
          </nav>
        </div>

        <div class="flex items-center gap-3">
          <Link
            :href="route('tasks.create')"
            class="hidden rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 active:scale-95 md:inline-flex"
          >
            Create Task
          </Link>

          <div ref="profileMenuRef" class="relative hidden md:block">
            <button
              type="button"
              class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-white p-1.5 pr-3 text-left shadow-sm transition hover:border-slate-300 focus:outline-none"
              @click.stop="toggleProfileMenu"
            >
              <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800">
                {{ userInitials }}
              </div>
              <div class="min-w-0 max-w-[100px]">
                <p class="truncate text-xs font-semibold text-slate-900">{{ user?.name || 'User' }}</p>
              </div>
              <svg class="h-3.5 w-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': profileOpen }" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.168l3.71-3.938a.75.75 0 1 1 1.08 1.04l-4.25 4.512a.75.75 0 0 1-1.08 0L5.21 8.27a.75.75 0 0 1 .02-1.06Z" clip-rule="evenodd" />
              </svg>
            </button>

            <div v-if="profileOpen" class="absolute right-0 mt-2 w-64 origin-top-right rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl ring-1 ring-black/5 z-50">
              <div class="px-3 py-2.5 border-b border-slate-100">
                <p class="text-xs font-medium text-slate-400">Signed in as</p>
                <p class="truncate text-sm font-semibold text-slate-800">{{ user?.email }}</p>
                <span class="mt-1.5 inline-flex items-center rounded-md bg-slate-50 border border-slate-200 px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider text-slate-600">
                  {{ user?.role || 'member' }}
                </span>
              </div>
              <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium text-rose-600 hover:bg-rose-50 transition"
                @click="closeMenus"
              >
                Sign Out
              </Link>
            </div>
          </div>

          <button
            type="button"
            class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 md:hidden"
            @click="mobileMenuOpen = !mobileMenuOpen"
          >
            <svg v-if="!mobileMenuOpen" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div v-if="mobileMenuOpen" class="border-t border-slate-200 bg-white md:hidden shadow-inner px-4 py-4 space-y-4">
        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-3">
          <div class="min-w-0">
            <p class="truncate text-sm font-bold text-slate-900">{{ user?.name }}</p>
            <p class="text-xs uppercase tracking-wider text-slate-400 font-medium mt-0.5">{{ user?.role || 'member' }}</p>
          </div>
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100 transition"
            @click="closeMenus"
          >
            Logout
          </Link>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <Link
            v-for="item in navigation"
            :key="item.key"
            :href="item.href"
            class="flex items-center justify-center rounded-xl p-3 text-sm font-semibold transition"
            :class="isActive(item.key) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600'"
            @click="closeMenus"
          >
            {{ item.name }}
          </Link>
        </div>
        <Link
          :href="route('tasks.create')"
          class="flex w-full items-center justify-center rounded-xl bg-emerald-600 p-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-500"
          @click="closeMenus"
        >
          + Create New Task
        </Link>
      </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <slot />
    </main>
  </div>
</template>