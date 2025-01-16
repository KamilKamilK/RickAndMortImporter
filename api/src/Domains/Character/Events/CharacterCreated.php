<?php

namespace Domains\Character\Events;

use Doctrine\Common\Collections\Collection;
use Domains\Character\Gateways\Events\CharacterCreatedContract;
use Domains\Character\Models\Character;
use Domains\Common\Events\DomainEvent;

class CharacterCreated extends DomainEvent implements CharacterCreatedContract
{
    private Collection $episodes;
    private Character $character;


    public function __construct(Character $character, Collection $episodes)
    {
        $this->episodes = $episodes;
        $this->character = $character;
    }

    public function episodes(): Collection
    {
        return $this->episodes;
    }

    public function character(): Character
    {
        return $this->character;
    }
}