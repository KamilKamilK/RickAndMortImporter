<?php

namespace Domains\Character\Models;

final class Origin
{
    public string $name;
    public string $url;
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function url(): string
    {
        return $this->url;
    }
}