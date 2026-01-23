import {defineStore} from "pinia";
import api, {UserInterface} from "@/store/api";

export const useUserStore = defineStore('user', {
    state: (): UserStoreInterface => ({
        user: undefined
    }),
    actions: {
        async lazyGetUser(): Promise<UserInterface> {
            if (!this.user) {
                this.user = await api.auth.index();

                return this.user;
            }

            return this.user;
        }
    },
})

interface UserStoreInterface {
    user?: UserInterface;
}
