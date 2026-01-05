import {defineStore} from "pinia";
import apiClient from "@/store/api";

export const useZomboidStore = defineStore("server", {
    state: (): ServerStateInterface => ({
        ip: '127.0.0.1',
        port: 0,
        status: '...',
    }),
    getters: {
        getStatus: (state: ServerStateInterface): ServerStatusEnum =>
            state.status,
        isActive: (state: ServerStateInterface): boolean =>
            state.status === 'active',
        isDown: (state: ServerStateInterface): boolean =>
            state.status === 'down',
    },
    actions: {
        async fetch(): Promise<void> {
            this.$state = await apiClient.zomboid.index<ServerStateInterface>();
        },

        setStatus(status: ServerStatusEnum): void
        {
            this.status = status;
        },
        async start(): Promise<void> {
            await apiClient.zomboid.start();
        },
        async down(): Promise<void> {
            await apiClient.zomboid.down();
        },
    }
})

interface ServerStateInterface
{
    ip: string;
    port: number;
    status: ServerStatusEnum;
}

type ServerStatusEnum = '...' | 'active' | 'down' | 'pending' | 'restarting' | 'paused' | 'error';
