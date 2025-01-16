<?php

namespace Domains\Location\Repositories;

use Domains\Location\Models\Location;

interface LocationRepositoryContract
{
    public function store(Location $model);
    public function flush();
}