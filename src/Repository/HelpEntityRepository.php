<?php

// HelpEntityRepository.php

namespace App\Repository;

use App\Entity\HelpEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class HelpEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HelpEntity::class);
    }

    public function findByStatus($status): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.Status = :status')
            ->setParameter('status', $status)
            ->setParameter('categorie', $categorie)
            ->orderBy('id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPaginatedQuestions($page, $pageSize)
    {
        $offset = ($page - 1) * $pageSize;

        return $this->createQueryBuilder('h')
            ->orderBy('h.id', 'DESC')
            ->setMaxResults($pageSize)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
