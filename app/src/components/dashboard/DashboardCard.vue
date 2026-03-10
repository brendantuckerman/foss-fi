<script setup lang="ts">
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/components/ui/card'

defineProps<{
  title: string
  headerDescription?: string
  footerDescription?: string
  cardFooter?: string
}>()
</script>

<template>
  <Card class="w-full max-w-sm foss-fi__dashboard-card">
    <CardHeader>
      <CardDescription>{{ headerDescription }}</CardDescription>
      <CardTitle class="foss-fi__dashboard-card-title text-3xl font-extrabold">{{
        title
      }}</CardTitle>
    </CardHeader>
    <CardContent>
      <slot />
    </CardContent>
    <CardFooter v-if="cardFooter">
      <CardDescription> {{ footerDescription }} </CardDescription>
    </CardFooter>
  </Card>
</template>

<style scoped lang="css">
.foss-fi__dashboard-card-title {
  font-weight: 700;
}
</style>
