<?php

namespace App\Mailer;

class Mail
{
     private $body;
     private $receiver;
     private $subject;
     private $sender;

     public function __construct(
         string $subject,
         EmailAddress $sender,
         EmailAddress $receiver,
         string $body
     ) {
         $this->body=$body;
         $this->subject=$subject;
         $this->receiver=$receiver;
         $this->sender=$sender;
     }

     public function getBody()
     {
        return $this->body;
     }

     public function getSender(): EmailAddress
     {
         return $this->sender;
     }

     public function getReceiver(): EmailAddress
     {
         return $this->receiver;
     }

     public function getSubject()
     {
         return $this->subject;
     }
}
