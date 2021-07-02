<?php


namespace App\Domain\EmailDeliveryPlatform\Enom;


class EmailDeliveryPlatformEnom
{
    const MAILJET_PLATFORM = 'mailjet';
    const SENDGRID_PLATFORM = 'sendgrid';

    const MESSAGE_SENT_STATUS = 1;
    const MESSAGE_NOT_SENT_STATUS = 0;

    private function __construct()
    {

    }

    public static function getPlatformsNames()
    {
        return [
            self::SENDGRID_PLATFORM,
            self::MAILJET_PLATFORM,

        ];
    }
}
