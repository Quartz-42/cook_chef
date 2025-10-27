<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findTotalDuration(): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('SUM(r.duration) as totalDuration')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function paginateRecipes(int $page, int $limit, ?int $userId): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('r');

        if (null !== $userId) {
            $queryBuilder->andWhere('r.author = :userId')
                ->setParameter('userId', $userId);
        }

        return $this->paginator->paginate(
            $queryBuilder,
            $page,
            $limit,
            [
                'distinct' => false,
                'sortFieldAllowList' => ['r.id', 'r.title', 'r.content', 'r.duration'],
            ]
        );
    }
}
