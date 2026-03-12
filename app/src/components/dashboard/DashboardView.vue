<script setup lang="ts">
import { useScenarioStore } from '@/stores/scenario'
import { useInputsStore } from '@/stores/inputs'
import DashboardCard from './DashboardCard.vue'
import { Icon } from '@iconify/vue'
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableFooter,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table'

const inputsStore = useInputsStore()

const scenarioStore = useScenarioStore()

console.log('Results', scenarioStore.calculations)

// const store = useScenarioStore()

// const scenario = store.scenario
</script>

<template>
  <section v-if="inputsStore.label">
    <h1 class="text-3xl font-bold text-center">{{ inputsStore.label }}</h1>
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
  </section>

  <div v-if="scenarioStore.calculations" class="foss-fi-dashboard__results flex flex-col gap-4">
    <section class="foss-fi-dashboard__results-overall grid gap-4">
      <h2 class="text-2xl">Your results</h2>
      <!-- Total Results -->
      <DashboardCard
        v-if="scenarioStore.calculations.yearsToFi && scenarioStore.calculations.yearOfFi"
        :title="scenarioStore.calculations.yearsToFi.toFixed(2).toString()"
        header-description="Years until FIRE"
      >
        <Icon
          icon="material-symbols:calendar-month-rounded"
          width="24"
          height="24"
          style="color: #b71319"
          class="inline"
        />
        <p v-if="scenarioStore.calculations.yearOfFi">
          You will reach FIRE in
          <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl">{{
            scenarioStore.calculations.yearOfFi.toString()
          }}</span>
        </p>
      </DashboardCard>
    </section>
    <section class="foss-fi-dashboard__results-pre-super grid gap-4">
      <h3 class="text-xl">Pre super</h3>
      <p>FIRE is broken into two phases- the pre super phase and the post super phase.</p>
      <!-- Super years -->
      <DashboardCard
        v-if="scenarioStore.calculations.superPreservationAge"
        :title="`${scenarioStore.calculations.superPreservationAge.toString()} / ${scenarioStore.calculations.yearsUntilPreservation.toString()}`"
        header-description="Age you can access your superannuation / Years until you can access your superannuation  "
      >
        <Icon
          icon="material-symbols:calendar-month-rounded"
          width="24"
          height="24"
          style="color: #b71319"
        />
        <p v-if="scenarioStore.calculations.yearOfFi">
          You can access your super in
          <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl">{{
            scenarioStore.calculations.preservationYear.toString()
          }}</span>
        </p>
      </DashboardCard>

      <!-- Pre Super Expenses and Savings rate -->

      <DashboardCard
        v-if="scenarioStore.calculations.savingsRate"
        :title="`${scenarioStore.calculations.savingsRate.toString()}%`"
        header-description="Your current savings rate."
      >
        <p v-if="scenarioStore.calculations.yearOfFi">
          You are spending
          <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl"
            >${{ scenarioStore.calculations.monthlyExpenses.toLocaleString() }}</span
          >
          per month.
        </p>
      </DashboardCard>

      <!-- Pre super $ -->

      <DashboardCard
        v-if="scenarioStore.calculations.postPv"
        :title="`$${scenarioStore.calculations.postPv.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`"
        header-description="Amount you need to get you to your super year."
      >
        <p v-if="scenarioStore.calculations.yearOfFi">
          You'll need to save
          <span class="foss-fi-dashboard__card-years-to-fi-span text-2xl"
            >${{
              scenarioStore.calculations.remainingPreSuper.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
            }}</span
          >.
        </p>
      </DashboardCard>

      <!-- Pre super savings schedule -->
      <Table v-if="scenarioStore.calculations.preSuperSchedule">
        <TableCaption>Schedule of savings towards your pre-FIRE amount.</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead class="w-[100px]"> Period </TableHead>
            <TableHead>Year</TableHead>
            <TableHead>Deposit</TableHead>
            <TableHead>Interest</TableHead>
            <TableHead>Saved so far</TableHead>
            <TableHead class="text-right"> Balance </TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="(row, key) in scenarioStore.calculations.preSuperSchedule" :key="key">
            <TableCell class="font-medium">
              {{ key }}
            </TableCell>
            <TableCell>{{ row.year }}</TableCell>
            <TableCell>${{ row.deposit?.toLocaleString() ?? '0' }}</TableCell>
            <TableCell>${{ row.interestMade?.toLocaleString() ?? '0' }}</TableCell>
            <TableCell>${{ row.savedSoFar?.toLocaleString() ?? '0' }}</TableCell>
            <TableCell class="text-right"> ${{ row.balance.toLocaleString() }} </TableCell>
          </TableRow>
        </TableBody>
        <TableFooter>
          <TableRow>
            <TableCell colspan="6"> You will reach your pre-Super FIRE number in ... </TableCell>
          </TableRow>
        </TableFooter>
      </Table>

      <!-- Pre super to zero schedule -->

      <hr />
    </section>
    <section class="foss-fi-dashboard__results-post-super">
      <h3 class="text-xl">Post super</h3>
    </section>
  </div>
</template>

<style scoped lang="css">
.foss-fi-dashboard__card-years-to-fi-span {
  font-weight: 700;
}
</style>
