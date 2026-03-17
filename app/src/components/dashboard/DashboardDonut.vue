<script setup lang="ts">
import { VisSingleContainer, VisDonut } from '@unovis/vue'
import Card from '../ui/card/Card.vue'

// Example

//

// We might need to take the full amount and the create % from the
// two smaller values?

defineProps<{
  title: string
  data: number[]
}>()

const value = (d: number) => d
</script>

<template>
  <Card>
    <VisSingleContainer :data="data">
      <VisDonut
        :value="value"
        :centralLabel="`$${title}`"
        centralSubLabel="Total amount needed in super so it will grow to your FI amount."
        :angleRange="[-1.5707963267948966, 1.5707963267948966]"
      />
    </VisSingleContainer>
  </Card>
</template>
