<?php

namespace App\Repository;

use App\Entity\Paragraphes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paragraphes>
 *
 * @method Paragraphes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paragraphes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paragraphes[]    findAll()
 * @method Paragraphes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paragraphes::class);
    }

    public function findByPostId(int $postId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.post_id = :postId')
            ->setParameter('postId', $postId)
            ->getQuery()
            ->getResult();
    }

    // Ajoutez ici vos méthodes personnalisées si nécessaire
}
