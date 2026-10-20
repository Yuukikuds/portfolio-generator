<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;

/**
 * Sends Laravel mail through an email provider's HTTPS API instead of SMTP.
 * Railway blocks SMTP on the Free / Trial / Hobby plans, but HTTPS always works.
 * Supported providers: "resend" and "brevo". No extra Composer package is needed.
 */
class HttpApiTransport extends AbstractTransport
{
    public function __construct(private string $provider, private ?string $key)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        if (! $this->key) {
            throw new RuntimeException('MAIL_API_KEY is not set, so the email could not be sent.');
        }

        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $from = $email->getFrom()[0];
        $to = $email->getTo();

        $response = match ($this->provider) {
            'brevo' => Http::withHeaders(['api-key' => $this->key])->acceptJson()->timeout(15)->post(
                'https://api.brevo.com/v3/smtp/email',
                array_filter([
                    'sender' => array_filter(['name' => $from->getName(), 'email' => $from->getAddress()]),
                    'to' => array_map(fn (Address $a) => array_filter(['email' => $a->getAddress(), 'name' => $a->getName()]), $to),
                    'subject' => $email->getSubject(),
                    'htmlContent' => $email->getHtmlBody(),
                    'textContent' => $email->getTextBody(),
                ])
            ),
            'resend' => Http::withToken($this->key)->acceptJson()->timeout(15)->post(
                'https://api.resend.com/emails',
                array_filter([
                    'from' => $from->toString(),
                    'to' => array_map(fn (Address $a) => $a->getAddress(), $to),
                    'subject' => $email->getSubject(),
                    'html' => $email->getHtmlBody(),
                    'text' => $email->getTextBody(),
                ])
            ),
            default => throw new RuntimeException("Unknown MAIL_API_PROVIDER \"{$this->provider}\" (use resend or brevo)."),
        };

        if ($response->failed()) {
            throw new RuntimeException('The email provider rejected the message (HTTP '.$response->status().'): '.$response->body());
        }
    }

    public function __toString(): string
    {
        return 'http_api://'.$this->provider;
    }
}
