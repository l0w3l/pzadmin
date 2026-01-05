<script setup lang="ts">

import Loader from "@/app/Components/Loader.vue";
import PageLayout from "@/app/Layouts/Base/PageLayout.vue";
import {computed, defineProps} from "vue";
import {onBeforeMount, reactive} from "vue";
import {useAuthStore} from "@/store/auth/";
import {useRouter} from "vue-router";
import {UserInterface, useUserStore} from "@/store/auth/user";
import UsernameMenu from "@/app/Components/Menu/UsernameMenu.vue";

interface AdminLayoutPropsInterface {
    /** @var boolean field for pages that require some global loading preparations */
    loading?: boolean;
}

interface AdminLayoutDataInterface {
    /** @var boolean flag for loadings within layout (at least authStore.ping() call */
    loading: boolean;
    user?: UserInterface;
}

const props = withDefaults(defineProps<AdminLayoutPropsInterface>(), {
    loading: false
});

const data = reactive<AdminLayoutDataInterface>( {
    loading: true,
});

const loadingStatus = computed<boolean>((): boolean => props.loading || data.loading);

const authStore = useAuthStore();
const router = useRouter();



/**
 * redirect non-authenticated users on login page
 */
onBeforeMount(async () => {
    try {
        if (!authStore.isAuthenticated) {
            throw new Error();
        }

        await authStore.ping()

        data.loading = false;
    } catch (error) {
        await router.push({name: "auth.login"});
    }
});

</script>

<template>
    <Loader :loading="loadingStatus">
        <PageLayout>
            <template v-slot:header_logo_postfix>
                <span class="text-red-900">ADMIN</span>
            </template>
            <template v-slot:header_right_area>
                    <UsernameMenu/>
            </template>
            <slot />
        </PageLayout>
    </Loader>
</template>

<style scoped>

</style>
