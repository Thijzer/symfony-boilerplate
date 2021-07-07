<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("author", TextType::class)
            ->add("title", TextType::class)
            ->add( "body", TextareaType::class)
            ->add("image",TextType::class)
            ->add("categories",EntityType::class, [
                'multiple'=>true,
                'class' => Category::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'Name',
            ])
        ;
    }
}