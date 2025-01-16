<?php

namespace Domains\Common\Events;

interface EventListenerContract
{
    public function handle(DomainEvent $event): void;
}