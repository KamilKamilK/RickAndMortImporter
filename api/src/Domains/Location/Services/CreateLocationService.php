<?php

namespace Domains\Location\Services;

use Domains\Common\Exceptions\DomainValidationException;
use Domains\Location\Models\Location;
use Domains\Location\Models\LocationDTO;
use Domains\Location\Repositories\LocationRepositoryContract;


class CreateLocationService
{
    private LocationRepositoryContract $locationRepository;

    public function __construct(
        LocationRepositoryContract $locationRepository,
    ) {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @throws DomainValidationException
     */
    public function create(LocationDTO $DTO): Location
    {
        $location = $this->locationRepository->findOneBy(['apiId' => $DTO->apiId()]);

        if (!$location) {
            $location = Location::create($DTO);
            $this->locationRepository->store($location);
            $this->locationRepository->flush();
        }

        return $location;
    }
}