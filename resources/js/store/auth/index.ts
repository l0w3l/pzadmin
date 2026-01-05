import {defineStore} from "pinia";
import client from "@/store/api";

export const useAuthStore = defineStore('auth', {
    state: (): AuthStateInterface => ({
        type: '',
        token: '',
        expires_at: new Date(),
        regenerate_timeout_index: null
    }),
    getters: {
        getType: (state: AuthStateInterface): string =>
            state.type,
        getToken: (state: AuthStateInterface): string =>
            state.token,
        getExpiresAt: (state: AuthStateInterface): Date =>
            new Date(state.expires_at),
        getRegenerateTimeoutIndex: (state: AuthStateInterface): number|null =>
            state.regenerate_timeout_index,
        isRegenerateStarted: (state: AuthStateInterface): boolean =>
            state.regenerate_timeout_index !== null,
        isAuthenticated: (state): boolean =>
            (state.token.length > 0) && (state.type.length > 0)
    },
    actions: {
        async ping(): Promise<void> {
            await client.auth.tokens.ping()

            this._resetRegenerateTimeoutIndex();
            this._startRegenerateProcess();
        },
        async regenerate(): Promise<void> {
            const authState: AuthStateInterface = await client.auth.tokens.regenerate<AuthStateInterface>();

            this._setAuthState(authState);
        },
        async verifyHash(hash: string): Promise<void> {
            await client.auth.verify.hash(hash);
        },
        async verifyUsername(username: string): Promise<void> {
            await client.auth.verify.username(username);
        },
        async verifyEmail(email: string): Promise<void> {
            await client.auth.verify.email(email);
        },
        async login(loginForm: LoginFormInterface): Promise<void> {
            const authState: AuthStateInterface = await client.auth.login<AuthStateInterface>(
                loginForm.username.toLowerCase(), loginForm.password, loginForm.remember_me ?? false
            )

            this._setAuthState(authState);
        },
        async registration(registrationForm: RegistrationFormInterface): Promise<void> {
            await client.auth.registration(
                registrationForm.username.toLowerCase(),
                registrationForm.email.toLowerCase(),
                registrationForm.password,
                registrationForm.password_confirmation,
                registrationForm.hash
            )
        },
        async logout(): Promise<void> {
            await client.auth.logout();

            this.$reset();
        },
        _startRegenerateProcess(): void {
            const timeShift: number = 1000 * 60 * 10;
            const expiresTime: number = this.getExpiresAt.valueOf() - Date.now() - timeShift;

            if (expiresTime > 0 && this.isRegenerateStarted) {
                const timeoutIndex: number = setTimeout(async (): Promise<void> => {
                    await this.regenerate();

                    this._resetRegenerateTimeoutIndex();
                    this._startRegenerateProcess();
                }, expiresTime);

                this._setRegenerateTimeoutIndex(timeoutIndex);
            }
        },

        _setAuthState(authState: AuthStateInterface) {
            this._setType(authState.type);
            this._setToken(authState.token);
            this._setExpiresAt(authState.expires_at);
        },
        _setToken(token: string): void {
            this.$state.token = token;
        },
        _setType(type: string): void {
            this.$state.type = type;
        },
        _setExpiresAt(date: Date): void {
            this.$state.expires_at = new Date(date);
        },
        _setRegenerateTimeoutIndex(index: number): void {
            this.$state.regenerate_timeout_index = index;
        },
        _resetRegenerateTimeoutIndex(): void {
            this.$state.regenerate_timeout_index = null;
        }
    },
    persist: {
        storage: sessionStorage,
    },
});


interface AuthStateInterface
{
    type: string;
    token: string;
    expires_at: Date;
    regenerate_timeout_index: number|null;
}

export interface LoginFormInterface {
    username: string;
    password: string;
    remember_me: boolean;
}

export interface RegistrationFormInterface extends LoginFormInterface {
    username: string;
    email: string;
    password: string;
    password_confirmation: string;
    hash: string;
}
