<script setup lang="ts">

import {FormInstance, FormRules, FormValidateCallback, FormValidationResult} from "element-plus";
import {ref} from "vue";
import type {Arrayable} from "element-plus/es/utils";
import type {FormItemProp} from "element-plus/es/components/form/src/form-item";

const emit = defineEmits<{
    (e: "onEnterSubmit"): void,
}>();

interface BlackBloodyFormPropsInterface {
    rules?: FormRules,
}

const props = defineProps<BlackBloodyFormPropsInterface>();

const model = defineModel<Object>();

const formInstance = ref<FormInstance>();

async function validate(callback: FormValidateCallback): FormValidationResult {
    return getForm().validate(callback);
}

async function validateField(props?: Arrayable<FormItemProp> | undefined, callback?: FormValidateCallback | undefined): FormValidationResult
{
    return getForm().validateField(props, callback);
}

defineExpose({
    validate, validateField
});

function getForm(): FormInstance
{
    const form = formInstance.value;

    if (!form) {
        throw new Error();
    }

    return form;
}

</script>

<template>
    <el-form :model="model"
             :rules="props.rules"
             @keypress.enter.native="emit('onEnterSubmit')"
             size="large"
             label-width="auto"
             style="max-width: 600px"
             class="flex flex-col justify-center items-center"
             ref="formInstance">
        <slot />
    </el-form>
</template>

<style scoped>

</style>
