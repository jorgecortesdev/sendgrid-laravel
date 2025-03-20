<?php

namespace JorgeCortesDev\SendgridLaravel;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SendGrid;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Mail::extend('sendgrid', static function (array $config) {
            $apiKey = config('services.sendgrid.api_key');

            $sg = new SendGrid($apiKey, $config['options']);

            return new SendGridTransport($sg);
        });
    }
}