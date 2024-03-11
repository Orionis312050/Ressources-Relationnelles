<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAdminEmails(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '["ROLE_ADMIN"]')
            ->select('u.email')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    public function findByStatsForLatestMonth(): int
    {
        $dateOneMonthAgo = new \DateTime('-1 month');
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.created_at >= :date_one_month_ago')
            ->setParameter('date_one_month_ago', $dateOneMonthAgo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByStatsForLastThreeMonths(): int
    {
        $dateThreeMonthsAgo = new \DateTime('-3 months');
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.created_at >= :date_three_months_ago')
            ->setParameter('date_three_months_ago', $dateThreeMonthsAgo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByStatsForLastSixMonths(): int
    {
        $dateSixMonthsAgo = new \DateTime('-6 months');
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.created_at >= :date_six_months_ago')
            ->setParameter('date_six_months_ago', $dateSixMonthsAgo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByStatsForLatestYear(): int
    {
        $dateOneYearAgo = new \DateTime('-1 year');
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.created_at >= :date_one_year_ago')
            ->setParameter('date_one_year_ago', $dateOneYearAgo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByAllStats(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
