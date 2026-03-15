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
import { ChartContainer } from '@/components/ui/chart'

interface Column {
  label: string
  getValue: (row: unknown, key: string | number) => string
  class?: string
}

defineProps<{
  tableToggle: boolean
  title: string
  data: Record<string | number, unknown>
  tableColumns: Column[]
  tableFooter?: string
  chartConfig: ChartConfig
}>()

type Data = (typeof chartData.value)[number]
</script>

<template>
  <!-- Todo - card / item needed with toggle. Add v-if to Table and Chart -->
  <!-- Table -->
  <Card>
    <Table v-if="!tableToggle">
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
    <ChartContainer v-if="tableToggle" :config="chartConfig" class="min-h-[200px] w-full">
      <h2 class="font-bold text-center">{{ title }}</h2>
      <VisXYContainer :data="data">
        <VisAxis
          type="x"
          :x="(d: Data) => d.year"
          :tick-line="false"
          :domain-line="false"
          :grid-line="false"
          :tick-values="data.map((d) => d.year)"
        />
        <VisAxis
          type="y"
          :y="(d: Data) => d.balance"
          :tick-format="(d: number) => d.balance"
          :tick-line="true"
          :domain-line="false"
        />
        <VisGroupedBar
          :x="(d: Data) => d.year"
          :y="[(d: Data) => d.balance]"
          :color="[chartConfig.options.color]"
          :rounded-corners="4"
          :barPadding="0"
          :groupPadding="0"
          :groupWidth="20"
        />
      </VisXYContainer>
    </ChartContainer>
  </Card>
</template>
