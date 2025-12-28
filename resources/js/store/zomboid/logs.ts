import {defineStore} from "pinia";
import apiClient from "@/store/api/";

export const useZomboidLogsStore = defineStore('logs', {
    state: (): ZomboidLogStoreInterface => ({
        data: []
    }),
    getters: {
        getLogs: (state: ZomboidLogStoreInterface): ZomboidLogDecorator[] =>
            state.data.map<ZomboidLogDecorator>((log: ZomboidLogInterface) => new ZomboidLogDecorator(log)),
    },
    actions: {
        async fetch(): Promise<void>
        {
            const logs: ZomboidLogStoreInterface = await apiClient.zomboid.logs.console<ZomboidLogStoreInterface>()

            this.$patch(logs);
        },
        setData(logs: ZomboidLogInterface[]): void {
            this.$patch({
                data: logs
            });
        },
    }
});

class ZomboidLogDecorator
{
    private readonly log: ZomboidLogInterface;

    constructor(log: ZomboidLogInterface) {
        this.log = log;
    }

    getMessage(): string {
        if ((typeof this.log.message === "undefined") && (typeof this.log.scope === "undefined") && (typeof this.log.type === "string")) {
            return this.getType();
        } else {
            return this.log.message ?? '';
        }
    }

    getScope(): string {
        return this.log.scope ?? '';
    }

    getType(): string {
        return this.log.type ?? '';
    }

    toString(): string {
        return `${this.getType()},\t${this.getScope()}:\t${this.getMessage()}`;
    }
}

interface ZomboidLogStoreInterface
{
    data: ZomboidLogInterface[];
}

export interface ZomboidLogInterface
{
    message: string;
    scope: string;
    type: string;
}
