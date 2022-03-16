<?php

namespace App\Repository;

use App\Entity\ProductionTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method ProductionTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionTime[]    findAll()
 * @method ProductionTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionTime::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ProductionTime $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ProductionTime $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function totalTime()
    {
        $qb = $this->createQueryBuilder('t')
            ->select('SUM(t.time)');

        return $qb->getQuery()->getSingleScalarResult();
    }


    /* SQL MAX : SELECT MAX(z.SumCoast) 
                FROM ( SELECT SUM(daily_cost*time) as SumCoast 
                FROM employee, production_time WHERE employee.id = production_time.employee_id GROUP BY production_time.employee_id) z  
    
    */
    /* SQL :  SELECT *, SUM(daily_cost*time) FROM `employee`, `production_time` WHERE employee.id = production_time.employee_id GROUP BY production_time.employee_id; */
    public function findBestEmployee()
    {

        $qb  = $this->createQueryBuilder('t');
        $qb2 = $qb;

        $qb2 = $this->createQueryBuilder('t')
            ->addSelect('SUM(e.dailyCost*t.time) AS sumCoast')
            ->addSelect('e.lastname')
            ->addSelect('e.firstname')
            ->addSelect('e.hireDate')
            ->leftJoin('t.employee', 'e')
            ->groupBy('t.employee');

        /*
        $qb = $this->createQueryBuilder('t')  
            ->addSelect($qb->expr()->max('t.id', $qb2->getDQL()))
             ;*/

        return $qb2->getQuery()->getResult();
    }


    /*
    SELECT *, SUM(time*employee.daily_cost) FROM `project`, `production_time`, `employee` WHERE delivered_at <= CURRENT_TIMESTAMP AND production_time.project_id = project.id AND production_time.employee_id = employee.id GROUP BY production_time.project_id;
    */ 

    public function findRentability()
    { 
        $qb  = $this->createQueryBuilder('t');

        $qb = $this->createQueryBuilder('t')
            ->addSelect('SUM(e.dailyCost*t.time) AS sumCoastProject')
            ->addSelect('p.id') 
            ->addSelect('p.price')  
            ->leftJoin('t.employee', 'e')
            ->leftJoin('t.project', 'p')
            ->groupBy('t.project');
 
        return $qb->getQuery()->getResult();
    }

 

    /**
     * @return ProductionTime[]
     */
    public function findLastCreatedProductionTime(): array
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.entryAt', 'DESC')
            ->setMaxResults(6);

        return $qb->getQuery()->getResult();
    }



    // /**
    //  * @return ProductionTime[] Returns an array of ProductionTime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionTime
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
