<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, RouterView } from 'vue-router'
import SidebarWrapper from './components/sidebar/SidebarWrapper.vue'
import { Icon } from '@iconify/vue'
const toggleState = ref(false)
</script>

<template>
  <header>
    <div class="wrapper">
      <nav>
        <RouterLink to="/">Home</RouterLink>
        <RouterLink to="/about">About</RouterLink>
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
    <body>
      <RouterView />
    </body>
  </main>
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
  right: 0;
  top: 0;
}

.foss-fi-sidebar__section.sidebar-open {
  left: 0;
  box-shadow: var(--shadow-elevation-medium);
}

/* Closed Section and toggle */
.foss-fi-sidebar__section.sidebar-closed {
  left: -90vw;
}

.foss-fi-sidebar__section.sidebar-closed .foss-fi-sidebar__toggle {
  top: 35%;
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
