<script setup lang="ts">
import { onMounted } from 'vue'
import { useScenarioStore } from '@/stores/scenario'

const store = useScenarioStore()
onMounted(() => store.fetchScenario(1))
</script>

<template>
  <div v-if="store.scenario">{{ store.scenario.label }}</div>
</template>
