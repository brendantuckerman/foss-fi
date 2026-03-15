<script setup lang="ts">
import { VisSingleContainer, VisDonut } from '@unovis/vue'
import Card from '../ui/card/Card.vue'

// Example
// const props = defineProps<{ data: number[] }>()
// const value = (d: number) => d

defineProps<{
  title: string
  data: Record<string | number, unknown>
}>()
</script>

<template>
  <Card>
    <VisSingleContainer :data="data">
      <VisDonut
        :value="value"
        centralLabel="Label"
        centralSubLabel="Long sub-label wraps onto the next line"
      />
    </VisSingleContainer>
  </Card>
</template>
