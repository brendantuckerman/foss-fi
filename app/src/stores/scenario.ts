// app/src/stores/scenario.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useScenarioStore = defineStore('scenario', () => {
  const scenario = ref(null)

  async function fetchScenario(id: number) {
    const res = await fetch(`/api/scenarios/${id}`, {
      headers: { Accept: 'application/json' },
    })
    if (!res.ok) throw new Error(`Failed: ${res.status}`)
    scenario.value = await res.json()
  }

  return { scenario, fetchScenario }
})
