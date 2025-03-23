<?php

namespace JorgeCortesDev\SendGridLaravel;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use JorgeCortesDev\SendGridLaravel\Exceptions\ApiKeyIsMissing;
use SendGrid;

class ServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SendGrid::class, static function (): SendGrid {
            $apiKey = config('services.sendgrid.api_key');
            $options = config('services.sendgrid.options') || [];

            if (! is_string($apiKey)) {
                throw ApiKeyIsMissing::create();
            }

            return new SendGrid($apiKey, $options);
        });
    }

    public function boot(): void
    {
        Mail::extend('sendgrid', static function (array $config) {
            $sendGrid = app(SendGrid::class);

            return new SendGridTransport($sendGrid);
        });
    }
}
