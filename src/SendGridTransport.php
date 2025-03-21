<?php

declare(strict_types=1);

namespace JorgeCortesDev\SendGridLaravel;

use Exception;
use Illuminate\Support\Facades\Log;
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

        try {
            $this->sendGrid->send($email);
        } catch (Exception $e) {
            Log::error('Caught exception: '.$e->getMessage());
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
