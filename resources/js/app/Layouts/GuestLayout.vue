<script setup lang="ts">

import Loader from "@/app/Components/Loader.vue";
import PageLayout from "@/app/Layouts/Base/PageLayout.vue";
import {computed, defineProps} from "vue";
import {onBeforeMount, reactive} from "vue";
import {useAuthStore} from "@/store/auth/";
import {useRouter} from "vue-router";

interface GuestLayoutPropsInterface {
    /** @var boolean field for pages that require some global loading preparations */
    loading?: boolean;
}

interface GuestLayoutDataInterface {
    /** @var boolean flag for loadings within layout (at least authStore.ping() call */
    loading: boolean;
}

const props = withDefaults(defineProps<GuestLayoutPropsInterface>(), {
    loading: false
});

const data = reactive<GuestLayoutDataInterface>( {
    loading: true
});

const loadingStatus = computed<boolean>((): boolean => props.loading || data.loading);

const authStore = useAuthStore();
const router = useRouter();

/**
 * redirect authenticated users on dashboard page
 */
onBeforeMount(async () => {
    try {
        if (!authStore.isAuthenticated) {
            throw new Error();
        }

        await authStore.ping();

        await router.push({name: "admin.dashboard"});
    } catch (error) {
        data.loading = false;
    }
});

</script>

<template>
    <Loader :loading="loadingStatus">
        <PageLayout>
            <template v-slot:header_logo_postfix>
                <span class="text-red-900">ADMIN</span>
            </template>
            <slot />
        </PageLayout>
    </Loader>
</template>

<style scoped>

</style>
