<script setup lang="ts">

interface FormCheckboxPropsInterface {
    name: string;
    title: string;
}

const props = defineProps<FormCheckboxPropsInterface>();

const model = defineModel<any>();

</script>

<template>
    <el-form-item :label="props.title.toUpperCase()" :title="props.title" :prop="name">
        <el-checkbox v-model="model" :name="props.name">
            <slot />
        </el-checkbox>
    </el-form-item>
</template>

<style scoped>

</style>
