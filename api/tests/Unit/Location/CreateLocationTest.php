<?php

namespace App\Tests\Unit\Location;

use Domains\Common\Exceptions\DomainValidationException;
use Domains\Location\Models\Location;
use Domains\Location\Models\LocationDTO;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class CreateLocationTest extends TestCase
{
    /**
     * @throws Exception
     * @throws DomainValidationException
     */
    public function testCreateLocationWithValidData(): void
    {
        $dto = $this->createMock(LocationDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('Earth');
        $dto->method('type')->willReturn('Planet');
        $dto->method('dimension')->willReturn('C-137');
        $dto->method('url')->willReturn('https://rickandmortyapi.com/api/location/1');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        $location = Location::create($dto);

        $this->assertSame(1, $location->apiId());
        $this->assertSame('Earth', $location->name());
        $this->assertSame('Planet', $location->type());
        $this->assertSame('C-137', $location->dimension());
        $this->assertSame('https://rickandmortyapi.com/api/location/1', $location->url());
        $this->assertEquals(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'), $location->createdAt());
    }

    /**
     * @throws Exception
     */
    public function testCreateLocationWithInvalidDataThrowsException(): void
    {
        $this->expectException(DomainValidationException::class);

        $dto = $this->createMock(LocationDTO::class);
        $dto->method('apiId')->willReturn(1);
        $dto->method('name')->willReturn('');
        $dto->method('type')->willReturn(null);
        $dto->method('dimension')->willReturn(null);
        $dto->method('url')->willReturn('https://rickandmortyapi.com/invalid-url');
        $dto->method('createdAt')->willReturn(new \DateTimeImmutable('2017-11-04T22:34:53.659Z'));

        Location::create($dto);
    }
}