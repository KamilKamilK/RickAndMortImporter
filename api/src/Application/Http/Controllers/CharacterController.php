<?php

namespace App\Http\Controllers;

use App\Http\Responses\CharacterListResponse;
use Domains\Character\Repositories\CharacterRepositoryContract;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CharacterController
{
    private CharacterRepositoryContract $characterRepository;
    public function __construct(
        CharacterRepositoryContract $characterRepository
    )
    {
        $this->characterRepository = $characterRepository;
    }

    #[Route('/api/characters', name: 'character_list', methods: ['GET'])]
    public function list(Request $request): CharacterListResponse
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $perPage = max(1, (int) $request->query->get('perPageLimit', 10));

        $paginator = $this->characterRepository->list($page, $perPage);
        return new CharacterListResponse($paginator);
    }
}
