<?php

namespace App\Repository;

use App\Entity\ProductUpdate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductUpdate>
 */
class ProductUpdateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductUpdate::class);
    }

    /**
     * Get the latest update for a product
     */
    public function findLatestByProduct(int $productId): ?ProductUpdate
    {
        return $this->createQueryBuilder('pu')
            ->where('pu.product = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('pu.updateAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
