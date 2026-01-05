import axios, {AxiosResponse} from "axios";
import {useAuthStore} from "@/store/auth/";
import router from "@/router";

const apiClient = axios.create({
    baseURL: '/api/v1',
    validateStatus: (status) => status >= 200 && status < 400,
});

apiClient.interceptors.request.use((config: any) => {
    const authStore = useAuthStore();
    const token = authStore.token;
    const type = authStore.type;

    return {
        ...config,
        headers: {...config.headers, Authorization: `${type} ${token}`, Accept: 'application/json' }
    }
})

export default apiClient;
