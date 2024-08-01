<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByDateOfBirth(array $dates = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        if(\array_key_exists('start', $dates)){
            $startDate = \DateTimeImmutable::createFromFormat('d-m-Y', $dates['start']);
            if($startDate){
                $qb->andWhere('a.dateOfBirth >= :start')
                ->setParameter('start', $startDate);
            }else{
                echo "Invalid start date format: " . $dates['start'];
            }

        }
        // else {
        //     echo "Start date is not provided or empty.<br>";
        // }
        
        
        if(\array_key_exists('end', $dates)){
            $endDate = \DateTimeImmutable::createFromFormat('d-m-Y', $dates['end']);
            if($endDate){
                $qb->andWhere('a.dateOfBirth <= :end')
                ->setParameter('end', $endDate);
            }else{
                echo "Invalid start date format: " . $dates['end'];
            }

        }
        // else {
        //     echo "Start date is not provided or empty.<br>";
        // }
        

        // echo $qb->getQuery()->getDQL();
        // echo "<br>";
        // echo "Parameters: ";
        foreach ($qb->getParameters() as $param) {
            echo $param->getName() . ": " . $param->getValue()->format('Y-m-d') . "<br>";
        }

                return $qb;
    }
}
