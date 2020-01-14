<?php
// src/DataFixtures/ArticleFixtures.php
namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public const COMMENT_USER = 'comment';

    public function load(ObjectManager $manager)
    {

        $comment = new Comment();
        $comment->setUser('Barney Higgings');
        $comment->setComment('I am a generated comment');

        $manager->persist($comment);
        $manager->flush();

        $this->addReference(self::COMMENT_USER, $comment);

    }
}