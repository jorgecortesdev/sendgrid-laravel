<?php

declare(strict_types=1);

namespace JorgeCortesDev\SendGridLaravel;

use JorgeCortesDev\SendGridLaravel\Exceptions\SendEmailException;
use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

class SendGridTransport extends AbstractTransport
{
    public function __construct(
        protected SendGrid $sendGrid,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $originalMessage = MessageConverter::toEmail($message->getOriginalMessage());
        $email = new Mail;
        $email->setFrom($originalMessage->getFrom()[0]->getAddress(), $originalMessage->getFrom()[0]->getName());
        $email->setSubject($originalMessage->getSubject());

        collect($originalMessage->getTo())->each(function (Address $address) use ($email) {
            $email->addTo($address->getAddress(), $address->getName());
        });

        if ($originalMessage->getTextBody()) {
            $email->addContent('text/plain', $originalMessage->getTextBody());
        }

        if ($originalMessage->getHtmlBody()) {
            $email->addContent('text/html', $originalMessage->getHtmlBody());
        }

        $response = $this->sendGrid->send($email);

        if ($response->statusCode() !== 202) {
            $data = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            throw SendEmailException::create($data['errors'][0]['message'], $response->statusCode());
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
