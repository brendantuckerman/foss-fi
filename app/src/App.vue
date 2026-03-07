<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, RouterView } from 'vue-router'
import SidebarWrapper from './components/sidebar/SidebarWrapper.vue'
import { Icon } from '@iconify/vue'
import ColorMode from './components/ColorMode.vue'

import { useWindowSize } from '@vueuse/core'
import DisclaimerBanner from './components/DisclaimerBanner.vue'
import IconBranding from './components/icons/IconBranding.vue'

const { width } = useWindowSize()
const toggleState = ref(false)
</script>

<template>
  <header>
    <div class="wrapper pl-8 pr-8">
      <div class="foss-fi-header__utilities-wrapper w-full text-right">
        <a href="/">Log in or Sign up</a>
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
        <p v-if="width < 860" class="text-sm flex">
          <Icon icon="material-symbols:right-click" width="24" height="24" />Click the tab on the
          left to enter details.
        </p>
        <p v-else class="text-sm">Use the panel to the left to enter details.</p>
      </div>

      <nav>
        <RouterLink to="/" class="hover:cursor-pointer">Dashboard</RouterLink>
        <RouterLink to="/about" class="hover:cursor-pointer">About</RouterLink>
      </nav>
    </div>
  </header>
  <main>
    <section
      :class="
        toggleState
          ? 'foss-fi-sidebar__section sidebar-open'
          : 'foss-fi-sidebar__section sidebar-closed'
      "
    >
      <div
        role="button"
        class="foss-fi-sidebar__toggle"
        id="sidebar-toggle"
        @click="toggleState = !toggleState"
      >
        <span v-if="!toggleState" class="foss-fi-sidebar__toggle-span"
          ><Icon icon="material-symbols:arrow-circle-up-outline" width="24" height="24" />Adjust
          values
        </span>
        <span v-else class="foss-fi-sidebar__toggle-span"
          ><Icon icon="material-symbols:close-rounded" width="24" height="24" />
        </span>
      </div>
      <SidebarWrapper />
    </section>
    <body class="foss-fi-dashboard border-1 p-2">
      <RouterView />
    </body>
  </main>
  <DisclaimerBanner />
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

main {
  position: relative;
}

.foss-fi-sidebar__section {
  background-color: var(--color-background-mute);
  height: 100vh;
  position: absolute;
  width: 90vw;
  transition: left 0.3s ease;
  padding-bottom: 4rem;
}

.foss-fi-sidebar__toggle {
  position: absolute;
  box-shadow: var(--shadow-elevation-low);
  background-color: var(--color-background-mute);
}

/* Open  Section and toggle*/
/* Position toggle to allow close */
.foss-fi-sidebar__toggle span:hover {
  color: var(--color-text-muted);
  cursor: pointer;
}

.foss-fi-sidebar__section.sidebar-open .foss-fi-sidebar__toggle {
  right: 0.25rem;
  top: 0.25rem;
  box-shadow: none;
}

.foss-fi-sidebar__section.sidebar-open {
  left: 0;
  overflow: scroll;
}

/* Closed Section and toggle */
.foss-fi-sidebar__section.sidebar-closed {
  left: -90vw;
}

.foss-fi-sidebar__section.sidebar-closed .foss-fi-sidebar__toggle {
  top: 15%;
  right: -40px;
  height: 25%;
  width: 40px;
  border-top-right-radius: 10px;
  border-bottom-right-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.foss-fi-sidebar__section.sidebar-closed .foss-fi-sidebar__toggle .foss-fi-sidebar__toggle-span {
  white-space: nowrap;
  transform: rotate(90deg);
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.25rem;
}

.foss-fi-sidebar__section.sidebar-open .foss-fi-sidebar__toggle .foss-fi-sidebar__toggle-span {
  box-shadow: none;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
