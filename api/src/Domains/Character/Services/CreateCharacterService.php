<?php

namespace Domains\Character\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Domains\Character\Models\Character;
use Domains\Character\Models\CharacterDTO;
use Domains\Character\Repositories\CharacterRepositoryContract;
use Domains\Common\Events\AbstractDomainService;
use Domains\Common\Events\EventDispatcher;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Episode\Service\CreateEpisodeService;
use Domains\Location\Models\Location;
use Domains\Location\Models\LocationDTO;
use Domains\Location\Services\CreateLocationService;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateCharacterService extends AbstractDomainService
{
    private CharacterRepositoryContract $characterRepository;
    private CreateLocationService $locationService;
    private CreateEpisodeService $episodeService;
    private HttpClientInterface $httpClient;

    public function __construct(
        CharacterRepositoryContract $characterRepository,
        CreateEpisodeService $episodeService,
        CreateLocationService $locationService,
        HttpClientInterface $httpClient,
        EventDispatcher $eventDispatcher,
    ) {
        parent::__construct($eventDispatcher);
        $this->httpClient = $httpClient;

        $this->characterRepository = $characterRepository;
        $this->locationService = $locationService;
        $this->episodeService = $episodeService;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function create(CharacterDTO $characterDTO): Character
    {
        $character = $this->characterRepository->findOneBy(['apiId' => $characterDTO->apiId()]);
        if (!$character) {
            $location = $this->findOrCreateLocation($characterDTO->location()->url());
            $origin = $this->findOrCreateLocation($characterDTO->origin()->url());

            $episodes = $this->episodeService->findEpisodes($characterDTO->episodes());

            $character = Character::create($characterDTO, $origin, $location, $episodes);

            $this->characterRepository->store($character);
            $this->characterRepository->flush();

            $this->dispatchEvents($character);
        }

        return $character;
    }

    private function findOrCreateLocation(string $url): ?Location
    {
        if (empty($url)) {
            return null;
        }

        $response = $this->httpClient->request('GET', $url);
        $locationDTO = new LocationDTO($response->toArray());

        return $this->locationService->create($locationDTO);
    }
}