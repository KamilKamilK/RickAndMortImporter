<?php

namespace Domains\Location\Models;

class LocationDTO
{
    private int $apiId;
    private string $name;
    private ?string $type;
    private ?string $dimension;
    private array $residents;
    private string $url;
    private \DateTimeImmutable $createdAt;

    public function __construct(array $data) {
        $this->apiId = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->dimension = $data['dimension'] ?? null;
        $this->residents = $data['residents'] ?? [];
        $this->url = $data['url'] ?? null;
        $this->createdAt = new \DateTimeImmutable($data['createdAt'] ?? null);
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

    public function residents(): array
    {
        return $this->residents;
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
