<script setup lang="ts">
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'
import DashboardCard from './DashboardCard.vue'

const inputsStore = useInputsStore()

const scenarioStore = useScenarioStore()

console.log('Results', scenarioStore.calculations.superPreservationAge)

// const store = useScenarioStore()

// const scenario = store.scenario
</script>

<template>
  <h1 v-if="inputsStore" class="text-3xl font-bold text-center">{{ inputsStore.label }}</h1>
  <h1 v-else>Your Dashboard</h1>
  <p class="text-xs">A FIRE Dashboard</p>
  <p v-if="!inputsStore">
    Use the tab to the left to enter your details. Your results will appear below
  </p>
  <h2 class="text-2xl">Your information</h2>
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

  <div v-if="scenarioStore" class="foss-fi-dashboard__results flex flex-col gap-4">
    <section class="foss-fi-dashboard__results-overall">
      <h2 class="text-2xl">Your results</h2>
      <!-- Total Results -->
      <DashboardCard
        v-if="scenarioStore.calculations.yearsToFi"
        :title="scenarioStore.calculations.yearsToFi"
      >
        <p v-if="scenarioStore.calculations.yearOfFi">
          You will reach FIRE in
          <span class="font-bold text-2xl">{{ scenarioStore.calculations.yearOfFi }}</span>
        </p>
      </DashboardCard>
    </section>
    <section class="foss-fi-dashboard__results-pre-super">
      <h3 class="text-xl">Pre super</h3>
      <p>FIRE is broken into two phases- the pre super phase and the post super phase.</p>

      <hr />
    </section>
    <section class="foss-fi-dashboard__results-post-super">
      <h3 class="text-xl">Post super</h3>
    </section>
  </div>
</template>
