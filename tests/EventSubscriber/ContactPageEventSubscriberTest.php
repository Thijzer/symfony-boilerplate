<?php

namespace App\Tests\EventSubscriber;

use App\Entity\Enquiry;
use App\Event\EnquiryEvent;
use App\EventSubscriber\ContactPageEventSubscriber;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class ContactPageEventSubscriberTest extends TestCase
{
    public function test_it_should_send_an_email_from_an_inquery_event()
    {
        $mailer = $this->prophesize(MailerInterface::class);

        $subscriber = new ContactPageEventSubscriber($mailer->reveal());

        $event = $this->prophesize(EnquiryEvent::class);
        $event->getEnquiry()->willReturn($this->createEnquiry());

        $subscriber->onEnquirySubmitted($event->reveal());

        //$mailer->send(Argument::any())->shouldBeCalled();

        $mailer->send(Argument::type(TemplatedEmail::class))->shouldBeCalled();
    }

    private function createEnquiry()
    {
        $enquiry = new Enquiry();
        $enquiry->setBody('body');
        $enquiry->setSubject('subject');
        $enquiry->setEmail('email@example.com');
        $enquiry->setName('name');

        return $enquiry;
    }
}
