<?php

namespace Infrastructure\Database\Doctrine\Repositories\Episode;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domains\Episode\Models\Episode;
use Domains\Episode\Repositories\EpisodeRepositoryContract;

class DoctrineEpisodeRepository extends EntityRepository implements EpisodeRepositoryContract
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Episode::class));
    }

    public function store(Episode $model): self
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
