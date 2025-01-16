<?php

namespace App\Tests\Unit\Episode;

use Domains\Character\Gateways\Events\CharacterCreatedContract;
use Domains\Character\Models\Character;
use Domains\Common\Exceptions\DomainValidationException;
use Domains\Episode\Models\Episode;
use Domains\Episode\Models\EpisodeDTO;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateEpisodeTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testCreateEpisodeWithValidData(): void
    {
        $dto = $this->createMock(EpisodeDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Pilot');
        $dto->method('airDate')->willReturn('December 2, 2013');
        $dto->method('episode')->willReturn('S01E01');
        $dto->method('url')->willReturn('https://example.com/episodes/1');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $episode = Episode::create($dto);

        $this->assertSame(1, $episode->apiId());
        $this->assertSame('Pilot', $episode->name());
        $this->assertSame('December 2, 2013', $episode->airDate());
        $this->assertSame('S01E01', $episode->episode());
        $this->assertSame('https://example.com/episodes/1', $episode->url());
        $this->assertEquals(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'), $episode->createdAt());
    }

    /**
     * @throws Exception
     */
    public function testCreateEpisodeWithInvalidDataThrowsException(): void
    {
        $this->expectException(DomainValidationException::class);

        $dto = $this->createMock(EpisodeDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('');
        $dto->method('airDate')->willReturn('');
        $dto->method('episode')->willReturn('');
        $dto->method('url')->willReturn('https://rickandmortyapi.com/invalid-url');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        Episode::create($dto);
    }

    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testAddCharacterToEpisode(): void
    {
        $dto = $this->createMock(EpisodeDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Pilot');
        $dto->method('airDate')->willReturn('December 2, 2013');
        $dto->method('episode')->willReturn('S01E01');
        $dto->method('url')->willReturn('https://example.com/episodes/1');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $episode = Episode::create($dto);

        $character = $this->createMock(Character::class);
        $character->expects($this->once())->method('addEpisode')->with($this->equalTo($episode));

        $episode->addCharacter($character);

        $this->assertCount(1, $episode->characters());
        $this->assertTrue($episode->characters()->contains($character));
    }

    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testApplyCharacterCreatedEvent(): void
    {
        $dto = $this->createMock(EpisodeDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Pilot');
        $dto->method('airDate')->willReturn('December 2, 2013');
        $dto->method('episode')->willReturn('S01E01');
        $dto->method('url')->willReturn('https://example.com/episodes/1');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $episode = Episode::create($dto);

        $character = $this->createMock(Character::class);
        $character->expects($this->once())->method('addEpisode')->with($this->equalTo($episode));

        $event = $this->createMock(CharacterCreatedContract::class);
        $event->method('character')->willReturn($character);

        $episode->applyCharacterCreatedEvent($event);

        $this->assertCount(1, $episode->characters());
        $this->assertTrue($episode->characters()->contains($character));
    }
}