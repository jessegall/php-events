# php-events

A simple event dispatcher for PHP that allows you to easily register listeners for specific events and dispatch those
events to their listeners.

## Installation

```bash
composer require jessegall/php-events
```

## Usage

To use the event dispatcher, you will first need to create an instance of the Dispatcher class:

```php
use JesseGall\Events\Dispatcher;

$dispatcher = new Dispatcher();
```

### Registering listeners

To register a listener for an event, you can use the listen method:

```php
$dispatcher->listen('event_name', $listener);
```

The `$listener` argument can be an instance of a class that implements a `handle` method, a string representing the name
of a class that has a `handle` method, or a closure:

```php
class MyListener
{
    public function handle($payload)
    {
        // Handle the event
    }
}

// Register listener using an instance of a class
$dispatcher->listen('event_name', new MyEventListener());

// Register a listener using a class
$dispatcher->listen('event_name', MyEventListener::class);

// Register a listener as a closure
$dispatcher->listen('event_name', function ($payload) {
    // Handle the event
});
```

You can also register multiple listeners at once by passing an array of listeners:

```php
$dispatcher->listen('event_name', [$listener1, $listener2, $listener3]);
```

Alternatively, you can use an event class to register listeners. An event class is a class that implements the Event
interface. To register listeners for an event class, you can use the listen method just as you would with a string event
name:

```php
$dispatcher->listen(UserRegistered::class, $listener);
```

### Dispatching events

To dispatch an event to all of its registered listeners, you can use the dispatch method:

```php
$dispatcher->dispatch('event_name', $payload);
```

When you dispatch an event with a string event name, you can pass a single payload value as an argument to
the `dispatch` method. This payload value will be passed to the `handle` method of each listener when the event is
dispatched.

```php
$dispatcher->dispatch('event_name', ['foo' => 'bar']);

class MyListener
{
    public function handle(array $payload)
    {
        // $payload will be ['foo' => 'bar']
    }
}
```

On the other hand, if you dispatch an event with an event instance, the event object is passed as the payload to the
`handle` method of each listener when the event is dispatched:

```php
$dispatcher->dispatch(new UserRegistered($user));

class MyListener
{
    public function handle(UserRegistered $event)
    {
        // Payload will be the dispatched event instance
    }
}
```