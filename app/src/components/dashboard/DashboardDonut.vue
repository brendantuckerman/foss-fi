<script setup lang="ts">
import { VisSingleContainer, VisNestedDonut } from '@unovis/vue'
import Card from '../ui/card/Card.vue'

// Example

//

// We might need to take the full amount and the create % from the
// two smaller values?
type Datum = [{ label: string; value: number }]

defineProps<{
  title: string
  data: Datum[]
}>()

const layers = [(d: { label: string; value: number }) => d.label]
</script>

<template>
  <Card>
    <VisSingleContainer :data="data">
      <VisNestedDonut
        :layers="layers"
        :value="(d) => d.value"
        :centralLabel="`$${title}`"
        centralSubLabel="Total amount needed in super so it will grow to your FI amount."
        :angleRange="[-1.5707963267948966, 1.5707963267948966]"
      />
    </VisSingleContainer>
  </Card>
</template>
