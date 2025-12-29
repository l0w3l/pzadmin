import apiClient from "@/store/api/client";

export default {
    apiClient,
    zomboid: {
        index: async <T>(): Promise<T> =>
            apiClient.get<ResponseData<T>>('/zomboid').then(({ data }) => data.data),

        players: {
            index: async <T>(): Promise<T> =>
                apiClient.get<T>('/zomboid/players').then(({ data }) => data)
        },
    },
}

interface ResponseData<T> {
    data: T;
}
