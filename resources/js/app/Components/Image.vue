<script setup lang="ts">
import {computed, reactive} from 'vue';

const props = defineProps({
    relativePath: { type: String, required: true },
    width: { type: String, required: true },
    height: { type: String, required: true },
    alt: { type: String, default: "image"}
})

const imagePath = computed(() => (new URL('./../../../images/' + props.relativePath, import.meta.url)).href);

</script>

<template>
    <img :src="imagePath" :alt="props.alt" :width="props.width" :height="props.height">
</template>

<style scoped>

</style>
