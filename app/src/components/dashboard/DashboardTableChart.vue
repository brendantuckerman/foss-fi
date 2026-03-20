<script setup lang="ts">
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

import { Card } from '@/components/ui/card'

import type { ChartConfig } from '@/components/ui/chart'
import { VisGroupedBar, VisAxis, VisXYContainer } from '@unovis/vue'
import {
  ChartContainer,
  ChartCrosshair,
  ChartTooltip,
  ChartTooltipContent,
  componentToString,
} from '@/components/ui/chart'
import { ref } from 'vue'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'

interface DataItem {
  year: number
  balance: number
}

interface Column {
  label: string
  getValue: (row: unknown, key: string | number) => string
  class?: string
}

const props = defineProps<{
  title: string
  data: DataItem[]
  tableColumns: Column[]
  tableFooter?: string
  chartConfig: ChartConfig
}>()

const showChart = ref(true)
// Set y axis to be $
const tickFormat = (d: number) => `$${d.toLocaleString()}`
</script>

<template>
  <!-- Todo - card / item needed with toggle. Add v-if to Table and Chart -->
  <!-- Table -->
  <Card>
    <div class="flex space-x-2 p-4 gap-2">
      <Switch id="table-toggle" v-model="showChart" />
      <Label for="table-toggle">{{ showChart ? 'Show table' : 'Show chart' }}</Label>
    </div>
    <Table v-if="!showChart">
      <TableCaption>{{ title }}</TableCaption>
      <TableHeader>
        <TableRow>
          <TableHead v-for="(col, i) in tableColumns" :key="i" :class="col.class">{{
            col.label
          }}</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="(row, key) in data" :key="key">
          <TableCell v-for="(col, i) in tableColumns" :key="i" :class="col.class">
            {{ col.getValue(row, key) }}
          </TableCell>
        </TableRow>
      </TableBody>
      <TableFooter v-if="tableFooter">
        <TableRow>
          <TableCell :colspan="tableColumns.length">{{ tableFooter }}</TableCell>
        </TableRow>
      </TableFooter>
    </Table>

    <!-- Chart version -->
    <ChartContainer v-if="showChart" :config="chartConfig" class="min-h-[200px] w-full">
      <h2 class="font-bold text-center">{{ title }}</h2>
      <VisXYContainer :data="data">
        <VisAxis
          type="x"
          :tickTextHideOverlapping="true"
          :x="(d: DataItem) => d.year"
          :tick-line="false"
          :domain-line="false"
          :grid-line="false"
          :tick-values="data.map((d) => d.year)"
        />
        <VisAxis
          type="y"
          :y="(d: DataItem) => d.balance"
          :tickFormat="tickFormat"
          :tick-line="true"
          :domain-line="false"
        />
        <VisGroupedBar
          :x="(d: DataItem) => d.year"
          :y="[(d: DataItem) => d.balance]"
          :color="['var(--color-balance)']"
          :rounded-corners="4"
          :barPadding="0.1"
          :groupPadding="0.1"
        />
        <ChartTooltip />
        <ChartCrosshair
          :template="componentToString(props.chartConfig, ChartTooltipContent)"
          :color="['var(--color-balance)']"
        />
      </VisXYContainer>
    </ChartContainer>
  </Card>
</template>
