<?php
namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;

class EnquiryEvent extends Event
{
    public const ENQUERY_CREATED = 'enquery_created';

    private $enquiry;

    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
    }
    public function getEnquiry()
    {
        return $this->enquiry;
    }
}