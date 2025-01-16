<?php

namespace Domains\Location\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Common\Models\Entity;
use Domains\Location\Validator\LocationValidator;

class Location extends Entity
{
    private int $apiId;
    private string $name;
    private ?string $type;

    private ?string $dimension;

    private string $url;

    private \DateTimeImmutable $createdAt;

    private Collection $residents;

    private function __construct(
        int $apiId,
        string $name,
        ?string $type,
        ?string $dimension,
        string $url,
        \DateTimeImmutable $createdAt
    ) {
        parent::__construct();

        $this->apiId = $apiId;
        $this->name = $name;
        $this->type = $type;
        $this->dimension = $dimension;
        $this->url = $url;
        $this->createdAt = $createdAt;
        $this->residents = new ArrayCollection();
    }

    /**
     * @throws DomainValidationException
     */
    public static function create(LocationDTO $dto): self
    {
        $validator = new LocationValidator();

        $validator->validateName($dto->name());
        $validator->validateType($dto->type());
        $validator->validateDimension($dto->dimension());
        $validator->validateUrl($dto->url());

        if ($validator->exception()->hasErrors()) {
            throw $validator->exception();
        }

        return new self(
            $dto->apiId(),
            $dto->name(),
            $dto->type(),
            $dto->dimension(),
            $dto->url(),
            $dto->createdAt()
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

    public function type(): ?string
    {
        return $this->type;
    }

    public function dimension(): ?string
    {
        return $this->dimension;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function residents(): Collection
    {
        return $this->residents;
    }
}