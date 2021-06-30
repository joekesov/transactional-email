<?php


namespace App\Domain\EmailDeliveryPlatform\Enom;


class EmailDeliveryPlatformEnom
{
    const MAILJET_PLATFORM = 'mailjet';
    const SENDGRID_PLATFORM = 'sendgrid';

    const BASIC_AUTH_CREDENTIAL_TYPE = '';
    const BARER_TOKEN_CREDENTIAL_TYPE = '';

    private function __construct()
    {

    }

    public static function getPlatformsNames()
    {
        return [
            self::MAILJET_PLATFORM,
            self::SENDGRID_PLATFORM,
        ];
    }
}
