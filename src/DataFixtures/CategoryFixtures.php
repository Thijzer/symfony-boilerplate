<?php

// src/DataFixtures/ArticleFixtures.php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY = 'category__';

    public function load(ObjectManager $manager)
    {
        foreach (['ict', 'akeneo', 'blog'] as $i => $CatName) {
            $category= new Category();

            $category->setName($CatName);

            $manager->persist($category);

            $this->addReference(self::CATEGORY.$i, $category);
        }

        $manager->flush();
    }
}