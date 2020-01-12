<?php

namespace App\Entity;

class Category
{

    private $id;

    private $name;

    private $code_name;

    public function __construct()
    {

    }

    public function setName($name)
    {
        $this->name=strtolower($name);

        $this->code_name= base64_encode(strtolower($name));
    }

    public function getCodeName()
    {
        return $this->code_name;
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
