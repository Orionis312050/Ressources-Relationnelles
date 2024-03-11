<?php

namespace App\Repository;

use App\Entity\Repost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Repost>
 *
 * @method Repost|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repost|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repost[]    findAll()
 * @method Repost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepostRepository extends ServiceEntityRepository
{ 
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Repost::class);
    }

    public function findRepostPostsByUser(User $user): array
    {
        // Première requête
        $repostPosts = $this->createQueryBuilder('f')
            ->join('f.post', 'p')
            ->andWhere('f.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    
        // Deuxième requête
        $followingRepostPosts = $this->createQueryBuilder('r')
            ->join('r.post', 'p')
            ->join('r.user', 'u')
            ->join('u.following', 'f')
            ->andWhere('f.follower = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    
        // Utilisez les résultats comme vous le souhaitez
        // Par exemple, vous pouvez les concaténer ou les retourner dans un tableau
        $allRepostPosts = array_merge($repostPosts, $followingRepostPosts);
    
        // Vous pouvez également retourner les résultats séparément ou les manipuler autrement
        return $allRepostPosts;
    }
    

    // ... your other methods
}
