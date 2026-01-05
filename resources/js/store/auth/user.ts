import {defineStore} from "pinia";
import api from "@/store/api";

export const useUserStore = defineStore('user', {
    state: (): UserStoreInterface => ({
        user: undefined
    }),
    actions: {
        async lazyGetUser(): Promise<UserInterface> {
            if (!this.user) {
                this.user = await api.users.auth<UserInterface>();

                return this.user;
            }

            return this.user;
        }
    },
})

interface UserStoreInterface {
    user?: UserInterface;
}

export interface UserInterface {
    username: string;
    email: string;
    created_at: string;
}
