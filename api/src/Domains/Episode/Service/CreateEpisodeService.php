<?php

namespace Domains\Episode\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Episode\Models\Episode;
use Domains\Episode\Models\EpisodeDTO;
use Domains\Episode\Repositories\EpisodeRepositoryContract;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CreateEpisodeService
{
    private EpisodeRepositoryContract $episodeRepository;
    private HttpClientInterface $httpClient;

    public function __construct(
        EpisodeRepositoryContract $episodeRepository,
        HttpClientInterface $httpClient,

    ) {
        $this->episodeRepository = $episodeRepository;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws DomainValidationException
     */
    public function create(EpisodeDTO $DTO): Episode
    {
        $episode = $this->episodeRepository->findOneBy(['apiId' => $DTO->apiId()]);

        if (!$episode) {
            $episode = Episode::create($DTO);
            $this->episodeRepository->store($episode);
            $this->episodeRepository->flush();
        }

        return $episode;
    }

    public function findEpisodes(array $episodeUrlList): ArrayCollection
    {
        $episodes = new ArrayCollection();
        foreach ($episodeUrlList as $episodeUrl) {
            $response = $this->httpClient->request('GET', $episodeUrl);
            $episodeDTO = new EpisodeDTO($response->toArray());
            $episode = $this->create($episodeDTO);
            $episodes->add($episode);
        }

        return $episodes;
    }
}
