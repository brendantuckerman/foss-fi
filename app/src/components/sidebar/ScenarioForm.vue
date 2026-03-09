<script setup lang="ts">
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip'
import { Icon } from '@iconify/vue'
import { useWindowSize } from '@vueuse/core'

const { width } = useWindowSize()
// Form submission
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'
const store = useScenarioStore()
const inputsStore = useInputsStore()

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

async function handleSubmit(data: FormData) {
  // Set up store
  inputsStore.label = data.label
  inputsStore.age = data.age
  inputsStore.income = data.income
  inputsStore.outgoings = data.outgoings

  await store.calculateScenario(data)
  console.log(store.calculations)
}
</script>

<template>
  <div class="foss-fi-sidebar__inner-wrapper flex flex-col gap-4">
    <h2 class="text-xl">Create a new scenario</h2>
    <FormKit
      type="form"
      @submit="handleSubmit"
      submit-label="Calculate"
      name="scenario-form"
      id="scenario-form"
    >
      <FormKit
        type="text"
        name="label"
        label="Label"
        help="Give your scenario a name (e.g. 'Conservative')"
        placeholder="My financial scenario"
        validation="required"
      />
      <FormKit
        type="number"
        name="age"
        label="Age"
        validation="min:0|max:120"
        :validation-messages="{
          min: 'Must be between 0 and 120.',
          max: 'Must be between 0 and 120.',
        }"
      />
      <FormKit
        type="number"
        name="income"
        label="Income"
        validation="required|min:0|max:10000000"
        :validation-messages="{
          min: 'Must be between $0 and $10,000,000.',
          max: 'Must be between $0 and $10,000,000.',
        }"
        :help="width < 860 ? `Your household's annual income after tax.` : ''"
      >
        <template v-if="width > 860" #label="{ label, id }">
          <label :for="id" class="formkit-label flex items-center gap-1">
            {{ label }}
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger type="button">
                  <Icon
                    icon="material-symbols:info-outline-rounded"
                    width="24"
                    height="24"
                    style="color: #b71319"
                  />
                </TooltipTrigger>
                <TooltipContent>
                  <p>Your annual income after tax.</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </label>
        </template>
      </FormKit>
      <FormKit
        type="number"
        name="outgoings"
        label="Outgoings"
        validation="required|min:0|max:10000000"
        :help="width < 860 ? `Your annual expenses excluding Super and savings.` : ''"
        :validation-messages="{
          min: 'Must be between $0 and $10,000,000.',
          max: 'Must be between $0 and $10,000,000.',
        }"
      >
        <template v-if="width > 860" #label="{ label, id }">
          <label :for="id" class="formkit-label flex items-center gap-1">
            {{ label }}
            <TooltipProvider>
              <Tooltip>
                <TooltipTrigger type="button">
                  <Icon
                    icon="material-symbols:info-outline-rounded"
                    width="24"
                    height="24"
                    style="color: #b71319"
                  />
                </TooltipTrigger>
                <TooltipContent>
                  <p>Your annual expenses excluding Super and savings.</p>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </label>
        </template>
      </FormKit>

      <FormKit
        type="number"
        name="investmentAmount"
        label="Current Net Worth (Excluding Super or Home Equity)"
        validation="min:0|max:10000000"
        :validation-messages="{
          min: 'Must be between $0 and $10,000,000.',
          max: 'Must be between $0 and $10,000,000.',
        }"
      />
      <FormKit
        type="number"
        name="super"
        label="Current Superannuation Balance"
        validation="min:0|max:10000000"
        :validation-messages="{
          min: 'Must be between $0 and $10,000,000.',
          max: 'Must be between $0 and $10,000,000.',
        }"
      />
      <FormKit
        type="number"
        name="superGuarantee"
        label="Employer Super Guarantee Rate (%)"
        step="0.1"
        value="12.00"
        help="From July 1, 2025 this is 12% if you are 18 or older."
      />

      <FormKit
        type="number"
        name="returnRate"
        label="Return Rate (%)"
        step="0.01"
        value="8.00"
        validation="min:0|max:100"
        :validation-messages="{
          min: 'Must be between 0% and 100%.',
          max: 'Must be between 0% and 100%.',
        }"
      />
      <FormKit
        type="number"
        name="inflationRate"
        label="Inflation Rate (%)"
        step="0.01"
        value="3.00"
        validation="min:0|max:100"
        :validation-messages="{
          min: 'Must be between 0% and 100%.',
          max: 'Must be between 0% and 100%.',
        }"
      />
    </FormKit>
  </div>
</template>

<style lang="css" scoped>
.foss-fi-sidebar__inner-wrapper {
  padding: 2rem;
}

#scenario-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
