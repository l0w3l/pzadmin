<script setup lang="ts">

import {nextTick, computed, onBeforeMount, onMounted, onUnmounted, ref, StyleValue} from "vue";
import {useZomboidLogsStore, ZomboidLogInterface} from "@/store/zomboid/logs";
import {ChannelProxy} from "@/classes/Events/ChannelProxy";
import {Event} from "@/classes/Events/Event";

const globalWidth = ref<number>(window.innerWidth);
const scrollWindow = ref<HTMLDivElement>();
const channelProxy = new ChannelProxy('servers.zomboid.logs');

const logs = useZomboidLogsStore();

const styles = computed<StyleValue>(() => ({
    display: (globalWidth.value < 786) ? "none": "block",
    height: (globalWidth.value > 1023) ? "480px": "560px",
    "min-width": (globalWidth.value > 1023) ? "802px": "560px",
}));

onBeforeMount(() => {
    window.addEventListener('resize', () => {
        globalWidth.value = window.innerWidth;
    });
});

onMounted(async () => {
    if (scrollWindow.value) {
        const scrollWindowValue = scrollWindow.value;

        await logs.fetch();

        scrollWindowValue.scrollTop = scrollWindowValue.scrollHeight;

        channelProxy.addEvent(
            new Event('.record', async (_handler?: unknown) => {
                const ifScrollHeightWasInTheEndOfList =
                    (scrollWindowValue.scrollTop + scrollWindowValue.clientHeight) >= scrollWindowValue.scrollHeight;

                await logs.fetch();

                await nextTick(() => {
                    if (ifScrollHeightWasInTheEndOfList) {
                        scrollWindowValue.scrollTop = scrollWindowValue.scrollHeight;
                    }
                });
            })
        );
    }
});

onUnmounted(() => {
    channelProxy.destroy();
})

</script>

<template>
    <div ref="scrollWindow" class="bg-gray-300 overflow-x-scroll" :style="styles">
        <div class="text-black font-mono mx-3 my-5 overflow-x-visible">
            <span v-for="(log, index) in logs.getLogs" :key="index" class="text-sm font-mono block overflow-x-visible" v-html="log.toString()">
            </span>
        </div>
    </div>
</template>

<style scoped>

</style>
