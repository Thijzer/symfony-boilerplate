<?php

namespace App\Mailer;

class Mail
{
     private $body;
     private $receiver;
     private $subject;
     private $sender;

     public function __construct($subject,$sender,$receiver,$body)
     {
         $this->body=$body;
         $this->subject=$subject;
         $this->receiver=$receiver;
         $this->sender=$sender;
     }


    public function getBody()
    {
        return $this->body;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function getSubject()
    {
        return $this->subject;
    }

}
