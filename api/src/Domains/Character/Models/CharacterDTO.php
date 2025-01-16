<?php

namespace Domains\Character\Models;

use Symfony\Component\Validator\Constraints as Assert;

class CharacterDTO
{
    /**
     * @Assert\Type("integer")
     */
    private int $id;

    /**
     * @Assert\Type("integer")
     */
    private int $apiId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=100)
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"Alive", "Dead", "unknown"})
     */
    private string $status;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=250)
     */
    private string $species;

    /**
     * @Assert\Type("string")
     */
    private ?string $type;

    /**
     * @Assert\NotBlank()
     * @Assert\Choice({"Male", "Female", "Genderless", "unknown"})
     */
    private string $gender;

    private Origin $origin;
    private ?Location $location;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private string $image;

    /**
     * @Assert\Type("array")
     */
    private array $episodes;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private string $url;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private \DateTimeImmutable $created;

    public function __construct(array $data)
    {
        $this->apiId = $data['id'];
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->species = $data['species'];
        $this->type = $data['type'] !== '' ? $data['type'] : null;
        $this->gender = $data['gender'];
        $this->origin = isset($data['origin']['name']) ? new Origin($data['origin']['name'], $data['origin']['url']) : null;
        $this->location = isset($data['location']['name']) ? new Location($data['location']['name'],$data['location']['url'] ) : null;
        $this->image = $data['image'];
        $this->episodes = $data['episode'];
        $this->url = $data['url'];
        $this->created = new \DateTimeImmutable($data['created']);
    }

    public function apiId(): int
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

    public function origin(): Origin
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

    public function episodes(): array
    {
        return $this->episodes;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function created(): \DateTimeImmutable
    {
        return $this->created;
    }
}
