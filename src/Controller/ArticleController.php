<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Entity\Comment;
use App\Form\CommentType;

class ArticleController extends AbstractController
{

    public function index($page = 1)
    {
        $articlesQuery = $this->getArticleRepository()->findAllArticlesQuery();

        $pagerfanta = $this->pagination($page, $articlesQuery);

        return $this->render('Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }

    private function getCommentRepository()
    {
        return $this->getDoctrine()->getRepository(Comment::class);
    }

    private function getArticle(string $articleId)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);
        if (null === $article) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        return $article;
    }

    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }

    private function pagination($page, $articles)
    {
        $adapter = new DoctrineORMAdapter($articles);
        $pagerfanta = new Pagerfanta($adapter);
        $maxPerPage = $pagerfanta->getMaxPerPage();
        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default
        $nbResults = $pagerfanta->getNbResults();
        $pagerfanta->getNbPages();
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate($nbResults); // whether the number of results is higher than the max per page
        return $pagerfanta;
    }
}
