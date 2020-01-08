<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{

    public function index()
    {
        return $this->render('comment/comment.html.twig', []);
    }
}
