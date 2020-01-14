<?php
// src/DataFixtures/ArticleFixtures.php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 50; $i++) {

            $article = new Article();
            $category = $this->getCategory();
            $comment = $this->getComment();

            $article->setAuthor('Kenneth Barnes');
            $article->setTitle('article'.$i);
            $article->setImage('Lamp_Woonkamer.jpg');
            $article->setBody('We all have heard about Computer Programming gaining a lot 
            of popularity in the past 3 decades. So many students these days want to opt for a Computer Science stream in order to 
            get a job at their dream tech company - Google, Facebook, Microsoft, Apple and whatnot.');

            $article->addComment($comment);
            $article->addCategory($category);

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getCategory(): Category
    {
        return $this->getRandomReference(CategoryFixtures::CATEGORY, 10);
    }

    public function getComment(): Comment
    {
        return $this->getReference(CommentFixtures::COMMENT_USER);
    }

    private function getRandomReference(string $reference, int $max)
    {
        $rand = 0;
        while (true) {
            $rand = random_int(0, $max);
            if ($this->hasReference($reference.$rand)) {
                break;
            }
        }
        return $this->getReference($reference.$rand);
    }

    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
            CommentFixtures::class
        );
    }
}