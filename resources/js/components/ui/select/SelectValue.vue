<script setup lang="ts">
import type { SelectValueProps } from "reka-ui"
import { SelectValue } from "reka-ui"
import { computed } from "vue";

const props = defineProps<SelectValueProps & {
  placeholder?: string
}>()

const formattedPlaceholder = computed(() => {
  if (!props.placeholder) return undefined
  return props.placeholder.charAt(0).toUpperCase() + props.placeholder.slice(1).toLowerCase()
})
</script>

<template>
  <SelectValue
    data-slot="select-value"
    v-bind="props"
    :placeholder="formattedPlaceholder"
  >
    <slot />
  </SelectValue>
</template>
