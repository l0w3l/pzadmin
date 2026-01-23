<script setup lang="ts">

import {useZomboidStore} from "@/store/zomboid/";
import {computed} from "vue";

const server = useZomboidStore();

const title = computed<string>((): string => {
    if (disabled.value) {
        return '';
    } else if (server.isActive) {
        return 'down';
    } else {
        return 'start';
    }
});

const disabled = computed<boolean>(() => !(server.isActive || server.isDown));

async function resolveUpAndDown(): Promise<void>
{
    const isActive = server.isActive;
    server.setStatus('...');

    if (isActive) {
        await server.down();
    } else {
        await server.start();
    }
}

</script>

<template>
    <el-button @click="resolveUpAndDown()" :disabled="disabled" color="dark" size="large" :loading="disabled" class="w-36">
        <span class="uppercase text-4xl">{{ title }}</span>
    </el-button>
</template>

<style scoped>

</style>
