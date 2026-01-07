<script setup lang="ts">

import {nextTick, computed, onBeforeMount, onMounted, onUnmounted, ref} from "vue";
import {useZomboidLogsStore} from "@/store/zomboid/logs";
import {ChannelProxy} from "@/classes/Events/ChannelProxy";
import {Event} from "@/classes/Events/Event";

const globalWidth = ref<number>(window.innerWidth);
const scrollWindow = ref<HTMLDivElement>();
const channelProxy = new ChannelProxy('zomboid.logs');

const logs = useZomboidLogsStore();

const styles = computed(() => ({
    display: (globalWidth.value < 786) ? "none": "block",
}));

onBeforeMount(() => {
    window.addEventListener('resize', () => {
        globalWidth.value = window.innerWidth;
    });
});

onMounted(async () => {
    if (scrollWindow.value) {
        const scrollWindowValue = scrollWindow.value;

        if (!logs.isFetched) {
            await logs.fetchConsole();
        }

        scrollWindowValue.scrollTop = scrollWindowValue.scrollHeight;

        channelProxy.addEvent(
            new Event('.console.update', async (_handler?: unknown) => {
                const ifScrollHeightWasInTheEndOfList =
                    (scrollWindowValue.scrollTop + scrollWindowValue.clientHeight) >= scrollWindowValue.scrollHeight;

                await logs.fetchConsole();

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

const logsList = computed<string>((): string => {
    return logs.getConsoleLogs.reduce((acc, item) => acc + "\n" + item.toString(), '');
})

</script>


<template>
    <div
        class="
            bg-gray-100 border border-gray-300 rounded overflow-hidden
            h-[200px]
            lg:h-[800px]
            xl:h-[600px]
            2xl:h-[650px]
        "
        :style="styles"
    >
        <div
            ref="scrollWindow"
            contenteditable="true"
            spellcheck="false"
            @beforeinput.prevent
            @paste.prevent
            class="
        h-full
        px-3 py-2
        font-mono text-sm text-black
        outline-none cursor-text
        whitespace-pre
        overflow-x-auto overflow-y-auto
        scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200
      "
        >
            {{ logsList }}
<!--      <span-->
<!--          v-for="(log, index) in logs.getConsoleLogs"-->
<!--          :key="index"-->
<!--          class="block whitespace-pre"-->
<!--          v-html="log.toString()"-->
<!--      />-->
        </div>
    </div>
</template>

<style scoped>
/* Tailwind-style кастомизация scrollbars */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.scrollbar-thumb-gray-400::-webkit-scrollbar-thumb {
    background-color: #9ca3af; /* Tailwind gray-400 */
    border-radius: 3px;
}

.scrollbar-track-gray-200::-webkit-scrollbar-track {
    background-color: #e5e7eb; /* Tailwind gray-200 */
    border-radius: 3px;
}

/* Firefox */
.scrollbar-thin {
    scrollbar-width: thin;
    scrollbar-color: #9ca3af #e5e7eb;
}
</style>
