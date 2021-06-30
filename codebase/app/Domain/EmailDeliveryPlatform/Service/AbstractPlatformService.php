<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Models\EmailDeliveryPlatform;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

abstract class AbstractPlatformService
{
    protected $platform;

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        $this->platform = EmailDeliveryPlatform::firstWhere('name', $this->getPlatformName());
        if (empty($this->platform)) {
            throw new \Exception(sprintf('There is no platform with the name %s into the DataBase'));
        }
    }

    abstract public function getPlatformName(): string;

    abstract public function getParams(MessageParamsVO $messageParams): array;

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

    public function sendMessage(MessageParamsVO $messageParams): bool
    {
        $http = $this->getPlatformCredentials();
        $response = $http->post($this->platform->url, $this->getParams($messageParams));

        return $this->checkResponse($response);
    }

    abstract protected function checkResponse(Response $response): bool;
}
