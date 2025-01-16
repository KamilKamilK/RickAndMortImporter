<?php

namespace App\Http\Responses;

use App\Http\Mappers\ApiCharacter;
use Domains\Character\Models\Character;
use Domains\Common\Paginator\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CharacterListResponse extends JsonResponse
{
    public function __construct(Paginator $paginator)
    {
        $data = [
            'data' => array_map(function (Character $character) {
                $apiCharacter = new ApiCharacter($character);
                return $apiCharacter->toArray();
            }, $paginator->items()),

            'perPageLimit' => $paginator->perPageLimit(),
            'page' => $paginator->page(),
            'lastPage' => $paginator->lastPage(),
            'total' => $paginator->total(),
        ];

        parent::__construct($data, Response::HTTP_OK);
    }
}