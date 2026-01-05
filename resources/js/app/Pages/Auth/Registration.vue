<script setup lang="ts">

import {RegistrationFormInterface, useAuthStore} from "@/store/auth";
import {onBeforeMount, onMounted, reactive, ref, watch} from "vue";
import GuestLayout from "@/app/Layouts/GuestLayout.vue";
import {RouteLocationNormalizedLoaded, RouteMap, useRoute, useRouter} from "vue-router";
import {ElMessageBox, FormInstance, FormRules} from "element-plus";
import {debounce} from "ts-debounce";
import RegistrationForm from "@/app/Components/Forms/Auth/RegistrationForm.vue";

interface RegistrationDataInterface {
    loading: boolean;
}

const data = reactive<RegistrationDataInterface>({
    loading: true,
});

const router = useRouter();

</script>

<template>
    <GuestLayout :loading="data.loading">
        <div class="flex flex-col justify-center items-center mt-24">
            <RegistrationForm
                :hash="useRoute().params['hash'].toString()"
                @hashBad="router.push({name: 'auth.login'})"
                @hashOk="data.loading = false"
                @onSuccessSubmit="router.push({name: 'admin.dashboard'})"/>
        </div>
    </GuestLayout>
</template>

<style>
</style>
