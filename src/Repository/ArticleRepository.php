<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findAllArticlesQuery($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.comments', 'c')
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }
        return $qb;
    }

    public function findAllArticlesByCategoryID($categoryId)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, co')
            ->leftJoin('a.comments', 'co')
            ->leftJoin('a.categories', 'ca')
            ->addOrderBy('a.created', 'DESC')

        ;
        $qb->where($qb->expr()->in('ca.id', [$categoryId]));

        return $qb->getQuery();
    }

    public function findBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }
}
