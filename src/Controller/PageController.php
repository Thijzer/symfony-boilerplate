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
        return $this->render('page/commentForm.html.twig', []);
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
        $categories= $this->getArticleRepository()->findAll();

        return $this->render('Page/sidebar.html.twig', [
            'categories'=> $categories
        ]);
    }

    public function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Category::class);
    }
}
