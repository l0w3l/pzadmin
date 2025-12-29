import {defineStore} from "pinia";
import {LinkInterface, Pagination, PaginationInterface} from "@/classes/Pagination";
import client from "@/store/api";

export const usePlayersStore = defineStore('players', {
    state: (): PlayersStateInterface => Object.assign({}, new Pagination<PlayerInterface>()),
    getters: {
        getPlayersCount: (state: PlayersStateInterface): number =>
            state.data.length,
        getPlayersList: (state: PlayersStateInterface): PlayerInterface[] =>
            state.data,
    },
    actions: {
        async fetch(): Promise<void> {
            const playerState: PlayersStateInterface = await client.zomboid.players.index<PlayersStateInterface>();

            this.$patch(playerState);
        }
    }
});

interface PlayersStateInterface extends PaginationInterface<PlayerInterface>{

}

interface PlayerInterface
{
    name: string;
    username: string;
    is_dead: boolean;
    steam_id: string|null;
}
