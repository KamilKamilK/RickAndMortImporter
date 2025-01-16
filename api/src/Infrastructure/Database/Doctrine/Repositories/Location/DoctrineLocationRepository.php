<?php

namespace Infrastructure\Database\Doctrine\Repositories\Location;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domains\Location\Models\Location;
use Domains\Location\Repositories\LocationRepositoryContract;

class DoctrineLocationRepository extends EntityRepository implements LocationRepositoryContract
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Location::class));
    }

    public function store(Location $model): self
    {
        $this->entityManager->persist($model);
        return $this;
    }

    public function flush(): self
    {
        $this->entityManager->flush();
        return $this;
    }
}
