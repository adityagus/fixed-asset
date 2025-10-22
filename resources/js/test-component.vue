<template>
  <div>
    <button @click="isOpen = !isOpen">Toggle</button>
    <HeightCollapsible :isOpen="isOpen">
      <div style="background: red; padding: 20px;">
        <p>Test content 1</p>
        <p>Test content 2</p>
      </div>
    </HeightCollapsible>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import HeightCollapsible from 'vue-height-collapsible/vue3'

const isOpen = ref(true)
</script>

<style>
[data-height-collapsible] {
  transition: height 280ms cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
