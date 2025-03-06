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

    //自定义查询方法
    public function findByDateOfBirth(array $dates=[]):QueryBuilder
    {
        $qb = $this->createQueryBuilder('a');

        if(array_key_exists('start', $dates)){
            $qb->andWhere('a.dateOfBirth >= :start')
            ->setParameter('start', new \DateTimeImmutable($dates['start']));
        }
        if(array_key_exists('end', $dates)){
            $qb->andWhere('a.dateOfBirth <= :end')
            ->setParameter('end', new \DateTimeImmutable($dates['end']));
        }
            
            return $qb;
            
    }
}
