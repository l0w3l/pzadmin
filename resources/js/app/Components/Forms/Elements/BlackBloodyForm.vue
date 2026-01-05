<script setup lang="ts">


interface FormWrapperPropsInterface {
    title: string;
}

const props = defineProps<FormWrapperPropsInterface>();

</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 border rounded-2xl border-red-700 shadow-2xl shadow-red-600 bg-black w-5/6 sm:w-96">
        <h3 class="mb-5 text-3xl">{{ props.title }}</h3>
        <slot />
    </div>
</template>

<style >
.el-input input {
    text-align: center;
    font-size: 26px;
}

.el-form-item__error {
    text-transform: uppercase;
    font-size: 18px;
    margin-bottom: 10px;
}
.el-form-item {
    margin-bottom: 30px;
}
</style>
