<script setup lang="ts">

import {ChannelProxy} from "@/classes/Events/ChannelProxy";
import {useZomboidStore} from "@/store/zomboid/";
import {onMounted, onUnmounted} from "vue";
import {Event} from "@/classes/Events/Event";

const server = useZomboidStore();
const channelProxy = new ChannelProxy('zomboid');

onMounted(async () => {
    setTimeout(server.fetch, 1000);

    channelProxy.addEvent(new Event('.status', async (handler: any) => {
        server.setStatus(handler.status);
    }));
})

onUnmounted(() => {
    channelProxy.destroy();
})

</script>

<template>
    <h1 class="text-4xl lg:text-5xl uppercase">STATUS: {{ server.status }}</h1>
</template>

<style scoped>

</style>
