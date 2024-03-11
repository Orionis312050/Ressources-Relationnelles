<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Images;
use App\Entity\Paragraphes;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findByStatsForLatestMonth(?int $userId = null): int
{
    $dateOneMonthAgo = new \DateTime('-1 month');
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.created_at >= :date_one_month_ago')
        ->setParameter('date_one_month_ago', $dateOneMonthAgo);

    if ($userId !== null) {
        $qb->andWhere('p.user = :user_id')
            ->setParameter('user_id', $userId);
    }

    return $qb->getQuery()
        ->getSingleScalarResult();
}

public function findByStatsForLastThreeMonths(?int $userId = null): int
{
    $dateThreeMonthsAgo = new \DateTime('-3 months');
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.created_at >= :date_three_months_ago')
        ->setParameter('date_three_months_ago', $dateThreeMonthsAgo);

    if ($userId !== null) {
        $qb->andWhere('p.user = :user_id')
            ->setParameter('user_id', $userId);
    }

    return $qb->getQuery()
        ->getSingleScalarResult();
}

public function findByStatsForLastSixMonths(?int $userId = null): int
{
    $dateSixMonthsAgo = new \DateTime('-6 months');
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.created_at >= :date_six_months_ago')
        ->setParameter('date_six_months_ago', $dateSixMonthsAgo);

    if ($userId !== null) {
        $qb->andWhere('p.user = :user_id')
            ->setParameter('user_id', $userId);
    }

    return $qb->getQuery()
        ->getSingleScalarResult();
}

public function findByStatsForLatestYear(?int $userId = null): int
{
    $dateOneYearAgo = new \DateTime('-1 year');
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.created_at >= :date_one_year_ago')
        ->setParameter('date_one_year_ago', $dateOneYearAgo);

    if ($userId !== null) {
        $qb->andWhere('p.user = :user_id')
            ->setParameter('user_id', $userId);
    }

    return $qb->getQuery()
        ->getSingleScalarResult();
}

public function findByAllStats(?int $userId = null): int
{
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)');

    if ($userId !== null) {
        $qb->andWhere('p.user = :user_id')
            ->setParameter('user_id', $userId);
    }

    return $qb->getQuery()
        ->getSingleScalarResult();
}

    //Search barre
    public function findByTitle($term)
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->getQuery()
            ->getResult();
    }
    

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
