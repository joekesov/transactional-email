<?php


namespace App\Domain\EmailDeliveryPlatform\Service;


use App\Domain\EmailDeliveryPlatform\Service\AbstractPlatformService;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Models\EmailDeliveryPlatform;
use App\Domain\EmailDeliveryPlatform\Enom\EmailDeliveryPlatformEnom;
use Illuminate\Http\Client\Response;

class MailjetService extends AbstractPlatformService
{
    public function getPlatformName(): string
    {
        return EmailDeliveryPlatformEnom::MAILJET_PLATFORM;
    }

    protected function checkResponse(Response $response): bool
    {
        if ($response->status() != 200) {
            return false;
        }

        return true;
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
