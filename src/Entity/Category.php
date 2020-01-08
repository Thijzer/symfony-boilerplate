<?php

namespace App\Entity;

class Category
{

    private $id;

    private $name;

    public function __construct(string $name)
    {
        $this->name=$name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

}
