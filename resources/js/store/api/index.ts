import apiClient from "@/store/api/client";

export default {
    apiClient,
    auth: {
        tokens: {
            ping: async (): Promise<void> =>
                apiClient.get('/auth/tokens/ping'),

            regenerate: async <T>(): Promise<T> =>
                apiClient.get<T>('/auth/tokens/regenerate').then(({ data }) => data),
        },
        verify: {
            hash: async (hash: string): Promise<void> =>
                apiClient.get(`/auth/verify/hash/${hash}`),

            username: async (username: string): Promise<void> =>
                apiClient.get(`/auth/verify/username/${username}`),

            email: async (email: string): Promise<void> =>
                apiClient.get(`/auth/verify/email/${email}`),
        },

        index: async <T>(): Promise<T> =>
            apiClient.get<T>('/auth').then(({ data }) => data),

        login: async <T>(username: string, password: string, remember_me: boolean): Promise<T> =>
            apiClient.post<T>('/auth/login', {username, password, remember_me}).then(({ data }) => data),

        registration: async (username: string, email: string, password: string, password_confirmation: string, hash: string): Promise<void> =>
            apiClient.post('/auth/registration', {username, email, password, password_confirmation, hash}),

        logout: async (): Promise<void> =>
            apiClient.get('/auth/logout')
    },
    zomboid: {
        index: async <T>(): Promise<T> =>
            apiClient.get<T>('/zomboid').then(({ data }) => data),

        start: async (): Promise<void> =>
            apiClient.get('/zomboid/start'),
        down: async (): Promise<void> =>
            apiClient.get('/zomboid/down'),
        logs: {
            console: async <T>(): Promise<T> =>
                apiClient.get<T>('/zomboid/logs/console').then(({ data}) => data),
        },
        players: {
            index: async <T>(): Promise<T> =>
                apiClient.get<T>('/zomboid/players').then(({ data }) => data)
        },
    },
}

