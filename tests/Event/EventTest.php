<?php

namespace App\Tests\Event;

use App\Entity\Enquiry;
use App\Event\EnquiryEvent;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEventCode()
    {
        $enquiry = $this->getTestEnquiry();

        $enquiryEvent = new EnquiryEvent($enquiry);
        $result = $enquiryEvent->getEnquiry();

        $this->assertEquals($enquiry,$result);
    }

    private function getTestEnquiry()
    {
        $enquiry = new Enquiry();

        $enquiry->setName('simon');
        $enquiry->setEmail('peeterssimon@hotmail.be');
        $enquiry->setSubject('vraag over blog');
        $enquiry->setBody('dit is een test op het event dat word getriggert');

        return $enquiry;
    }
}