<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Enquiry;
use App\Form\EnquiryType;
use App\Event\EnquiryEvent;

class PageController extends AbstractController
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function AboutPage()
    {
        return $this->render('page/about.html.twig', []);
    }

    public function ContactPage(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class,$enquiry);
        $form->handleRequest($request);

        //$captcha = $this->get('phpro.captcha-service');

        if ($form->isSubmitted() && $form->isValid() /* && $captcha->isValid($request) */ ) {
            $this->eventDispatcher->dispatch(new EnquiryEvent($enquiry), EnquiryEvent::ENQUERY_CREATED);
            $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
            return $this->redirectToRoute('page_contact');
        }
        return $this->render('Page/contact.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    public function sidebar()
    {
        $articleRepository= $this->getDoctrine()->getRepository(Article::class)->findAll();

        $categories = $this->createCategoryList($articleRepository);



        return $this->render('Page/sidebar.html.twig', []);
    }

    public function createCategoryList($articleCategories)
    {
        $categories = [];
        foreach ($articleCategories as $article_Category)
        {
            $categories = array_merge(explode(',',$article_Category['categories']),$categories);
        }

        foreach ($categories as &$category) {
            $category = trim($category);
        }
        return $categories;
    }
}
