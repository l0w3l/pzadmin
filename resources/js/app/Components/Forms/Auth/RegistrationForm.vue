<script setup lang="ts">

import FormWrapper from "@/app/Components/Forms/FormWrapper.vue";
import {RegistrationFormInterface, useAuthStore} from "@/store/auth/";
import {computed, onBeforeMount, reactive, ref} from "vue";
import {ElMessageBox, FormInstance, FormRules} from "element-plus";
import {debounce} from "ts-debounce";
import FormInput from "@/app/Components/Forms/Elements/Items/FormInput.vue";
import BlackBloodyForm from "@/app/Components/Forms/Elements/BlackBloodyForm.vue";
import FormButton from "@/app/Components/Forms/Elements/Items/FormButton.vue";


interface RegistrationFormPropsInterface {
    /**
     * unique hash invite identifier
     */
    hash: string;
}
const props = defineProps<RegistrationFormPropsInterface>()

interface RegistrationFormDataInterface {
    form: RegistrationFormInterface;
}
const data = reactive<RegistrationFormDataInterface>({
    form: {
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        hash: '',
        remember_me: false,
    },
});

const emit = defineEmits<{
    (e: 'hashBad'): void,
    (e: 'hashOk'): void,
    (e: 'onSuccessSubmit'): void
}>();

const authStore = useAuthStore();
const formRef = ref<FormInstance>();

/**
 * #######################################
 * VALIDATION
 */

/**
 * Username validation (unique check + additional validation)
 */
const usernameExistValidation = debounce(async (callback: Function, value: string) => {
    try {
        await authStore.verifyUsername(value);

        callback();
    } catch (error) {
        callback(new Error('username already exists'));
    }
}, 1000)
const validateUsername = async (_: any, value: any, callback: Function) => {
    usernameExistValidation.cancel();
    value = String(value).trim();

    if (value === '') {
        callback(new Error('who are you mfk???'));
    } else if (value.length < 4) {
        callback(new Error('too small, think more'));
    } else if (value.length > 255) {
        callback(new Error('are you an idiot?'));
    } else {
        await usernameExistValidation(callback, value);
    }
}

/**
 * email validation
 */
const emailExistsValidation = debounce(async (callback: Function, value: string) => {
    try {
        await authStore.verifyEmail(value);

        callback();
    } catch (error) {
        callback(new Error('email already exists'));
    }
}, 1000);
const validationEmail = async (_: any, value: any, callback: Function) => {
    emailExistsValidation.cancel();
    value = String(value).trim();
    const regex = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!value.match(regex)) {
        callback(new Error('incorrect email format'));
    } else if (value.length > 255) {
        callback(new Error('are you an idiot?'));
    } else {
        await emailExistsValidation(callback, value);
    }
}

/**
 * password validation
 */
const validationPassword = (_: any, value: any, callback: Function) => {
    value = String(value).trim();
    const regex = /^(?=.*[0-9])[a-zA-Z0-9!@#$%^&*]{8,16}$/;

    if (!value.match(regex)) {
        return new Error('incorrect format');
    } else if (value.length > 255) {
        callback(new Error('are you an idiot?'));
    } else {
        if (data.form.password_confirmation !== '') {
            formRef.value?.validateField('password_confirmation');
        }

        callback();
    }
}
const validationPasswordConfirmation = (_: any, value: any, callback: Function) => {
    value = String(value).trim();

    if (value !== data.form.password) {
        return new Error('passwords are different');
    } else if (value.length > 255) {
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
    email: [{
        required: true, asyncValidator: validationEmail, trigger: 'change',
    }],
    password: [{
        required: true, validator: validationPassword, trigger: 'change',
    }],
    password_confirmation: [{
        required: true, validator: validationPasswordConfirmation, trigger: 'change',
    }],
    hash: [],
});

/**
 * #######################################
 */


async function registration(): Promise<void> {
    try {
        await formRef.value?.validate(async (valid: boolean) => {
            if (valid) {
                await authStore.registration(data.form);
                await authStore.login(data.form);
            } else {
                throw new Error('bad validation');
            }
        })

        emit('onSuccessSubmit');
    } catch (error) {
        await ElMessageBox.alert('Something went wrong :(', '...');
    }
}

onBeforeMount(async () => {
    try {
        if (props.hash.length !== 8) {
            throw new Error(`Incompatible hash given (${props.hash})`);
        }

        await authStore.verifyHash(props.hash);

        data.form.hash = props.hash;
        emit('hashOk');
    } catch (error) {
        emit('hashBad');
    }
})

</script>

<template>
    <BlackBloodyForm title="REGISTRATION">
        <FormWrapper
            v-model="data.form"
            :rules="rules"
            @onEnterSubmit="registration()"
            ref="formRef">
            <FormInput v-model="data.form.username" type="text" name="username" title="username" placeholder="USERNAME" autofocus uppercase/>
            <FormInput v-model="data.form.email" type="text" name="email" title="email" placeholder="EMAIL" uppercase/>
            <FormInput v-model="data.form.password" type="password" name="password" title="password" placeholder="PASSWORD" show-password/>
            <FormInput v-model="data.form.password_confirmation" type="password" name="password_confirmation" title="confirm" placeholder="PASS CONFIRM" show-password/>

            <FormButton>KNOCK-KNOCK</FormButton>
        </FormWrapper>
    </BlackBloodyForm>
</template>

<style>
</style>
