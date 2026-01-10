import './bootstrap';
import '../css/app.css';
import 'element-plus/dist/index.css';
import 'element-plus/theme-chalk/dark/css-vars.css';

import { createApp } from 'vue';
import ElementPlus from 'element-plus';
import store from '@/store';
import router from "@/router";
import App from "@/App.vue";
import directives from "@/directives";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createApp(App)
    .use(router)
    .use(directives)
    .use(store)
    .use(ElementPlus)
    .mount("#app");
