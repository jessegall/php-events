<?php

namespace JesseGall\Events\Tests\TestClasses;

use Closure;
use JesseGall\Events\Event;

class TestEvent implements Event
{

    private ?Closure $callback;

    public function __construct(Closure $callback = null)
    {
        $this->callback = $callback;
    }

    public function getCallback(): ?Closure
    {
        return $this->callback;
    }

}