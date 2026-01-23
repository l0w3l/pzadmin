<script setup lang="ts">
import GuestLayout from "@/app/Layouts/GuestLayout.vue";
import {useRouter} from "vue-router";
import LoginForm from "@/app/Components/Forms/Auth/LoginForm.vue";
import {reactive} from "vue";

interface LoginDataInterface {
    loading: boolean;
}

const data = reactive<LoginDataInterface>({
    loading: true,
})

const router = useRouter();

</script>

<template>
    <GuestLayout>
        <div class="flex flex-col justify-center items-center mt-24">
            <LoginForm @onSuccessSubmit="router.push({name: 'admin.dashboard'})"/>
        </div>
    </GuestLayout>
</template>

<style>
</style>
