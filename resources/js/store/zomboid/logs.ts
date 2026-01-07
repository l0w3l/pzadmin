import {defineStore} from "pinia";
import apiClient from "@/store/api/";

export const useZomboidLogsStore = defineStore('logs', {
    state: (): ZomboidLogStoreInterface => ({
        console: {
            md5: "",
            logItems: []
        }
    }),
    getters: {
        getConsoleLogs: (state: ZomboidLogStoreInterface): ZomboidLogDecorator[] =>
            state.console.logItems.map<ZomboidLogDecorator>((log: ZomboidLogDataItemInterface) => new ZomboidLogDecorator(log)),
        isFetched: (state: ZomboidLogStoreInterface): boolean =>
            state.console.logItems.length > 0,
    },
    actions: {
        async fetchConsole(): Promise<void>
        {
            const console: ZomboidLogDataInterface = await apiClient.zomboid.logs.console<ZomboidLogDataInterface>()

            this.setConsole(console);
        },
        setConsole(logs: ZomboidLogDataInterface): void {
            this.$patch((state: ZomboidLogStoreInterface) =>
                state.console = logs
            );
        },
    }
});

class ZomboidLogDecorator
{
    private readonly log: ZomboidLogDataItemInterface;

    constructor(log: ZomboidLogDataItemInterface) {
        this.log = log;
    }

    toString(): string {
        return this.log.message;
    }
}

interface ZomboidLogStoreInterface
{
    console: ZomboidLogDataInterface,
}

interface ZomboidLogDataInterface
{
    md5: string,
    logItems: ZomboidLogDataItemInterface[];
}

export interface ZomboidLogDataItemInterface
{
    id: number,
    message: string
}
