import {defineStore} from "pinia";
import apiClient, {ConsoleLogDataInterface, ConsoleLogDataItemInterface} from "@/store/api/";

export const useZomboidLogsStore = defineStore('logs', {
    state: (): ZomboidLogStoreInterface => ({
        console: {
            md5: "",
            logItems: [],
            lastId: -1
        }
    }),
    getters: {
        getConsoleLogs: (state: ZomboidLogStoreInterface): ZomboidLogDecorator[] =>
            state.console.logItems.map<ZomboidLogDecorator>((log: ConsoleLogDataItemInterface) => new ZomboidLogDecorator(log)),
        isFetched: (state: ZomboidLogStoreInterface): boolean =>
            state.console.lastId > 0,
        lastId: (state: ZomboidLogStoreInterface): number =>
            state.console.lastId,
    },
    actions: {
        async fetchConsole(): Promise<void>
        {
            const console: ConsoleLogDataInterface = await apiClient.zomboid.logs.console.index();

            this.setConsole(console);
        },
        async updateConsole(newConsoleLastId: number): Promise<void>
        {
            if (newConsoleLastId > this.$state.console.lastId) {
                const console: ConsoleLogDataInterface = await apiClient.zomboid.logs.console.cursor(this.$state.console.lastId, newConsoleLastId);

                this.appendConsoleLogs(console);
            } else {
                this.$reset();

                await this.fetchConsole();
            }
        },
        appendConsoleLogs(logs: ConsoleLogDataInterface): void {
          this.$patch((state: ZomboidLogStoreInterface) => {
              state.console.md5 = logs.md5;

              state.console.logItems.push(...logs.logItems);

              state.console.lastId = logs.lastId;
          })
        },
        setConsole(logs: ConsoleLogDataInterface): void {
            this.$patch((state: ZomboidLogStoreInterface) =>
                state.console = logs
            );
        },
    }
});

export class ZomboidLogDecorator
{
    public readonly instance: ConsoleLogDataItemInterface;
    public readonly message: string;
    public readonly isWarning: boolean;
    public readonly isError: boolean;

    constructor(log: ConsoleLogDataItemInterface) {
        this.instance = log;
        this.message = log.message.trim().replace(/t:(\d{13}),/g, (_, ts) => {
            const d = new Date(Number(ts));
            return `[${d.toISOString().replace('T', ' ').replace('Z', ' UTC')}]`;
        }).replace(/(f:\d+,)/g, '');
        this.isWarning = this.instance.message.includes('WARN');
        this.isError = this.instance.message.includes('ERROR');
    }

    toString(): string {
        return this.message;
    }
}

interface ZomboidLogStoreInterface
{
    console: ConsoleLogDataInterface,
}

