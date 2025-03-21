# Sendgrid Laravel

[![Latest Version](https://img.shields.io/packagist/v/jorgecortesdev/sendgrid-laravel.svg?style=flat-square)](https://packagist.org/packages/jorgecortesdev/sendgrid-laravel)
[![License](https://img.shields.io/packagist/l/jorgecortesdev/sendgrid-laravel.svg?style=flat-square)](LICENSE.md)

A Laravel wrapper for the official [SendGrid PHP library](https://github.com/sendgrid/sendgrid-php), providing a simple and elegant way to send emails through SendGrid in your Laravel applications.

## 🚨 Note

> **IMPORTANT:** This package currently provides only the most basic functionality required to send emails using SendGrid. No advanced features or additional abstractions are included. Feel free to submit a PR if you'd like to extend the functionality!

## Installation

You can install the package via Composer:

```bash
composer require jorgecortesdev/sendgrid-laravel
```

## Configuration

Update your `.env` file with the SendGrid API key:

```env
SENDGRID_API_KEY=
```

Second, add the following configuration to the `config/services.php` file:

```php
'sendgrid' => [
    'api_key' => env('SENDGRID_API_KEY'),
],
```

Lastly, add the following configuration array to your array of `mailers` in the `config/mail.php` file:

```php
'sendgrid' => [
    'transport' => 'sendgrid',
    'options' => [],
],
```

## Usage

Update your `.env` file with the new mailer:

```
MAIL_MAILER=sendgrid
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.