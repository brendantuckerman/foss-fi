import { ref } from 'vue'
import { defineStore } from 'pinia'

// Make form values global (in cases where user is not auth)
// We might get a type mismatch for the floats...
export const useInputsStore = defineStore('inputs', () => {
  const label = ref<string | null>(null)
  const age = ref<number | null>(null)
  const income = ref<number | null>(null)
  const postTaxIncome = ref<number | null>(null)
  const outgoings = ref<number | null>(null)
  const investmentAmount = ref<number | null>(null)
  const currentSuper = ref<number | null>(null)
  const superGuaranteee = ref<number | null>(null)
  const returnRate = ref<number | null>(null)
  const inflationRate = ref<number | null>(null)

  return {
    label,
    age,
    income,
    postTaxIncome,
    outgoings,
    investmentAmount,
    currentSuper,
    superGuaranteee,
    returnRate,
    inflationRate,
  }
})
