<?php

namespace App\Repository;

use App\Entity\ProdSteps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProdSteps|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProdSteps|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProdSteps[]    findAll()
 * @method ProdSteps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdStepsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdSteps::class);
    }

    // /**
    //  * @return ProdSteps[] Returns an array of ProdSteps objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProdSteps
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
