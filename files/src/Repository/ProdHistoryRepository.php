<?php

namespace App\Repository;

use App\Entity\ProdHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method ProdHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProdHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProdHistory[]    findAll()
 * @method ProdHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdHistory::class);
    }

    //select prod_id, stepid, sum(stepstatus=1) AS `total_ok`, sum(stepstatus=0) AS `total_nok` from prod_history INNER JOIN product ON prod_history.prod_id = product.ProdId group by prod_id,stepid;
    public function currentproduction()
    {
        return $this->createQueryBuilder('u')
            ->select('u.prodId','u.stepid','sum(u.stepstatus) AS total_ok','count(u.prodId)-sum(u.stepstatus) AS total_nok')
            ->innerJoin('App:Product', 'c', Join::WITH, 'u.prodId = c.prodid')
            ->innerJoin('App:ProdSteps', 'k', Join::WITH, 'u.stepid = k.descp')
            ->groupBy('u.prodId','u.stepid')
            ->getQuery()
            ->getResult();
    }

    //select prod_id from prod_history INNER JOIN product ON prod_history.prod_id = product.ProdId group by prod_id;
    public function onlyprod()
    {
        return $this->createQueryBuilder('u')
            ->select('u.prodId')
            ->innerJoin('App:Product', 'c', Join::WITH, 'u.prodId = c.prodid')
            ->groupBy('u.prodId')
            ->getQuery()
            ->getResult();
    }
    
    public function stepsNtotals()
    {
        return $this->createQueryBuilder('u')
            ->select('u.stepid','sum(u.stepstatus) AS total_ok','count(u.prodId)-sum(u.stepstatus) AS total_nok','k.color')
            ->innerJoin('App:Product', 'c', Join::WITH, 'u.prodId = c.prodid')
            ->innerJoin('App:ProdSteps', 'k', Join::WITH, 'u.stepid = k.descp')
            ->groupBy('u.stepid')
            ->getQuery()
            ->getResult();
    }

    public function stepsNtotalsForOne($prodId)
    {
        return $this->createQueryBuilder('u')
            ->select('u.stepid','sum(u.stepstatus) AS total_ok','count(u.prodId)-sum(u.stepstatus) AS total_nok','k.color')
            ->innerJoin('App:ProdSteps', 'k', Join::WITH, 'u.stepid = k.descp')
            ->andWhere('u.prodId = :val')
            ->setParameter('val', $prodId)
            ->groupBy('u.stepid')
            ->getQuery()
            ->getResult();
    }

    public function searchby($prodId,$prodSerial)
    {
        if($prodId!=null&&$prodSerial!=null){
            return $this->createQueryBuilder('u')
            ->andWhere('u.prodId LIKE :prodId')
            ->setParameter('prodId', '%'.$prodId.'%')
            ->andWhere('u.prodSerial LIKE :prodSerial')
            ->setParameter('prodSerial', '%'.$prodSerial.'%')
            ->getQuery()
            ->getResult();
        }elseif($prodId!=null&&$prodSerial==null){
            return $this->createQueryBuilder('u')
            ->andWhere('u.prodId LIKE :prodId')
            ->setParameter('prodId', '%'.$prodId.'%')
            ->getQuery()
            ->getResult(); 
        }elseif($prodId==null&&$prodSerial!=null){
            return $this->createQueryBuilder('u')
            ->andWhere('u.prodSerial LIKE :prodSerial')
            ->setParameter('prodSerial', '%'.$prodSerial.'%')
            ->getQuery()
            ->getResult(); 
        }
    }

    /*
    public function findOneBySomeField($value): ?ProdHistory
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
