<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Models\EmailDeliveryPlatform;
use App\Domain\EmailDeliveryPlatform\Enom\EmailDeliveryPlatformEnom;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class MailjetService
{
    private $platform;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->platform = EmailDeliveryPlatform::firstWhere('name', EmailDeliveryPlatformEnom::MAILJET_PLATFORM);
        if (empty($this->platform)) {
            throw new \Exception('Platform is empty');
        }
    }

    public function sendMessage(MessageParamsVO $messageParams)
    {
        $http = $this->getPlatformCredentials();
        $response = $http->post($this->platform->url, $this->getParams($messageParams));

        dump($response);
    }

    public function getPlatformCredentials(): PendingRequest
    {
        $basicAuth = $this->platform->basicAuth;
        if (!empty($basicAuth)) {
            return Http::withBasicAuth($basicAuth->username, $basicAuth->password);
        }

        $barerToken = $this->platform->barerToken;
        if (!empty($barerToken)) {
            return Http::withToken($barerToken->token);
        }

        throw new \Exception('There are no credential for this delivery platform');
    }

    public function getParams(MessageParamsVO $messageParams): array
    {
        $message = [
                'From' => [
                    'Email' => $this->platform->from_email,
                    'Name' => $this->platform->from_name,
                ],
                'To' => [
                    [
                        'Email' => $messageParams->to['email'],
                        'Name' => $messageParams->to['name'],
                    ]
                ],
                'Subject' => $messageParams->subject,
            ];

        if ($messageParams->contentType == 'text/plain') {
            $message['TextPart'] = $messageParams->content;
        } else if ($messageParams->contentType == 'text/html') {
            $message['HTMLPart'] = $messageParams->content;
        }


        $params = [
            'Messages' => [$message]
        ];

        return $params;
    }
}
