<script setup lang="ts">

import {UserInterface, useUserStore} from "@/store/auth/user";
import {computed, onMounted, reactive} from "vue";
import {useAuthStore} from "@/store/auth/";
import {useRouter} from "vue-router";

interface UsernameDropdownDataInterface {
    user?: UserInterface;
}

const data = reactive<UsernameDropdownDataInterface>({});

const router = useRouter();
const userStore = useUserStore();
const authStore = useAuthStore();

const username = computed<string>(() => {
    if (data.user) {
        const username: string = data.user.username;

        if (username.length < 10) {
            return username
        } else {
            return `${username.slice(0, 3)}...${username.slice(-3)}`
        }
    }

    return '...';
})

onMounted(async () => {
    data.user = await userStore.lazyGetUser();
});


function logout(): void {
    authStore.logout().then(() => {
        return router.push({name: 'auth.login'})
    });
}
</script>

<template>
    <div style="box-sizing: content-box">
        <el-menu mode="horizontal" :ellipsis="false" active-text-color="none">
            <el-sub-menu index="1">
                <template #title>
                    <div class="text-5xl mr-2 text-red-900">
                        {{ username }}
                    </div>
                </template>
                <el-menu-item index="1-1" @click="logout()">
                    <div class="text-3xl text-center text-red-900 w-full">
                        LOGOUT
                    </div>
                </el-menu-item>
            </el-sub-menu>
        </el-menu>
    </div>
</template>

<style scoped>
    .el-menu {
        border-bottom: none;
    }
</style>
