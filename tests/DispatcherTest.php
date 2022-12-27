<?php

namespace JesseGall\Events\Tests;

use JesseGall\Events\Dispatcher;
use JesseGall\Events\Tests\TestClasses\TestEvent;
use JesseGall\Events\Tests\TestClasses\TestListener;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{

    public function test__Given_Listener_class__When_listen__Then_listener_added()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, TestListener::class);

        $this->assertCount(1, $dispatcher->getListeners(TestEvent::class));
    }

    public function test__Given_Listener_closure__When_listen__Then_listener_added()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, function () {
            //
        });

        $this->assertCount(1, $dispatcher->getListeners(TestEvent::class));
    }

    public function test__Given_Listener_instance__When_listen__Then_listener_added()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, new TestListener());

        $this->assertCount(1, $dispatcher->getListeners(TestEvent::class));
    }

    public function test__Given_Listener_array__When_listen__Then_listeners_added()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, [
            TestListener::class,
            new TestListener(),
            function () {
                //
            }
        ]);

        $this->assertCount(3, $dispatcher->getListeners(TestEvent::class));
    }

    public function test__Given_Listener_class__When_dispatch__Then_listener_called()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, TestListener::class);

        $handled = false;

        $dispatcher->dispatch(new TestEvent(function () use (&$handled) {
            $handled = true;
        }));

        $this->assertTrue($handled);
    }

    public function test__Given_Listener_closure__When_dispatch__Then_listener_called()
    {
        $dispatcher = new Dispatcher();

        $handled = false;

        $dispatcher->listen(TestEvent::class, function () use (&$handled) {
            $handled = true;
        });

        $dispatcher->dispatch(new TestEvent());

        $this->assertTrue($handled);
    }

    public function test__Given_Listener_array__When_dispatch__Then_listeners_called()
    {
        $dispatcher = new Dispatcher();

        $handled = 0;

        $dispatcher->listen(TestEvent::class, [
            TestListener::class,
            new TestListener(),
            function () use (&$handled) {
                $handled += 1;
            }
        ]);

        $dispatcher->dispatch(new TestEvent(function () use (&$handled) {
            $handled += 1;
        }));

        $this->assertEquals(3, $handled);
    }

    public function test__Given_payload__When_dispatch__Then_payload_passed_to_listener()
    {
        $dispatcher = new Dispatcher();

        $actual = null;

        $dispatcher->listen('test', function ($payload) use (&$actual) {
            $actual = $payload;
        });

        $dispatcher->dispatch('test', 'expected');

        $this->assertEquals('expected', $actual);
    }

    public function test__Given_listener_without_handle_method__When_dispatch__Then_gracefully_skipped()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, new class {
            //
        });

        $handled = false;

        $dispatcher->dispatch(new TestEvent(function () use (&$handled) {
            $handled = true;
        }));

        $this->assertFalse($handled);
    }

    public function test__When_clear__Then_listeners_cleared()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, TestListener::class);

        $dispatcher->clear();

        $this->assertCount(0, $dispatcher->getListeners());
    }

    public function test__Given_event__When_clear__Then_event_listeners_cleared()
    {
        $dispatcher = new Dispatcher();

        $dispatcher->listen(TestEvent::class, TestListener::class);

        $dispatcher->clear(TestEvent::class);

        $this->assertCount(0, $dispatcher->getListeners(TestEvent::class));
    }
}