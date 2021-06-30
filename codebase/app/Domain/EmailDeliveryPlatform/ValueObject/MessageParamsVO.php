<?php


namespace App\Domain\EmailDeliveryPlatform\ValueObject;


class MessageParamsVO
{
    public $to;
    public $subject;
    public $contentType;
    public $content;

    public function toArray(): array
    {
        return (array) $this;
    }
}
