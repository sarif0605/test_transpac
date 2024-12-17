<script setup>
import { RouterLink } from "vue-router";
defineProps({
  modelValue: Boolean,
});
defineEmits(["update:modelValue"]);
</script>

<template>
  <teleport to="body">
    <div class="drawer" :class="{ 'drawer-mobile': modelValue }">
      <input :checked="modelValue" type="checkbox" class="drawer-toggle" />
      <div
        class="drawer-content flex flex-col min-h-screen transition-all duration-300"
      >
        <slot name="content" />
      </div>
      <div class="drawer-side h-full">
        <label
          for="drawer"
          class="drawer-overlay"
          @click="$emit('update:modelValue', false)"
        />
        <div
          class="p-4 overflow-y-auto w-64 bg-primary text-base-content min-h-full"
        >
          <ul class="menu">
            <li>
              <RouterLink :to="{ name: 'WorkUnit' }" class="pt-14 pb-2"
                >Work Unit</RouterLink
              >
            </li>
            <li>
              <RouterLink :to="{ name: 'Employee' }" class="pb-2"
                >Employee</RouterLink
              >
            </li>
          </ul>
        </div>
      </div>
    </div>
  </teleport>
</template>