<?php

namespace App\Events;

use Domains\Common\Events\EventDispatcher;
use Domains\Common\Events\EventListenerContract;

class EventListenerProvider
{
    private array $listeners = [];

    public function __construct(private readonly EventDispatcher $eventDispatcher)
    {
    }

    public function registerListeners(): void
    {
        foreach ($this->listeners as $eventClass => $listenersForEvent) {
            foreach ($listenersForEvent as $listener) {
                $this->eventDispatcher->addListener($eventClass, $listener);
            }
        }
    }

    public function addListener(string $eventClass, EventListenerContract $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }
}