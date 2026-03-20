<script setup lang="ts">
import { computed, watch } from 'vue'
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'
import DashboardCard from './DashboardCard.vue'
import { Icon } from '@iconify/vue'
import { useDebounceFn, useWindowSize } from '@vueuse/core'
import type { ChartConfig } from '@/components/ui/chart'
import DashboardTableChart from './DashboardTableChart.vue'
import DashboardDonut from './DashboardDonut.vue'
import { Slider } from '@/components/ui/slider'

const { width } = useWindowSize()

// Pinia stores for form inputs and scenarioResults
const inputsStore = useInputsStore()
const scenarioStore = useScenarioStore()

// Pre super savings table variables
// This could probably be recycled for the preSuperToZero chart
const preSuperChartConfig = {
  balance: {
    label: 'Savings Total',
    color: 'var(--chart-3)',
  },
} satisfies ChartConfig

const preSuperTableColumns = [
  { label: 'Year', getValue: (row: unknown) => String((row as any).year) },
  {
    label: 'Deposit',
    getValue: (row: unknown) => `$${(row as any).deposit?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Interest',
    getValue: (row: unknown) => `$${(row as any).interestMade?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Annual savings',
    getValue: (row: unknown) => `$${(row as any).savedSoFar?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Balance',
    getValue: (row: unknown) => `$${(row as any).balance?.toLocaleString()}`,
    class: 'text-right balance-column',
  },
]

const preSuperChartData = computed(() => scenarioStore.calculations?.preSuperSchedule ?? [])

const preSupertoZeroChartData = computed(
  () => scenarioStore.calculations?.preSuperToZeroSchedule ?? [],
)

// Pre super to 0 variables
const preSuperToZeroTableColumns = [
  { label: 'Year', getValue: (row: unknown) => String((row as any).year) },

  {
    label: 'Interest Earned',
    getValue: (row: unknown) => `$${(row as any).interestMade?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Principal',
    getValue: (row: unknown) => `$${(row as any).balance?.toLocaleString() ?? '0'}`,
  },
]

// Data for super donut
const donutData = computed(() => [
  {
    label: '$' + Math.floor(scenarioStore.calculations?.superResult ?? 0).toLocaleString(),
    value: scenarioStore.calculations?.superResult ?? 0,
  },
  {
    label: '$' + Math.floor(scenarioStore.calculations?.superNeeded ?? 0).toLocaleString(),
    value: scenarioStore.calculations?.superNeeded ?? 0,
  },
])

// Auto-recalculate when slider-controlled inputs change
const recalculate = useDebounceFn(async () => {
  if (!inputsStore.label) return
  await scenarioStore.calculateScenario({
    label: inputsStore.label!,
    age: inputsStore.age!,
    income: inputsStore.income!,
    postTaxIncome: inputsStore.postTaxIncome!,
    outgoings: inputsStore.outgoings!,
    investmentAmount: inputsStore.investmentAmount!,
    super: inputsStore.currentSuper!,
    superGuarantee: inputsStore.superGuaranteee!,
    returnRate: inputsStore.returnRate!,
    inflationRate: inputsStore.inflationRate!,
  })
}, 400)

watch(
  () => [
    inputsStore.income,
    inputsStore.postTaxIncome,
    inputsStore.outgoings,
    inputsStore.investmentAmount,
    inputsStore.currentSuper,
    inputsStore.superGuaranteee,
    inputsStore.returnRate,
    inputsStore.inflationRate,
  ],
  recalculate,
)

// Data for post-super with contributions
const postSuperContributionsData = computed(
  () => scenarioStore.calculations?.postSuperContributionPhase ?? [],
)
// Post superwith contribution variables
const postSuperContributionsColumns = [
  { label: 'Year', getValue: (row: unknown) => String((row as any).year) },
  { label: 'Annual Deposit', getValue: (row: unknown) => String((row as any).regularDeposit) },

  {
    label: 'Interest Earned',
    getValue: (row: unknown) => `$${(row as any).interestMade?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Principal',
    getValue: (row: unknown) => `$${(row as any).balance?.toLocaleString() ?? '0'}`,
  },
]

const postSuperChartConfig = {
  balance: {
    label: 'Super Total',
    color: 'var(--chart-3)',
  },
} satisfies ChartConfig

// Post super post fire
const postSuperInterestData = computed(
  () => scenarioStore.calculations?.postSuperInterestPhase ?? [],
)

const postSuperInterestColumns = [
  { label: 'Year', getValue: (row: unknown) => String((row as any).year) },

  {
    label: 'Interest Earned',
    getValue: (row: unknown) => `$${(row as any).interestMade?.toLocaleString() ?? '0'}`,
  },
  {
    label: 'Principal',
    getValue: (row: unknown) => `$${(row as any).balance?.toLocaleString() ?? '0'}`,
  },
]

// Debug results
console.log('Results', scenarioStore.calculations)
</script>

<template>
  <section v-if="inputsStore.label" class="pb-4">
    <div class="text-center">
      <h1 class="text-3xl font-bold text-center">{{ inputsStore.label }}</h1>
      <p class="text-xs pb-4 text-muted-foreground">A FIRE Dashboard</p>
    </div>
    <hr />
    <div class="p-2 flex flex-col gap-2">
      <h2 class="text-2xl font-bold text-center">Your Information</h2>
      <p class="text-sm font-medium">Age: {{ inputsStore.age }}</p>
      <div class="flex flex-col gap-4">
        <div>
          <label class="text-sm font-medium"
            >Pre-tax Income: ${{ inputsStore.income?.toLocaleString() }}</label
          >
          <Slider
            :min="0"
            :max="500000"
            :step="1000"
            :model-value="[inputsStore.income ?? 0]"
            @update:model-value="(v) => (inputsStore.income = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Post-tax Income: ${{ inputsStore.postTaxIncome?.toLocaleString() }}</label
          >
          <Slider
            :min="0"
            :max="500000"
            :step="1000"
            :model-value="[inputsStore.postTaxIncome ?? 0]"
            @update:model-value="(v) => (inputsStore.postTaxIncome = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Outgoings: ${{ inputsStore.outgoings?.toLocaleString() }}</label
          >
          <Slider
            :min="0"
            :max="200000"
            :step="500"
            :model-value="[inputsStore.outgoings ?? 0]"
            @update:model-value="(v) => (inputsStore.outgoings = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Net Worth: ${{ inputsStore.investmentAmount?.toLocaleString() }}</label
          >
          <Slider
            :min="0"
            :max="2000000"
            :step="1000"
            :model-value="[inputsStore.investmentAmount ?? 0]"
            @update:model-value="(v) => (inputsStore.investmentAmount = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Super Balance: ${{ inputsStore.currentSuper?.toLocaleString() }}</label
          >
          <Slider
            :min="0"
            :max="2000000"
            :step="1000"
            :model-value="[inputsStore.currentSuper ?? 0]"
            @update:model-value="(v) => (inputsStore.currentSuper = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Super Guarantee: {{ inputsStore.superGuaranteee }}%</label
          >
          <Slider
            :min="0"
            :max="30"
            :step="0.5"
            :model-value="[inputsStore.superGuaranteee ?? 12]"
            @update:model-value="(v) => (inputsStore.superGuaranteee = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium">Return Rate: {{ inputsStore.returnRate }}%</label>
          <Slider
            :min="0"
            :max="20"
            :step="0.25"
            :model-value="[inputsStore.returnRate ?? 8]"
            @update:model-value="(v) => (inputsStore.returnRate = v?.[0] ?? null)"
          />
        </div>
        <div>
          <label class="text-sm font-medium"
            >Inflation Rate: {{ inputsStore.inflationRate }}%</label
          >
          <Slider
            :min="0"
            :max="15"
            :step="0.25"
            :model-value="[inputsStore.inflationRate ?? 3]"
            @update:model-value="(v) => (inputsStore.inflationRate = v?.[0] ?? null)"
          />
        </div>
      </div>
    </div>
  </section>
  <hr />
  <div v-if="scenarioStore.calculations" class="foss-fi-dashboard__results p-2 flex flex-col gap-4">
    <section class="foss-fi-dashboard__results-overall grid gap-4">
      <h2 class="text-2xl font-bold text-center">Your Results</h2>
      <!-- Total Results -->
      <DashboardCard
        v-if="scenarioStore.calculations.yearsToFi && scenarioStore.calculations.yearOfFi"
        :title="scenarioStore.calculations.yearsToFi.toFixed(2).toString()"
        header-description="Years until FIRE"
      >
        <div class="flex gap-2 items-center">
          <Icon
            icon="material-symbols:calendar-month-rounded"
            width="24"
            height="24"
            style="color: var(--chart-1)"
            class="inline"
          />
          <p v-if="scenarioStore.calculations.yearOfFi" class="text-sm">
            You will reach FIRE in
            <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl">{{
              scenarioStore.calculations.yearOfFi.toString()
            }}</span>
          </p>
        </div>
      </DashboardCard>
    </section>
    <section class="foss-fi-dashboard__results-pre-super flex flex-col gap-4">
      <h3 class="text-xl font-bold text-center">Phase 1: Building your pre-Super amount</h3>
      <p class="text-xs pb-4 text-muted-foreground">
        Working towards FIRE is broken into two phases: the first phase builds enough savings to
        support you until you can access your super. The second phase is building your super.
      </p>
      <!-- Super years -->
      <DashboardCard
        v-if="scenarioStore.calculations.superPreservationAge"
        :title="`${scenarioStore.calculations.superPreservationAge.toString()} / ${scenarioStore.calculations.yearsUntilPreservation.toString()}`"
        header-description="Age you can access your superannuation / Years until you can access your superannuation  "
      >
        <div class="flex gap-2 items-center">
          <Icon
            icon="material-symbols:calendar-month-rounded"
            width="24"
            height="24"
            style="color: var(--chart-1)"
          />
          <p v-if="scenarioStore.calculations.yearOfFi" class="text-sm">
            This will be the year
            <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl">{{
              scenarioStore.calculations.preservationYear.toString()
            }}</span>
          </p>
        </div>
      </DashboardCard>

      <!-- Pre Super Expenses and Savings rate -->

      <DashboardCard
        v-if="scenarioStore.calculations.savingsRate"
        :title="`${scenarioStore.calculations.savingsRate.toString()}%`"
        header-description="Your current savings rate."
      >
        <div class="flex gap-2 items-center">
          <Icon
            icon="material-symbols:monetization-on"
            width="24"
            height="24"
            style="color: var(--chart-5)"
          />
          <p v-if="scenarioStore.calculations.monthlyExpenses" class="text-sm">
            You are spending
            <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl"
              >${{ scenarioStore.calculations.monthlyExpenses.toLocaleString() }}</span
            >
            per month.
          </p>
        </div>
      </DashboardCard>

      <!-- Pre super $ -->

      <DashboardCard
        v-if="scenarioStore.calculations.postPv"
        :title="`$${scenarioStore.calculations.postPv.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`"
        header-description="Investment target. Having this amount invested / saved will be enough to support you until your super preservation age."
      >
        <div class="flex gap-2 items-center">
          <Icon
            icon="material-symbols:target"
            width="24"
            height="24"
            style="color: var(--chart-4); min-width: 25px"
          />
          <p v-if="scenarioStore.calculations.yearOfFi" class="text-sm col-start-2 col-span-2">
            You'll need to save
            <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl"
              >${{
                scenarioStore.calculations.remainingPreSuper.toLocaleString(undefined, {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              }}</span
            >
            more to reach this target. You will reach this savings target in
            <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl">{{
              scenarioStore.calculations.yearOfPreSuper
            }}</span>
          </p>
        </div>
      </DashboardCard>

      <!-- Pre super savings -->
      <DashboardTableChart
        v-if="scenarioStore.calculations?.preSuperSchedule"
        title="Schedule showing savings towards your pre-FIRE amount."
        :data="preSuperChartData"
        :table-columns="preSuperTableColumns"
        :chart-config="preSuperChartConfig"
      />

      <!-- Pre super to 0 -->
      <DashboardTableChart
        v-if="scenarioStore.calculations?.preSuperToZeroSchedule"
        title="Schedule showing pre-super amount to zero."
        :data="preSupertoZeroChartData"
        :table-columns="preSuperToZeroTableColumns"
        :chart-config="preSuperChartConfig"
      />
    </section>
    <hr />
    <section class="foss-fi-dashboard__results-post-super flex flex-col gap-4">
      <h3 class="text-xl text-center">Phase 2: Focus on Super</h3>
      <p class="text-xs pb-4 text-muted-foreground">
        During the second phase, you can put all your savings towards super.
      </p>
      <!-- Super target -->
      <DashboardCard
        v-if="scenarioStore.calculations.superTarget"
        :title="`$${scenarioStore.calculations.superTarget.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`"
        header-description="How much you need in Super at your preservation age to last you forever."
      >
      </DashboardCard>

      <!-- Super Donut -->
      <DashboardDonut
        v-if="scenarioStore.calculations?.superRequiredForFi"
        :title="scenarioStore.calculations.superRequiredForFi.toLocaleString()"
        :data="donutData"
      />

      <!-- Super schedule with contributions -->
      <DashboardTableChart
        v-if="scenarioStore.calculations?.postSuperContributionPhase"
        title="Schedule showing super amount AFTER reaching your pre-super number."
        :data="postSuperContributionsData"
        :table-columns="postSuperContributionsColumns"
        :chart-config="postSuperChartConfig"
      />
      <!-- Super shcedule  post FIRE -->
      <DashboardTableChart
        v-if="scenarioStore.calculations?.postSuperInterestPhase"
        title="Schedule showing super amount post-FIRE."
        :data="postSuperInterestData"
        :table-columns="postSuperInterestColumns"
        :chart-config="postSuperChartConfig"
      />
    </section>
  </div>
  <div v-else class="h-20 my-0 mx-auto w-full pt-8">
    <p v-if="width < 860" class="text-sm text-center flex">
      <Icon icon="material-symbols:right-click" width="24" height="24" />Tap the tab on the left to
      enter details. Your results wil appear here.
    </p>
    <p v-else class="text-sm">Use the panel to the left to enter details.</p>
  </div>
</template>

<style scoped lang="css">
.foss-fi-dashboard__card-years-to-fi-span {
  font-weight: 700;
}
</style>
