<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'

import SidebarWrapper from './components/sidebar/SidebarWrapper.vue'
import { Icon } from '@iconify/vue'
import ColorMode from './components/ColorMode.vue'

import DisclaimerBanner from './components/DisclaimerBanner.vue'
import IconBranding from './components/icons/IconBranding.vue'
import { useUiStore } from './stores/ui'
import { useWindowSize } from '@vueuse/core'

const { width } = useWindowSize()
const uiStore = useUiStore()
</script>

<template>
  <header class="leading-normal max-h-screen lg:flex lg:place-items-center">
    <div class="wrapper pl-8 pr-8 lg:flex lg:items-start lg:flex-wrap">
      <div class="foss-fi-header__utilities-wrapper w-full text-right">
        <ColorMode />
      </div>
      <div class="foss-fi-header__branding text-center flex flex-col gap-2">
        <div class="flex gap-2 justify-center items-center">
          <IconBranding />
          <h1 class="text-3xl font-bold text-center">FIRE Calculator</h1>
        </div>
        <p class="text-sm">
          A <span class="italic">Financial Independence Retire Early </span> calculator for the
          Australian context.
        </p>
      </div>

      <nav
        class="w-full text-xs text-center mt-8 lg:text-left lg:-ml-4 lg:text-base lg:py-4 lg:mt-4"
      >
        <RouterLink to="/" class="hover:cursor-pointer inline-block px-4">Dashboard</RouterLink>
        <RouterLink
          to="/about"
          class="hover:cursor-pointer inline-block px-4 border-l border-[var(--color-border)]"
          >About</RouterLink
        >
      </nav>
    </div>
  </header>
  <main class="relative md:grid md:grid-cols-5">
    <!-- Smal;l screen- toggle ope -->
    <aside
      v-if="width < 760"
      :class="[
        'bg-[var(--color-background-mute)] h-screen absolute w-[90vw] transition-[left] duration-300 ease-in-out pb-16',
        uiStore.sidebarOpen ? 'left-0 overflow-scroll' : '-left-[90vw]',
      ]"
    >
      <div
        role="button"
        :class="[
          'absolute bg-[var(--color-background-mute)] cursor-pointer',
          uiStore.sidebarOpen
            ? 'right-1 top-1 shadow-none'
            : 'top-[15%] -right-10 h-1/4 w-10 rounded-tr-[10px] rounded-br-[10px] flex items-center justify-center shadow-[var(--shadow-elevation-low)]',
        ]"
        id="sidebar-toggle"
        @click="uiStore.toggleSidebar()"
      >
        <span
          v-if="!uiStore.sidebarOpen"
          class="whitespace-nowrap rotate-90 flex justify-center items-center gap-1 hover:text-[var(--color-text-muted)]"
          ><Icon icon="material-symbols:arrow-circle-up-outline" width="24" height="24" />
          Create new
        </span>
        <span v-else class="hover:text-[var(--color-text-muted)]"
          ><Icon icon="material-symbols:close-rounded" width="24" height="24" />
        </span>
      </div>
      <SidebarWrapper />
    </aside>
    <!-- Medium and above screens grid layout -->
    <aside v-else :class="['bg-[var(--color-background-mute)] h-screen']">
      <SidebarWrapper />
    </aside>
    <section class="foss-fi-dashboard border-1 rounded p-2">
      <RouterView />
    </section>
  </main>
  <DisclaimerBanner />
</template>

<style scoped>
@media (min-width: 1024px) {
  header {
    padding-right: calc(var(--section-gap) / 2);
  }
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}
</style>
