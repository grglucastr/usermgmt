<?php

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }
    
    public function findGroupsWhereUserIsNotIn($userId)
    {   
        $subQueryBuilder = $this->_em->createQueryBuilder();
        
        $subQuery = $subQueryBuilder->select(['gr.id'])
                                    ->from('App\Entity\Group', 'gr')
                                    ->innerJoin('gr.users', 'u')
                                    ->where('u.id = :user_id')
                                    ->setParameter('user_id', $userId)
                                    ->getQuery()->getResult();
        
        
        $queryBuilder = $this->_em->createQueryBuilder();
        $query = $queryBuilder->select('g')
                              ->from('App\Entity\Group', 'g')
                              ->where($queryBuilder->expr()->notIn('g.id', ':subQuery'))
                              ->setParameter('subQuery', $subQuery)
                              ->getQuery();
        
        
        return $query->getResult();
    }

    // /**
    //  * @return Group[] Returns an array of Group objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Group
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
