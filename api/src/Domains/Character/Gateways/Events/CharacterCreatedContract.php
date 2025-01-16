<?php

namespace Domains\Character\Gateways\Events;

use Doctrine\Common\Collections\Collection;
use Domains\Character\Gateways\Models\CharacterContract;


interface CharacterCreatedContract
{
    public function episodes(): Collection;
    public function character(): CharacterContract;
}