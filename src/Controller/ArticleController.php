<?php

namespace App\Controller;

    use App\Entity\Category;
    use App\Form\ArticleType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Pagerfanta\Pagerfanta;
    use App\Entity\Article;
    use App\Repository\ArticleRepository;
    use App\Entity\Comment;
    use App\Form\CommentType;
    use Symfony\Component\HttpFoundation\Request;

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

    public function create(Request $request)
    {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('blogger-notice', 'Your article was successfully saved. Thank you!');

        }
        return $this->render('Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function showByCategory($category_id,$page = 1)
    {
        $articlesQuery = $this->getArticleRepository()->findAllArticlesByCategoryIDS($category_id);
        $pagerfanta = $this->pagination($page, $articlesQuery);

        return $this->render('Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }

    public function show($slug)
    {
        $article = $this->getArticle($slug);
        $comments=$article->getComments();

        return $this->render('Article/show.html.twig', [
            'article' => $article,
            'comments' => $comments->toArray(),
        ]);
    }


    private function getArticle($slug)
    {
        $article = $this->getArticleRepository()->findOneBy(array('slug'=> $slug));
        if (null === $article) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }
        return $article;
    }

    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }
    private function getCategoryRepository(){
        return $this->getDoctrine()->getRepository(Category::class);
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
