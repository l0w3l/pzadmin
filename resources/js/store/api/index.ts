import apiClient from "@/store/api/client";
import {PaginationInterface} from "@/classes/Pagination";

export default {
    apiClient,
    auth: {
        tokens: {
            ping: async (): Promise<void> =>
                apiClient.get('/auth/tokens/ping'),

            regenerate: async (): Promise<AuthStateInterface> =>
                apiClient.get('/auth/tokens/regenerate').then(({ data }) => data),
        },
        verify: {
            hash: async (hash: string): Promise<void> =>
                apiClient.get(`/auth/verify/hash/${hash}`),

            username: async (username: string): Promise<void> =>
                apiClient.get(`/auth/verify/username/${username}`),

            email: async (email: string): Promise<void> =>
                apiClient.get(`/auth/verify/email/${email}`),
        },

        index: async (): Promise<UserInterface> =>
            apiClient.get<UserInterface>('/auth').then(({ data }) => data),

        login: async (username: string, password: string, remember_me: boolean): Promise<AuthStateInterface> =>
            apiClient.post<AuthStateInterface>('/auth/login', {username, password, remember_me}).then(({ data }) => data),

        registration: async (username: string, email: string, password: string, password_confirmation: string, hash: string): Promise<void> =>
            apiClient.post('/auth/registration', {username, email, password, password_confirmation, hash}),

        logout: async (): Promise<void> =>
            apiClient.get('/auth/logout')
    },
    zomboid: {
        index: async (): Promise<ServerStateInterface> =>
            apiClient.get<ServerStateInterface>('/zomboid').then(({ data }) => data),

        start: async (): Promise<void> =>
            apiClient.get('/zomboid/start'),
        down: async (): Promise<void> =>
            apiClient.get('/zomboid/down'),
        logs: {
            console: {
                index: async (): Promise<ConsoleLogDataInterface> =>
                    apiClient.get<ConsoleLogDataInterface>('/zomboid/logs/console').then(({ data}) => data),
                cursor: async (leftSide: number, rightSide: number): Promise<ConsoleLogDataInterface> =>
                    apiClient.get<ConsoleLogDataInterface>(`/zomboid/logs/console/${leftSide}/${rightSide}`).then(({ data }) => data),
            },
        },
        players: {
            index: async (): Promise<PlayersStateInterface> =>
                apiClient.get<PlayersStateInterface>('/zomboid/players').then(({ data }) => data)
        },
    },
}



export interface AuthStateInterface
{
    type: string;
    token: string;
    expires_at: Date;
    regenerate_timeout_index: number|null;
}

export interface UserInterface {
    username: string;
    email: string;
    created_at: string;
}

export interface ServerStateInterface
{
    ip: string;
    port: number;
    status: ServerStatusEnum;
}

export type ServerStatusEnum = '...' | 'active' | 'down' | 'pending' | 'restarting' | 'paused' | 'error';

export interface ConsoleLogDataInterface
{
    md5: string,
    logItems: ConsoleLogDataItemInterface[];
    lastId: number;
}

export interface ConsoleLogDataItemInterface
{
    id: number,
    message: string
}

export interface PlayersStateInterface extends PaginationInterface<PlayerInterface>{

}

export interface PlayerInterface
{
    name: string;
    username: string;
    is_dead: boolean;
    steam_id: string|null;
}
