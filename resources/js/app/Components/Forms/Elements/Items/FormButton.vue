<script setup lang="ts">

const emit = defineEmits<{
    (e: "click"): Promise<void>
}>();

</script>

<template>
    <el-form-item>
        <el-button @click="emit('click')" type="primary">
            <slot />
        </el-button>
    </el-form-item>
</template>

<style scoped>

</style>
