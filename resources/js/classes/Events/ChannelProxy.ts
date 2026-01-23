import {EventInterface} from "@/classes/Events/Event";
import {Channel} from "laravel-echo";
import {useEcho, useEchoPublic} from "@laravel/echo-vue";

export interface ChannelProxyInterface<T>
{
    readonly channelName: string;

    addEvent(event: EventInterface<T>): this;

    destroy(): this;
}

export class ChannelProxy<T> implements ChannelProxyInterface<T>
{
    readonly channelName: string;
    eventsCollection: Array<EventInterface<T>>;

    private readonly channel: Channel;

    constructor(channelName: string) {
        this.channelName = channelName;
        this.channel = useEchoPublic(channelName).channel();
        this.eventsCollection = [];
    }

    addEvent(event: EventInterface<T>): this {
        this.channel.listen(event.eventName, event.callable);

        this.eventsCollection.push(event);

        return this;
    }

    destroy(): this {
        this.eventsCollection.map((event: EventInterface<T>) => this.channel.stopListening(event.eventName));

        this.eventsCollection = [];

        return this;
    }

    destroyAll(): this {
        return this;
    }

    removeEvent(eventName: string): this {
        return this;
    }

}
