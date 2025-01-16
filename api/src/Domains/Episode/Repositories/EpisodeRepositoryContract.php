<?php

namespace Domains\Episode\Repositories;


use Domains\Episode\Models\Episode;

interface EpisodeRepositoryContract
{
    public function store(Episode $model);
    public function flush();
}