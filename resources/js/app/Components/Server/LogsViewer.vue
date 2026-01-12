<script setup lang="ts">

import {computed, nextTick, onMounted, onUnmounted, ref} from "vue";
import {useZomboidLogsStore, ZomboidLogDecorator} from "@/store/zomboid/logs";
import {ChannelProxy} from "@/classes/Events/ChannelProxy";
import {Event} from "@/classes/Events/Event";
import {useVirtualList} from "@vueuse/core";

const channelProxy = new ChannelProxy('zomboid.logs');

const logs = useZomboidLogsStore();

onMounted(async () => {
    if (scrollWindow.value) {
        const scrollWindowValue = scrollWindow.value;

        if (!logs.isFetched) {
            await logs.fetchConsole();
            scrollTo(logs.lastId);
        }

        scrollWindowValue.scrollTop = scrollWindowValue.scrollHeight;

        channelProxy.addEvent(
            new Event('.console.update', async (handler: any) => {
                const ifScrollHeightWasInTheEndOfList =
                    (scrollWindowValue.scrollTop + scrollWindowValue.clientHeight) >= scrollWindowValue.scrollHeight;

                await logs.updateConsole(handler.lastId);

                await nextTick(() => {
                    if (ifScrollHeightWasInTheEndOfList) {
                        scrollTo(logs.lastId);
                    }
                });
            })
        );
    }
});

onUnmounted(() => {
    channelProxy.destroy();
})

const {list, containerProps, wrapperProps, scrollTo} = useVirtualList(computed<ZomboidLogDecorator[]>(() => logs.getConsoleLogs), {itemHeight: 20});
const scrollWindow = containerProps.ref;

</script>

<template>
    <div
        class="
            hidden
            lg:block
            bg-gray-100 border border-gray-300 rounded overflow-hidden
            h-[200px]
            lg:h-[800px]
            xl:h-[600px]
            2xl:h-[650px]
        "
        v-bind="containerProps"
    >

        <div
            contenteditable="true"
            spellcheck="false"
            @beforeinput.prevent
            @paste.prevent
            class="
        h-full
        px-3
        font-mono text-sm text-black
        outline-none cursor-text
        whitespace-pre
        overflow-x-auto overflow-y-auto
      "
            v-bind="wrapperProps"
        >

            <div
                v-for="(log) in list"
                :key="log.data.instance.id"
                style="height: 20px"
            >
                <p v-if="log.data.isWarning" class="bg-yellow-100 text-yellow-800 block whitespace-pre" v-html="log.data.toString()" />
                <p v-else-if="log.data.isError" class="bg-red-100 text-red-800 block whitespace-pre" v-html="log.data.toString()" />
                <p v-else class="block whitespace-pre" v-html="log.data.toString()"/>
            </div>

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
