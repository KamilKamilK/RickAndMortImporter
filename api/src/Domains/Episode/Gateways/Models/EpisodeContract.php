<?php

namespace Domains\Episode\Gateways\Models;

use Doctrine\Common\Collections\Collection;

interface EpisodeContract
{
    public function characters(): Collection;
}