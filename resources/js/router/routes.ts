import Welcome from "@/app/Pages/Welcome.vue";
import Login from "@/app/Pages/Auth/Login.vue";
import Dashboard from "@/app/Pages/Admin/Dashboard.vue";
import {RouteRecordRaw, RouteRecordRedirect, RouteRecordRedirectOption} from "vue-router";
import Registration from "@/app/Pages/Auth/Registration.vue";

const routes: Readonly<RouteRecordRaw[]> = [
    {
        path: '/',
        name: "welcome",
        component: Welcome
    },
    {
        path: '/login',
        name: "auth.login",
        component: Login
    },
    {
        path: '/registration/:hash',
        name: "auth.registration",
        component: Registration
    },
    {
        path: "/admin/dashboard",
        name: "admin.dashboard",
        component: Dashboard
    },
    {
        path: "/admin",
        redirect: () => ({name: "admin.dashboard"})
    },
    {
        path: "/admin/:any",
        redirect: () => ({name: "auth.login"})
    },
    {
        path: "/:any",
        redirect: ()=> ({name: "welcome"})
    }
];

export default routes;
