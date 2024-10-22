<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    // DQL QUESTION 3
    public function minMaxnbBooks(int $min, int $max) : array 
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("Select a from App\Entity\Author a WHERE a.nb_books BETWEEN :min AND :max")
            ->setParameter(':min', $min)
            ->setParameter(':max', $max);
        return $query->getResult();
    }

    // DQL QUESTION 4
    public function deleteZeroBooks() : void {
        $em = $this->getEntityManager();

        $query = $em->createQuery("Delete from App\Entity\Author a WHERE a.nb_books=0");

        $query->execute();
    }

    // QUERY BUILDER QUESTION 1
    public function listAuthorsByEmail() : array {

        return $this->createQueryBuilder('a')
            ->orderBy('a.email')
            ->getQuery()->getResult();
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
}
