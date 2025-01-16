<?php

namespace Domains\Character\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domains\Character\Events\CharacterCreated;
use Domains\Character\Gateways\Events\CharacterCreatedContract;
use Domains\Character\Gateways\Models\CharacterContract;
use Domains\Character\Validator\CharacterValidator;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Common\Models\Entity;
use Domains\Episode\Gateways\Models\EpisodeContract;
use Domains\Episode\Models\Episode;
use Domains\Location\Models\Location;


class Character extends Entity implements CharacterContract
{
    private int $apiId;
    private string $name;
    private string $status;
    private string $species;
    private ?string $type;
    private string $gender;
    private ?Location $origin;
    private ?Location $location;
    private string $image;

    /** @var Collection<EpisodeContract> */
    private Collection $episodes;
    private string $url;
    private \DateTimeImmutable $createdAt;

    private function __construct(
        int $apiId,
        string $name,
        string $status,
        string $species,
        ?string $type,
        string $gender,
        ?Location $origin,
        ?Location $location,
        string $image,
        string $url,
        \DateTimeImmutable $createdAt
    ) {
        parent::__construct();

        $this->apiId = $apiId;
        $this->name = $name;
        $this->status = $status;
        $this->species = $species;
        $this->type = $type;
        $this->gender = $gender;
        $this->origin = $origin;
        $this->location = $location;
        $this->image = $image;
        $this->episodes = new ArrayCollection();
        $this->url = $url;
        $this->createdAt = $createdAt;
    }

    /**
     * @throws DomainValidationException
     */
    public static function create(CharacterDTO $dto, ?Location $origin, ?Location $location, ArrayCollection $episodes): self
    {
        $validator = new CharacterValidator();

        $validator->validateName($dto->name());
        $validator->validateStatus($dto->status());
        $validator->validateSpecies($dto->species());
        $validator->validateType($dto->type());
        $validator->validateGender($dto->gender());

        if ($validator->exception()->hasErrors()) {
            throw $validator->exception();
        }

        $character = new self(
            $dto->apiId(),
            $dto->name(),
            $dto->status(),
            $dto->species(),
            $dto->type(),
            $dto->gender(),
            $origin,
            $location,
            $dto->image(),
            $dto->url(),
            $dto->created()
        );

        $character->addPendingEvent(new CharacterCreated($character, $episodes));

        return $character;
    }

    public function addEpisode(Episode $episode): void
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->addCharacter($this); // Synchronizacja dwukierunkowa
        }
    }

    public function apiId(): ?int
    {
        return $this->apiId;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function species(): string
    {
        return $this->species;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function origin(): ?Location
    {
        return $this->origin;
    }

    public function location(): ?Location
    {
        return $this->location;
    }

    public function image(): string
    {
        return $this->image;
    }

    public function episodes(): Collection
    {
        return $this->episodes;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
