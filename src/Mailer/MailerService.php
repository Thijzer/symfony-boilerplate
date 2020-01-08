<?php

namespace App\Mailer;

use http\Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Swift_Mailer;
use Swift_Message;
use Swift_TransportException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MailerService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $mailer;
    private $twig;

    public function __construct(Environment $twig, Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendMail(Mail $mail)
    {
        try {
            $message = new Swift_Message(
                $mail->getSubject(),
                $mail->getBody()
            );


            $message
                ->setFrom($mail->getSender()->getEmail())
                ->setTo($mail->getReceiver()->getEmail())
            ;

            $this->mailer->send($message);
        }
        catch (\Swift_TransportException $STe) {
            $errorMsg = "the mail service has encountered a problem. Please retry later or contact the site admin.";

            $this->logger->critical($errorMsg);
        }
    }

    public function renderTemplate($templateName, array $context)
    {
        try {
            return $this->twig->render(
                $templateName,
                $context
            );
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }

        return false;
    }
}