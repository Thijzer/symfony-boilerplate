<?php

namespace App\EventSubscriber;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Event\EnquiryEvent;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;


class ContactPageEventSubscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer=$mailer;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry= $event->getCode();
        $mail = $this->getMail($enquiry);

        try {
            $email = (new TemplatedEmail())
                ->from($mail->getSender()->getEmail())
                ->to($mail->getReceiver()->getEmail())
                ->subject($mail->getSubject())
                ->htmlTemplate('emails/contactEmail.html.twig')
                ->context(['name' => $mail->getReceiver()->getName(),'body' => $mail->getBody() ])
            ;

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }

    }

    private function getMail($enquiry)
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
                ['onCustomEvent', 10],
            ],
        ];
    }
}