<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Domain\EmailDeliveryPlatform\Service\AbstractPlatformService;
use App\Domain\EmailDeliveryPlatform\Enom\EmailDeliveryPlatformEnom;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use Illuminate\Http\Client\Response;

class SendgridService extends AbstractPlatformService
{
    public function getPlatformName(): string
    {
        return EmailDeliveryPlatformEnom::SENDGRID_PLATFORM;
    }

    protected function checkResponse(Response $response): bool
    {
        if ($response->status() != 202) {
            return false;
        }

        return true;
    }

    public function getParams(MessageParamsVO $messageParams): array
    {
        $params = [
            'personalizations' => [
                [
                    'to' => [
                        [
                            'email' => $messageParams->to['email'],
                        ]
                    ]
                ]
            ],
            'from' => [
                'email' => $this->platform->from_email,
            ],
            'subject' => $messageParams->subject,
            'content' => [
                [
                    'type' => $messageParams->contentType,
                    'value' => $messageParams->content,
                ]
            ]
        ];


        return $params;
    }
}
