<template>
  <div 
    class="collapsible-container"
    :class="{ 'is-open': isOpen }"
    :style="{ 
      height: isOpen ? (contentHeight + 'px') : '0px',
      overflow: 'hidden',
      transition: 'height 0.3s ease-in-out'
    }"
  >
    <div ref="content" class="collapsible-content">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import { ref, nextTick, watch, onMounted } from 'vue'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  }
})

const content = ref(null)
const contentHeight = ref(0)

const updateHeight = async () => {
  await nextTick()
  if (content.value) {
    contentHeight.value = content.value.scrollHeight
  }
}

watch(() => props.isOpen, async () => {
  await updateHeight()
})

onMounted(() => {
  updateHeight()
})
</script>

<style scoped>
.collapsible-container {
  transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.collapsible-content {
  /* Ensure content doesn't affect height calculation */
}
</style>
