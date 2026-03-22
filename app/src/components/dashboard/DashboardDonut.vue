<script setup lang="ts">
import { VisSingleContainer, VisNestedDonut } from '@unovis/vue'
import { NestedDonutSegmentLabelAlignment } from '@unovis/ts'
import { Card, CardHeader, CardTitle, CardFooter } from '@/components/ui/card'

// Example

//

// We might need to take the full amount and the create % from the
// two smaller values?
type Datum = [{ label: string; value: number }]

type NestedDonutLayerSettings = {
  width: number | string // The layer's width in pixels or css string to be converted to pixels
  labelAlignment: NestedDonutSegmentLabelAlignment // Alignment of the layer's segment labels
}

// Settings
const layerSettings = {
  width: 80,
  labelAlignment: NestedDonutSegmentLabelAlignment.Straight,
} satisfies NestedDonutLayerSettings

defineProps<{
  title: string
  data: Datum[]
}>()

const layers = [(d: { label: string; value: number }) => d.label]
</script>

<template>
  <Card>
    <CardHeader>
      <CardTitle
        ><h3 class="text-lg donut-title">Super you will have saved at the end of phase one vs Super Required</h3></CardTitle
      >
    </CardHeader>
    <VisSingleContainer :data="data">
      <VisNestedDonut
        :layerSettings="layerSettings"
        :layers="layers"
        :value="(d) => d.value"
        :arc-width="25"
        :centralLabel="`$${title}`"
        :centralLabelOffsetY="50"
        :angleRange="[-1.5707963267948966, 1.5707963267948966]"
      />
    </VisSingleContainer>
    <CardFooter>
      <p class="relative top-[-100px]">
        <span class="font-bold donut-span">${{ title }}</span> is the amount needed in super so it
        will grow to your FI amount.
      </p>
    </CardFooter>
  </Card>
</template>

<style scoped lang="css">
.donut-title,
.donut-span {
  font-weight: 700;
}
</style>
