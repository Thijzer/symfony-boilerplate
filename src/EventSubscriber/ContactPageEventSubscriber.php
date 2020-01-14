<?php

namespace App\EventSubscriber;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Event\EnquiryEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactPageEventSubscriber implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry= $event->getCode();

        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EnquiryEvent::ENQUERY_CREATED => [
                ['onCustomEvent', 10],
            ],
        ];
    }
}