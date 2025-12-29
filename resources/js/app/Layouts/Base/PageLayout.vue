<script setup lang="ts">

import Image from "@/app/Components/Image.vue";
</script>

<template>
    <div class="wrapper p-6 lg:pt-8 transition-colors">
        <header class="flex flex-col lg:flex-row lg:justify-between">
            <slot name="header">
                <div class="flex flex-row justify-center items-end">
                    <slot name="header_logo_image">
                        <Image relative-path="zomboid-logo.png" width="150px" height="100px" alt="zomboid-logo-black"></Image>
                    </slot>
                    <p class="text-5xl mb-4 ml-1">
                        <slot name="header_logo_postfix" />
                    </p>
                </div>
                <div class="flex flex-row justify-center items-center mt-8">
                    <slot name="header_right_area" />
                </div>
            </slot>
        </header>
        <main>
            <slot />
        </main>
        <footer>
            <slot name="footer" />
        </footer>
    </div>
</template>

<style scoped>

</style>
