<?php

namespace JesseGall\Events\Tests\TestClasses;

class TestListener
{

    public function handle(TestEvent $event): void
    {
        $callback = $event->getCallback();

        if ($callback) {
            $callback();
        }
    }

}