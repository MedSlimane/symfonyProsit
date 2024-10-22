<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }


    public function tri(): array {

        $em = $this->getEntityManager();

        $query = $em->createQuery("Select b from App\Entity\Book b Order By b.publicationDate");

        return $query->getResult();
    }
    // DQL QUESTION 1
    public function getNumberBooksRomance() : int {
        $em = $this->getEntityManager();

        $query = $em->createQuery("Select Count(b) from App\Entity\Book b Where b.category='Romance'");

        return (int) $query->getSingleScalarResult();
    }
    // DQL QUESTION 2
    public function getBookBetweenDates() : array {
        $em = $this->getEntityManager();

        $query = $em->createQuery('Select b from App\Entity\Book b Where b.publicationDate BETWEEN :d1 AND :d2')
        ->setParameter('d1', "2014-1-1")
        ->setParameter('d2',"2018-12-13");

        return $query->getResult();

    }

    // QUERY BUILDER QUESTION 2

    public function getBookById(int $id) : array {
        return $this->createQueryBuilder('b')
        ->where('b.id =:id')
        ->setParameter('id',$id)
        ->getQuery()->getResult();
    }



    
    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
