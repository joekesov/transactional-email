<?php


namespace App\Domain\EmailDeliveryPlatform\Enom;


class EmailDeliveryPlatformEnom
{
    const MAILJET_PLATFORM = 'mailjet';
    const SENDGRID_PLATFORM = 'sendgrid';

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
