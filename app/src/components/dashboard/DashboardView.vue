<script setup lang="ts">
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'

const formStore = useInputsStore()

// const store = useScenarioStore()

// const scenario = store.scenario
</script>

<template>
  <h1>Dashboard</h1>
  <p>Here are your results:</p>
  <ul>
    <li v-for="(item, key) in formStore" :key="key">{{ key }}: {{ item }}</li>
  </ul>
</template>
