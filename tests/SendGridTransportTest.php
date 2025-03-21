<?php

namespace Tests;

use Illuminate\Mail\MailManager;
use JorgeCortesDev\SendGridLaravel\SendGridTransport;
use Mockery as m;

class SendGridTransportTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }

    public function test_get_sendgrid_transport(): void
    {
        config()->set('mail.mailers.sendgrid', [
            'transport' => 'sendgrid',
            'options' => [],
        ]);

        $mailManager = $this->app->make(MailManager::class);

        $mailer = $mailManager->mailer('sendgrid');

        $transport = $mailer->getSymfonyTransport();

        $this->assertInstanceOf(SendGridTransport::class, $transport);
    }
}
