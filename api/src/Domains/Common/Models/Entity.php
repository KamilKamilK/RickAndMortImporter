<?php

namespace Domains\Common\Models;

use Domains\Common\Events\DomainEvent;

abstract class Entity
{
    protected int $id;
    private array $pendingEvents;

    protected function __construct()
    {
        $this->pendingEvents = [];
    }

    public function id(): int
    {
        return $this->id;
    }

    final protected function addPendingEvent(DomainEvent $event): void
    {
        $this->pendingEvents[] = $event;
    }

    final public function releasePendingEvents(): array
    {
        $events = $this->pendingEvents;
        $this->pendingEvents = [];

        return $events;
    }

    final public function pendingEvents(): array
    {
        return $this->pendingEvents;
    }
}