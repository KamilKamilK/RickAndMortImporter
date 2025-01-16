<?php

namespace Domains\Character\Events\Listeners;

use Domains\Character\Gateways\Events\CharacterCreatedContract;
use Domains\Common\Events\DomainEvent;
use Domains\Common\Events\EventListenerContract;
use Domains\Episode\Models\Episode;

class CharacterCreatedListener implements EventListenerContract
{

    public function handle(DomainEvent $event): void
    {
        if (!$event instanceof CharacterCreatedContract) {
            throw new \InvalidArgumentException('Expected CharacterCreatedContract contract');
        }

        $episodes = $event->episodes();

        /** @var Episode $episode */
        foreach ($episodes as $episode) {
            $episode->applyCharacterCreatedEvent($event);
        }
    }
}