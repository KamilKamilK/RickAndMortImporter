<?php

namespace Domains\Common\Events;

use Domains\Common\Models\Entity;

abstract class AbstractDomainService
{
    protected EventDispatcher $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws \Exception
     */
    protected function dispatchEvents(Entity $model): void
    {
        $pendingEvents = $model->releasePendingEvents();
        foreach ($pendingEvents as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}