<script setup lang="ts">

import AdminLayout from "@/app/Layouts/AdminLayout.vue";
import ServerStatus from "@/app/Components/Server/ServerStatus.vue";
import ServerSwitch from "@/app/Components/Server/ServerSwitch.vue";
import LogsViewer from "@/app/Components/Server/LogsViewer.vue";

import {
    Document,
    Menu as IconMenu,
    Location,
    Setting,
} from '@element-plus/icons-vue'
import {reactive} from "vue";

const menuTypes = ["console", "players", "settings", "mods"] as const;
type MenuTypesEnum = typeof menuTypes[number];
const isMenuType = (value: string): value is MenuTypesEnum => {
    return menuTypes.includes(value as MenuTypesEnum);
}

interface DashboardDataInterface {
    selectedMenu: MenuTypesEnum;
}

const data = reactive<DashboardDataInterface>({selectedMenu: 'console'});

const onMenuItemSelect = (key: string) => {
    if (isMenuType(key)) {

        if (key === "settings") {
            return;
        }

        data.selectedMenu = key;
    }


}
</script>

<template>
    <AdminLayout>
        <div class="flex flex-col mt-12">
            <div class="flex flex-row px-5">
                <div>
                    <el-menu
                        :default-active="data.selectedMenu"
                        @select="onMenuItemSelect"
                    >
                        <el-menu-item index="console">CONSOLE</el-menu-item>
                        <el-menu-item index="players">PLAYERS</el-menu-item>
                        <el-sub-menu index="settings">
                            <template #title>
                                SETTINGS
                            </template>
                            <el-menu-item-group title="GAME">
                                <el-menu-item index="mods">MODS</el-menu-item>
                            </el-menu-item-group>
                        </el-sub-menu>
                    </el-menu>
                </div>

                <div class="flex justify-center items-center w-full">
                    <div v-if="data.selectedMenu === 'console'">
                        <div class="flex flex-col justify-center">
                            <div>
                                <LogsViewer />
                            </div>
                            <div class="flex justify-center mt-14">
                                <ServerStatus />
                            </div>
                            <div class="flex justify-center mt-14">
                                <ServerSwitch />
                            </div>
                        </div>
                    </div>
                    <div v-else>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>

<style scoped>

</style>
