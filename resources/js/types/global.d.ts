import { AxiosInstance } from 'axios';

declare global {
    interface Window {
        axios: AxiosInstance;
        Pusher: any;
        Echo: Echo;
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
    }
}

