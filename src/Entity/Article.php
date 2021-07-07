<?php

namespace App\Entity;

use App\Utils\Slug;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Article
{

    protected $id;

    protected $title;

    protected $author;

    protected $body;

    protected $image;

    protected $tags;

    protected $comments;

    protected $created;

    protected $updated;

    protected $slug;

    protected $categories;

    public function __construct()
    {

        $this->categories = new ArrayCollection();

        $this->comments = new ArrayCollection();

        $dateTime= new \DateTime();

        $this->setCreated($dateTime);

        $this->setUpdated(clone $dateTime);

    }

    public function createArticle( string $author,string $title,string $body)
    {
        $this->author=$author;
        $this->body=$body;
        $this->title=$title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        $this->setSlug($title);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor(string $author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody($length = null)
    {
        if (false === is_null($length) && $length > 0) {
            return substr($this->body, 0, $length);
        } else {
            return $this->body;
        }
    }

    public function setImage(string $image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setTags(string $tags)
    {
        $this->tags = $tags;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function  setUpdatedValue($updated)
    {
        $this->setUpdated(new \DateTime());
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setSlug(string $slug)
    {
        $this->slug = Slug::slugify($slug);
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function addCategory(Category $category)
    {
        $collectionCategorie = $this->getCategories();

        if($collectionCategorie->contains($category))
        {
            return false;
        }

        $this->categories[]=$category;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }



}
