<?php

namespace JesseGall\Events;

use Closure;

class ClosureDelegate
{

    private Closure $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function handle(mixed $payload): void
    {
        ($this->closure)($payload);
    }

}