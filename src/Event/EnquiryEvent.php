<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;

class EnquiryEvent extends Event
{
    public const ENQUERY_CREATED = 'enquery_created';

    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }
    public function getCode()
    {
        return $this->code;
    }
}