<?php

namespace App\Mailer;

class EmailAddress
{
     private $email;
     private $name;

     public function __construct($email,$name = null)
     {
         if($email && !filter_var($email,FILTER_VALIDATE_EMAIL))
         {
             throw new \InvalidArgumentException('Given e-mail address '.$email.' is not a valid');
         }

         $this->email=$email;
         $this->name=$name;
     }

    public function getEmail()
     {
         return $this->email;
     }

    public function getName()
    {
        return $this->name;
    }

    public static function createEmailAddress($email,$name= null)
    {
        return new self($email,$name);
    }

    public function toString()
    {
        return implode(', ',[$this->getEmail(),$this->getName()]);
    }

}