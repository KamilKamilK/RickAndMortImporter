<?php

namespace App\Tests\Unit\Character;

use Domains\Character\Models\Character;

class TestableCharacter extends Character
{
    public function publicAddPendingEvent($event): void
    {
        $this->addPendingEvent($event);
    }
}