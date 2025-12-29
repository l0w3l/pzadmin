<script setup lang="ts">
import WelcomeLayout from "@/app/Layouts/WelcomeLayout.vue";
import {useZomboidStore} from "@/store/zomboid/";
import {onBeforeMount, onMounted, onUnmounted, ref} from "vue";
import {usePlayersStore} from "@/store/zomboid/players";

const server = useZomboidStore();
const players = usePlayersStore();

const loading = ref(true);

onMounted(async () => {
    await server.fetch();
    await players.fetch();

    loading.value = false;
});

</script>

<template>
    <WelcomeLayout :loading="loading">
        <div class="wrapper">
            <ul class="flex flex-col mt-20">
                <li class="flex flex-row justify-center text-4xl xl:text-6xl lg:text-5xl mb-7">
                    <p>IP</p>
                    <span class="mr-3">:</span>
                    <p>{{ server.ip }}</p>
                </li>
                <li class="flex flex-row justify-center text-4xl xl:text-6xl lg:text-5xl mb-7">
                    <p>PORT</p>
                    <span class="mr-3">:</span>
                    <p>{{ server.port }}</p>
                </li>
                <li class="flex flex-row justify-center text-4xl xl:text-6xl lg:text-5xl mb-7">
                    <p>TOTAL SURVIVORS</p>
                    <span class="mr-3">:</span>
                    <p>{{ players.getPlayersCount }}</p>
                </li>
            </ul>

            <ul class="flex flex-col mt-20">
                <li v-for="(player,index) in players.getPlayersList" class="flex flex-row justify-center text-4xl xl:text-6xl lg:text-5xl mb-7">
                    <p>{{ index + 1 }}</p>
                    <span class="mr-3">.</span>
                    <p>{{ player.username }} {{ (player?.name) ? `(${player.name})`: '' }}</p>
                </li>
            </ul>
        </div>
    </WelcomeLayout>
</template>

<style>

</style>
