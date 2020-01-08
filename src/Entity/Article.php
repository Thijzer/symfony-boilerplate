<?php

namespace App\Entity;

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

    public function __construct($author)
    {
        $this->author = $author;

        $this->categories = new ArrayCollection();

        $this->comments = new ArrayCollection();

        $dateTime= new \DateTime();

        $this->setCreated($dateTime);

        $this->setUpdated(clone $dateTime);

    }

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        $this->setSlug($title);
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setBody($body)
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

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setTags($tags)
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

    public function setSlug($slug)
    {
        $this->slug = $this->slugify($slug);
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
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
        $this->categories[]=$category;
    }

    public function removeCategory(Category $category)
    {
        $this->categories->removeElement($category);
    }



}
