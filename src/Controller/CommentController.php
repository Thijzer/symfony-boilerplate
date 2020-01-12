<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{

    public function create(Request $request, $articleId)
    {
        $comment= new Comment();
        $form= $this->createForm(CommentType::class,$comment);

        $article = $this->getArticle($articleId);
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();
            $article->addComment($comment);

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('blogger-notice', 'Your comment was successfully created.');
        }

        return $this->render('comment/create.html.twig',
            ['articleInput'=> $article->getTitle()
                , 'form'=> $form->createView()]);
    }

    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }

    private function  getArticle($article_id)
    {
        $article = $this->getArticleRepository()->find($article_id);

        if(!$article)
        {
            throw $this->createNotFoundException('Unable to find Article.');
        }

        return $article;
    }
}
