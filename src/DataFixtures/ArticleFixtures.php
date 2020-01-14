<?php
// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {

            $article = new Article();
            $categorie = $this->getReference();
            $comment = new Comment();
            $comment->setUser('Barney Higgings');
            $comment->setComment('I am a generated comment');

            $article->setAuthor('Kenneth Barnes');
            $article->setTitle('article'.$i);
            $article->setImage('Lamp_Woonkamer.jpg');
            $article->setBody('We all have heard about Computer Programming gaining a lot 
            of popularity in the past 3 decades. So many students these days want to opt for a Computer Science stream in order to 
            get a job at their dream tech company - Google, Facebook, Microsoft, Apple and whatnot.');

            $article->addComment($comment);

            $manager->persist($comment);
            }

            $manager->flush();
        }
        
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
    }
}