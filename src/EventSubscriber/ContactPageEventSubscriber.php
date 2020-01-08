<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Mailer\MailerService;
use App\Event\EnquiryEvent;

class ContactPageEventSubscriber implements EventSubscriberInterface
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService=$mailerService;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry= $event->getCode();

        $this->mailerService->sendMail(new Mail('Contact enquiry from symblog'
            ,EmailAddress::createEmailAddress('simsimpeeeters@gmail.com','blabla')
            ,EmailAddress::createEmailAddress('simsimpeeeters@gmail.com','blabla')
            ,$this->mailerService->renderTemplate('page/contactEmail.txt.twig', [
                'enquiry' => $enquiry,
            ])));
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