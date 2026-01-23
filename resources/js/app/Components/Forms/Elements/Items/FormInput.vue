<script setup lang="ts">

import {computed, onMounted, ref, StyleValue} from "vue";

interface InputPropsInterface {
    title: string;
    name: string;
    placeholder: string;
    type: "text" | "textarea" | "password" | "button" | "checkbox" | "file" | "number" | "radio";
    autofocus?: boolean;
    showPassword?: boolean;
    uppercase?: boolean;
}

const props = withDefaults(defineProps<InputPropsInterface>(), {
    placeholder: '',
    title: '',
    type: 'text',
    autofocus: false,
    showPassword: false,
    uppercase: false,
})

const model = defineModel<any>();

const localInnerWidth = ref<number>(window.innerWidth);

const labelPosition = computed<""|"left"|"right"|"top">(() => localInnerWidth.value > 500 ? "left": "top");
const styles = computed<StyleValue>(() => ({
    width: localInnerWidth.value > 500 ? "200px": "",
}))

const formatter = (value: string): string => {
    if (props.uppercase) {
        value = value.toUpperCase();
    }

    return value;
};

onMounted(() => {
    window.addEventListener('resize', () => {
        localInnerWidth.value = window.innerWidth;
    })
});

</script>

<template>
    <el-form-item :label-position="labelPosition"
                  :title="props.title"
                  :label="props.title.toUpperCase()"
                  :prop="props.name">
        <el-input :formatter="formatter" v-model="model" :style="styles"
                  :name="props.name" :placeholder="props.placeholder" :type="props.type"
                  :autofocus="props.autofocus" :show-password='props.showPassword'></el-input>
    </el-form-item>
</template>

<style scoped>

</style>
