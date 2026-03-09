// app/src/stores/scenario.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'
interface FormData {
  label: string
  age: number
  income: number
  outgoings: number
  investmentAmount: number
  super: number
  superGuarantee: number
  returnRate: number
  inflationRate: number
}

export const useScenarioStore = defineStore('scenario', () => {
  const calculations = ref(null)

  async function calculateScenario(data: FormData) {
    const res = await fetch('/api/scenario/calculate', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    })

    if (!res.ok) throw new Error(`Failed: ${res.status}`)
    calculations.value = await res.json()
  }

  return { calculations, calculateScenario }
})
