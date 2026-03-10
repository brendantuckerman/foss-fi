<script setup lang="ts">
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'

const inputsStore = useInputsStore()

// const store = useScenarioStore()

// const scenario = store.scenario
</script>

<template>
  <h1 v-if="inputsStore" class="text-xl font-bold text-center">{{ inputsStore.label }}</h1>
  <h1 v-else>Your Dashboard</h1>
  <p v-if="!inputsStore">
    Use the tab to the left to enter your details. Your results will appear below
  </p>

  <ul>
    <li>{{ inputsStore.age }}</li>
    <li>{{ inputsStore.income }}</li>
    <li>{{ inputsStore.outgoings }}</li>
    <li>{{ inputsStore.investmentAmount }}</li>
    <li>{{ inputsStore.currentSuper }}</li>
    <li>{{ inputsStore.superGuaranteee }}</li>
    <li>{{ inputsStore.returnRate }}</li>
    <li>{{ inputsStore.inflationRate }}</li>
  </ul>
</template>
