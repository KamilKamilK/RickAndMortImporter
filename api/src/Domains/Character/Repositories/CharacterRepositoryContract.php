<?php

namespace Domains\Character\Repositories;

use Domains\Character\Models\Character;
use Domains\Common\Paginator\Paginator;

interface CharacterRepositoryContract
{
    public function list(int $page, int $perPageLimit): Paginator;
    public function store(Character $model);
    public function flush();
}