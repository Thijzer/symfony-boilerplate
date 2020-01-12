<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getCommentsForBlog($blogId, $isApproved = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.article = :article_id')
            ->addOrderBy('c.created')
            ->setParameter('article_id', $blogId)
        ;
        $qb
            ->andWhere('c.approved = :approved')
            ->setParameter('approved', $isApproved)
        ;
        return $qb->getQuery()
            ->getResult();
    }

    public function getLatestComments($limit = 10)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->addOrderBy('c.id', 'DESC');
        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }
        return $qb->getQuery()
            ->getResult();
    }
}
