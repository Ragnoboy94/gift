<?php

namespace App\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Email;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;

class PHPMailerTransport extends AbstractTransport
{
    protected $mailer;

    public function __construct(EventDispatcherInterface $dispatcher = null, LoggerInterface $logger = null)
    {
        parent::__construct($dispatcher, $logger);
        $this->mailer = new PHPMailer(true);
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (!$email instanceof Email) {
            throw new \InvalidArgumentException('Expected instance of Symfony\Component\Mime\Email.');
        }

        try {
            $this->mailer->isSMTP();
            $this->mailer->Host       = env('MAIL_HOST', 'smtp.mail.ru');
            $this->mailer->Username   = env('MAIL_USERNAME', 'still-1994@mail.ru');
            $this->mailer->Password   = env('MAIL_PASSWORD', 'GmR39mEVyzqqyKg9Wswr');
            $this->mailer->Port       = env('MAIL_PORT', 465);
            $this->mailer->SMTPAuth   = true;
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            // Recipients
            $this->mailer->setFrom(env('MAIL_FROM_ADDRESS','still-1994@mail.ru'), env('MAIL_FROM_NAME','Gift Admin'));
            $this->mailer->addAddress($email->getTo()[0]->getAddress());

            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $email->getSubject();
            $this->mailer->Body    = $email->getHtmlBody();

            $this->mailer->send();

        } catch (Exception $e) {
            throw new \RuntimeException('Message could not be sent. Mailer Error: ' . $this->mailer->ErrorInfo);
        }
    }

    public function __toString(): string
    {
        return 'phpmailer';
    }
}
