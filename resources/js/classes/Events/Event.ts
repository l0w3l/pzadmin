export interface EventInterface<T> {
    eventName: string;
    callable: (handler: T) => void | Promise<void>;
}

export class Event<T> implements EventInterface<T> {
    eventName: string;
    callable: (handler: T) => Promise<void>;

    constructor(
        eventName: string,
        callable: (handler: T) => Promise<void>
    ) {
        this.eventName = eventName;
        this.callable = callable;
    }
}
