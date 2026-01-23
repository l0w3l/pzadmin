<script setup lang="ts">

import FormWrapper from "@/app/Components/Forms/FormWrapper.vue";
import {computed, onMounted, reactive, ref} from "vue";
import {LoginFormInterface, RegistrationFormInterface, useAuthStore} from "@/store/auth/";
import {ElMessageBox, FormRules} from "element-plus";
import FormInput from "@/app/Components/Forms/Elements/Items/FormInput.vue";
import BlackBloodyForm from "@/app/Components/Forms/Elements/BlackBloodyForm.vue";
import FormButton from "@/app/Components/Forms/Elements/Items/FormButton.vue";
import FormCheckbox from "@/app/Components/Forms/Elements/Items/FormCheckbox.vue";

interface LoginDataInterface {
    form: LoginFormInterface;
}

const data = reactive<LoginDataInterface>({
    form: {
        username: '',
        password: '',
        remember_me: false,
    },
})
const emit = defineEmits<{
    (e: 'onSuccessSubmit'): void
}>();

const auth = useAuthStore();

const validateUsername = async (rule: any, value: any, callback: Function) => {
    value = String(value).trim();

    if (value === '') {
        callback(new Error('who are you mfk???'));
    } else if (value.length > 255) {
        callback(new Error('are you an idiot?'));
    } else {
        callback();
    }
}


/**
 * password validation
 */
const validationPassword = (rule: any, value: any, callback: Function) => {
    value = String(value).trim();

    if (value.length > 255) {
        callback(new Error('are you an idiot?'));
    } else {
        callback();
    }
}

/**
 * Main form rules
 */
const rules = reactive<FormRules<RegistrationFormInterface>>({
    username: [{
        required: true, asyncValidator: validateUsername, trigger: 'change',
    }],
    password: [{
        required: true, validator: validationPassword, trigger: 'blur',
    }],
});



async function authenticate() {
    try {
        await auth.login(data.form)

        emit('onSuccessSubmit');
    } catch (error) {
        await ElMessageBox.alert('Login or password are wrong! Are you zombie or not?', 'Nah???');
    }
}



</script>

<template>
    <BlackBloodyForm title="LOGIN">
        <FormWrapper v-model="data.form"
                     :rules="rules"
                     @onEnterSubmit="authenticate()">
            <FormInput v-model="data.form.username" type="text" name="username" title="username" placeholder="USERNAME" autofocus uppercase/>
            <FormInput v-model="data.form.password" type="password" name="password" title="password" placeholder="PASSWORD" show-password/>

            <FormCheckbox v-model="data.form.remember_me" title="remember me" name="remember_me" />

            <FormButton @click="authenticate()">KNOCK-KNOCK</FormButton>
        </FormWrapper>
    </BlackBloodyForm>
</template>

<style scoped>

</style>
