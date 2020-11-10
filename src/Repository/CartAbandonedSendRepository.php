<?php

namespace FMDD\SyliusMarketingPlugin\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use FMDD\SyliusMarketingPlugin\Entity\CartAbandonedSend;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CartAbandonedSend|null find($id, $lockMode = null, $lockVersion = null)
 * @method CartAbandonedSend|null findOneBy(array $criteria, array $orderBy = null)
 * @method CartAbandonedSend[]    findAll()
 * @method CartAbandonedSend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartAbandonedSendRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CartAbandonedSend::class);
    }

    // /**
    //  * @return CartAbandonedSend[] Returns an array of CartAbandonedSend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CartAbandonedSend
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
