<?php

namespace Domains\Episode\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class EpisodeDTO
{
    private int $apiId;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=100)
     */
    private string $airDate;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=10)
     */
    private string $episode;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private string $url;
    /**
     * @Assert\NotNull()
     */
    private \DateTimeImmutable $createdAt;

    private Collection $characters;

    public function __construct(array $data)
    {
        $this->apiId = $data['id'];
        $this->name = $data['name'] ?? null;
        $this->airDate = $data['air_date'] ?? null;
        $this->episode = $data['episode'] ?? null;
        $this->url = $data['url'] ?? null;
        $this->createdAt = isset($data['created']) ? new \DateTimeImmutable($data['created']) : null;
        $this->characters = new ArrayCollection(
            $data['characters'] ?? []
        );
    }

    public function apiId(): int
    {
        return $this->apiId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function airDate(): string
    {
        return $this->airDate;
    }

    public function episode(): string
    {
        return $this->episode;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function characters(): ArrayCollection
    {
        return $this->characters;
    }
}