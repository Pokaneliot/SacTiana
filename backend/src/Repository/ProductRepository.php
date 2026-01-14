<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find products with stock quantity and latest updates, with optional filtering
     *
     * @param array $filters ['ref' => string, 'sellingPrice' => float, 'createdAt' => DateTime, 'categoryId' => int]
     * @return array
     */
    public function findProductsWithStockAndUpdates(array $filters = []): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.productStocks', 'ps')
            ->leftJoin('p.productUpdates', 'pu')
            ->leftJoin('p.category', 'c')
            ->select('p', 'ps', 'pu', 'c')
            ->addOrderBy('pu.updateAt', 'DESC');

        // Apply filters
        if (!empty($filters['ref'])) {
            $qb->andWhere('p.ref LIKE :ref')
               ->setParameter('ref', '%' . $filters['ref'] . '%');
        }

        if (!empty($filters['sellingPrice'])) {
            $qb->andWhere('p.sellingPrice = :sellingPrice')
               ->setParameter('sellingPrice', $filters['sellingPrice']);
        }

        if (!empty($filters['createdAt'])) {
            $qb->andWhere('p.createdAt = :createdAt')
               ->setParameter('createdAt', $filters['createdAt']);
        }

        if (!empty($filters['categoryId'])) {
            $qb->andWhere('p.category = :categoryId')
               ->setParameter('categoryId', $filters['categoryId']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find a single product with stock and updates
     */
    public function findProductWithStockAndUpdates(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productStocks', 'ps')
            ->leftJoin('p.productUpdates', 'pu')
            ->leftJoin('p.category', 'c')
            ->select('p', 'ps', 'pu', 'c')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->addOrderBy('pu.updateAt', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
