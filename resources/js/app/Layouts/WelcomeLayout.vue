<script setup lang="ts">
import Image from "@/app/Components/Image.vue";
import Loader from "@/app/Components/Loader.vue";
import {defineProps} from "vue";
import Fog from "@/app/Components/Fog.vue";
import PageLayout from "@/app/Layouts/Base/PageLayout.vue";
import ServerStatus from "@/app/Components/Server/ServerStatus.vue";

interface GuestLayoutProps {
    loading: boolean;
}

const props = withDefaults(defineProps<GuestLayoutProps>(), {
    loading: false,
});

</script>

<template>
    <Fog />
    <Loader :loading="props.loading">
        <PageLayout>
            <template v-slot:header_logo_postfix>SERVER</template>
            <template v-slot:header_right_area>
                <ServerStatus />
            </template>
            <slot />
        </PageLayout>
    </Loader>
</template>

<style scoped>
</style>
