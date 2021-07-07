<?php

namespace App\EventSubscriber;

use App\Entity\Enquiry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Event\EnquiryEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class ContactPageEventSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onEnquirySubmitted(EnquiryEvent $event)
    {
        $enquiry = $event->getEnquiry();

        $this->sendMail($this->getMail($enquiry));
    }

    private function sendMail(Mail $mail)
    {
        try {
            $email = (new TemplatedEmail())
                ->from($mail->getSender()->getEmail())
                ->to($mail->getReceiver()->getEmail())
                ->subject($mail->getSubject())
                ->htmlTemplate('emails/contactEmail.html.twig')
                ->context(['name' => $mail->getReceiver()->getName(),'body' => $mail->getBody() ]);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }

    private function getMail(Enquiry $enquiry)
    {
        return new Mail(
            $enquiry->getSubject(),
            new EmailAddress('sysadmin@induxx.be'),
            new EmailAddress($enquiry->getEmail(), $enquiry->getName()),
            $enquiry->getBody()
        );
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            EnquiryEvent::ENQUERY_CREATED => [
                ['onEnquirySubmitted', 10],
            ],
        ];
    }
}