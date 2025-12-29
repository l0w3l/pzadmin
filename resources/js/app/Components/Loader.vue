<script setup lang="ts">

import {Loader} from "@/classes/Loader";
import {onBeforeMount, onMounted, onUnmounted, watch} from "vue";

interface LoaderProps {
    loading: boolean;
}

const props = withDefaults(defineProps<LoaderProps>(), {
    loading: false
})

const loadingInstance = new Loader({ fullscreen: true, background: "black" });
watch(() => props.loading, (newValue: boolean) => {
    loadingInstance.switch(newValue);
});

onBeforeMount(() => {
    loadingInstance.switch(props.loading);
})

onUnmounted(() => {
    loadingInstance.stop();
});


</script>

<template>
    <div v-show="!loading">
        <slot />
    </div>
</template>

<style>
</style>
