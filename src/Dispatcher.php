<?php

namespace JesseGall\Events;

use Closure;

class Dispatcher
{

    /**
     * The registered listeners.
     *
     * @var array
     */
    private array $listeners = [];

    /**
     * Register a listener.
     *
     * @param string $event
     * @param object|string|array $listeners
     * @return void
     */
    public function listen(string $event, object|string|array $listeners): void
    {
        if (! is_array($listeners)) {
            $listeners = [$listeners];
        }

        foreach ($listeners as $listener) {
            if ($listener instanceof Closure) {
                $listener = new ClosureDelegate($listener);
            } elseif (is_string($listener)) {
                $listener = new $listener;
            }

            $this->listeners[$event][] = $listener;
        }
    }

    /**
     * Dispatch listeners
     *
     * @param Event|string $event
     * @param mixed|null $payload
     * @return void
     */
    public function dispatch(Event|string $event, mixed $payload = null): void
    {
        [$event, $payload] = $this->parseEventAndPayload($event, $payload);

        $listeners = $this->getListeners($event);

        foreach ($listeners as $listener) {
            if (! method_exists($listener, 'handle')) {
                continue;
            }

            $listener->handle($payload);
        }
    }

    /**
     * Return the listeners for a specific event
     *
     * @param string|null $event
     * @return array
     */
    public function getListeners(string $event = null): array
    {
        if (is_null($event)) {
            return $this->listeners;
        }

        return $this->listeners[$event] ?? [];
    }

    /**
     * Clear listeners
     *
     * @param string|null $event
     * @return void
     */
    public function clear(string $event = null): void
    {
        if ($event) {
            unset($this->listeners[$event]);
        } else {
            $this->listeners = [];
        }
    }

    /**
     * Parse the event and payload
     *
     * @param Event|string $event
     * @param mixed|null $payload
     * @return array
     */
    protected function parseEventAndPayload(string|Event $event, mixed $payload): array
    {
        if ($event instanceof Event) {
            return [get_class($event), $event];
        }

        return [$event, $payload];
    }

}