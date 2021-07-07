<?php

namespace App\Entity;

class Comment
{

    protected $id;

    protected $user;

    protected $comment;

    protected $approved;

    protected $created;

    protected $updated;

    public function __construct()
    {

        $dateTime = new \DateTime();

        $this->setCreated($dateTime);

        $this->setUpdated(clone $dateTime);

        $this->setApproved(true);
    }

    public function update(string $comment)
    {
        $this->comment = $comment;

        $this->setUpdated(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(string $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setComment(Comment $comment)
    {
        $this->comment = $comment;
    }
    public function getComment()
    {
        return $this->comment;
    }

    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    public function getApproved()
    {
        return $this->approved;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setUpdatedValue()
    {
        $this->setUpdated(new \DateTime());
    }

    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

}
