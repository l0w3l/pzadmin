import Welcome from "@/app/Pages/Welcome.vue";
import {RouteRecordRaw} from "vue-router";

const routes: Readonly<RouteRecordRaw[]> = [
    {
        path: '/',
        name: "welcome",
        component: Welcome
    },
    {
        path: "/:any",
        redirect: ()=> ({name: "welcome"})
    }
];

export default routes;
