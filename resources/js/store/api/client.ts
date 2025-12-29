import axios, {AxiosResponse} from "axios";

const apiClient = axios.create({
    baseURL: '/api/v1',
    validateStatus: (status) => status >= 200 && status < 400,
});

apiClient.interceptors.request.use((config: any) => {
    return {
        ...config,
        headers: {Accept: 'application/json' }
    }
})

export default apiClient;
