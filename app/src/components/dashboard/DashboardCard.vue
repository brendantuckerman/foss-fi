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
  description?: string
}>()
</script>

<template>
  <Card class="w-full max-w-sm">
    <CardHeader>
      <CardDescription> Years until FIRE</CardDescription>
      <CardTitle class="text-3xl font-extrabold">{{ title }}</CardTitle>
    </CardHeader>
    <CardContent>
      <slot />
    </CardContent>
    <CardFooter class="flex flex-col gap-2"> </CardFooter>
  </Card>
</template>
