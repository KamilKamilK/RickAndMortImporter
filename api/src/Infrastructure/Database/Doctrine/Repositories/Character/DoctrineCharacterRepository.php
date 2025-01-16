<?php

namespace Infrastructure\Database\Doctrine\Repositories\Character;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Domains\Character\Models\Character;
use Domains\Character\Repositories\CharacterRepositoryContract;
use Domains\Common\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class DoctrineCharacterRepository extends EntityRepository implements CharacterRepositoryContract
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager, $entityManager->getClassMetadata(Character::class));
    }

    public function list(?int $page = 0, ?int $perPageLimit = 20): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('c');

        $queryBuilder
            ->orderBy('c.name', 'ASC')
            ->setFirstResult(($page - 1) * $perPageLimit)
            ->setMaxResults($perPageLimit);

        $paginator = new DoctrinePaginator($queryBuilder->getQuery());
        $results = $paginator->getQuery()->getResult();

        return new Paginator($results, count($paginator), $page, $perPageLimit);
    }

    public function store(Character $model): self
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