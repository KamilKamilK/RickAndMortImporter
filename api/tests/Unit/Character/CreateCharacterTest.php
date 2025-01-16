<?php

namespace App\Tests\Unit\Character;

use Doctrine\Common\Collections\ArrayCollection;
use Domains\Character\Events\CharacterCreated;
use Domains\Character\Models\Character;
use Domains\Character\Models\CharacterDTO;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Episode\Models\Episode;
use Domains\Location\Models\Location;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateCharacterTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testCreateCharacterWithValidData(): void
    {
        $dto = $this->createMock(CharacterDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Rick Sanchez');
        $dto->method('status')->willReturn('Alive');
        $dto->method('species')->willReturn('Human');
        $dto->method('type')->willReturn('Human with ants in his eyes');
        $dto->method('gender')->willReturn('Male');
        $dto->method('image')->willReturn('https://rickandmortyapi.com/api/character/avatar/20.jpeg');
        $dto->method('url')->willReturn('https://rickandmortyapi.com/api/character/20');
        $dto->method('created')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $origin = $this->createMock(Location::class);
        $location = $this->createMock(Location::class);
        $episodes = new ArrayCollection([$this->createMock(Episode::class)]);

        $character = Character::create($dto, $origin, $location, $episodes);

        $this->assertSame(1, $character->apiId());
        $this->assertSame('Rick Sanchez', $character->name());
        $this->assertSame('Alive', $character->status());
        $this->assertSame('Human', $character->species());
        $this->assertSame('Human with ants in his eyes', $character->type());
        $this->assertSame('Male', $character->gender());
        $this->assertSame('https://rickandmortyapi.com/api/character/avatar/20.jpeg', $character->image());
        $this->assertSame('https://rickandmortyapi.com/api/character/20', $character->url());
        $this->assertEquals(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'), $character->createdAt());
        $this->assertSame($origin, $character->origin());
        $this->assertSame($location, $character->location());
    }

    /**
     * @throws Exception
     */
    public function testCreateCharacterWithInvalidDataThrowsException(): void
    {
        $this->expectException(DomainValidationException::class);

        $dto = $this->createMock(CharacterDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('');
        $dto->method('status')->willReturn('');
        $dto->method('species')->willReturn('');
        $dto->method('type')->willReturn('');
        $dto->method('gender')->willReturn('');
        $dto->method('image')->willReturn('https://rickandmortyapi.com/invalid-url');
        $dto->method('url')->willReturn('');
        $dto->method('created')->willReturn(new \DateTimeImmutable('2025-01-01'));

        $origin = $this->createMock(Location::class);
        $location = $this->createMock(Location::class);
        $episodes = new ArrayCollection();

        Character::create($dto, $origin, $location, $episodes);
    }

    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testAddEpisodeToCharacter(): void
    {
        $dto = $this->createMock(CharacterDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Rick Sanchez');
        $dto->method('status')->willReturn('Alive');
        $dto->method('species')->willReturn('Human');
        $dto->method('type')->willReturn('Human with ants in his eyes');
        $dto->method('gender')->willReturn('Male');
        $dto->method('image')->willReturn('https://rickandmortyapi.com/api/character/avatar/20.jpeg');
        $dto->method('url')->willReturn('https://rickandmortyapi.com/api/character/20');
        $dto->method('created')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $origin = $this->createMock(Location::class);
        $location = $this->createMock(Location::class);
        $episodes = new ArrayCollection([$this->createMock(Episode::class)]);

        $character = Character::create($dto, $origin, $location, $episodes);

        $episode = $this->createMock(Episode::class);
        $episode->expects($this->once())->method('addCharacter')->with($character);

        $character->addEpisode($episode);

        $this->assertCount(1, $character->episodes());
        $this->assertTrue($character->episodes()->contains($episode));
    }

    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testCharacterCreatedEventIsAdded(): void
    {
        $dto = $this->createMock(CharacterDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Rick Sanchez');
        $dto->method('status')->willReturn('Alive');
        $dto->method('species')->willReturn('Human');
        $dto->method('type')->willReturn(null);
        $dto->method('gender')->willReturn('Male');
        $dto->method('image')->willReturn('https://example.com/image.jpg');
        $dto->method('url')->willReturn('https://example.com/characters/1');
        $dto->method('created')->willReturn(new \DateTimeImmutable('2025-01-01'));

        $origin = $this->createMock(Location::class);
        $location = $this->createMock(Location::class);
        $episodes = new ArrayCollection([$this->createMock(Episode::class)]);

        $character = TestableCharacter::create($dto, $origin, $location, $episodes);

        $characterCreatedEvent = new CharacterCreated($character, $episodes);
        $character->publicAddPendingEvent($characterCreatedEvent);

        $pendingEvents = $character->pendingEvents();
        $this->assertCount(1, $pendingEvents);
        $this->assertInstanceOf(CharacterCreated::class, $pendingEvents[0]);
    }
}