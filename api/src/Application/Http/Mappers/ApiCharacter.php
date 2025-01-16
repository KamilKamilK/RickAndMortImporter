<?php

namespace App\Http\Mappers;

use Domains\Character\Models\Character;

class ApiCharacter
{
    private Character $character;

    public function __construct(Character $character)
    {
        $this->character = $character;
    }

    public function toArray(): array
    {
//        dd($this->character);
        return [
            'name' => $this->character->name(),
            'status' => $this->character->status(),
            'localization' => $this->character->location()?->name() ?? null,
            'episode' => $this->character->episodes()->last()?->name() ?? null,
            'species' => $this->character->species(),
            'origin' => $this->character->origin()?->name() ?? null,
        ];
    }
}