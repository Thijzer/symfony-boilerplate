<?php

namespace App\Controller;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class,$category);

        $code_name = '';
        $entityManager = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $category = $form->getData();
            $category->setName($category->getName());

            $code_name = $category->getCodeName();

            $entityManager->persist($category);
            $entityManager->flush();

            $this->addFlash('blogger-notice', 'Your category was successfully created.');
        }

        return $this->render('category/create.html.twig',
            ['code_name' => $code_name,
            'form' => $form->createView()
            ]);
    }
}
