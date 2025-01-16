<?php

namespace Domains\Episode\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domains\Character\Gateways\Events\CharacterCreatedContract;
use Domains\Character\Gateways\Models\CharacterContract;
use Domains\Character\Models\Character;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Common\Models\Entity;
use Domains\Episode\Gateways\Models\EpisodeContract;
use Domains\Episode\Validator\EpisodeValidator;

class Episode extends Entity implements EpisodeContract
{
    private int $apiId;
    private string $name;

    private string $airDate;

    private string $episode;

    private string $url;

    private \DateTimeImmutable $createdAt;

    /** @var Collection<CharacterContract> */
    private Collection $characters;

    private function __construct(
        int $apiId,
        string $name,
        string $airDate,
        string $episode,
        string $url,
        \DateTimeImmutable $createdAt
    ) {
        parent::__construct();

        $this->apiId = $apiId;
        $this->name = $name;
        $this->airDate = $airDate;
        $this->episode = $episode;
        $this->url = $url;
        $this->createdAt = $createdAt;
        $this->characters = new ArrayCollection();
    }

    /**
     * @throws DomainValidationException
     */
    public static function create(EpisodeDTO $dto): self
    {
        $validator = new EpisodeValidator();

        $validator->validateName($dto->name());
        $validator->validateAirDate($dto->airDate());
        $validator->validateEpisode($dto->episode());
        $validator->validateUrl($dto->url());

        if ($validator->exception()->hasErrors()) {
            throw $validator->exception();
        }

        return new self(
            $dto->apiId(),
            $dto->name(),
            $dto->airDate(),
            $dto->episode(),
            $dto->url(),
            $dto->createdAt()
        );
    }

    public function applyCharacterCreatedEvent(CharacterCreatedContract $characterCreatedEvent): void
    {
        $character = $characterCreatedEvent->character();
        $this->addCharacter($character);
    }

    public function addCharacter(Character $character): void
    {
        if (!$this->characters->contains($character)) {
            $this->characters->add($character);
            $character->addEpisode($this);
        }
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

    public function characters(): Collection
    {
        return $this->characters;
    }
}