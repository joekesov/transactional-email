<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Jobs\SendEmail;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Domain\EmailDeliveryPlatform\Service\MailjetService;
use App\Domain\EmailDeliveryPlatform\Service\SendgridService;
use App\Exceptions\InvalidRequestException;
use App\Domain\EmailDeliveryPlatform\Enom\EmailDeliveryPlatformEnom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmailDeliveryPlatformService
{
    private $mailjetService;
    private $sendgridService;

    public function __construct(MailjetService $mailjetService, SendgridService $sendgridService)
    {
        $this->mailjetService = $mailjetService;
        $this->sendgridService = $sendgridService;
    }

    public function sendMessageToQueue(MessageParamsVO $messageParams): void
    {
        $validator = Validator::make($messageParams->toArray(), [
            'to.*.email' => ['required', ],
            'to.*.name' => ['required',],
            'subject' => ['required'],
            'contentType' => ['required', Rule::in(['text/plain', 'text/html']),],
            'content' => ['required'],
        ]);

        if ($validator->fails()) {
            throw new InvalidRequestException(implode('; ', $validator->errors()->all()));
        }

        foreach ($messageParams->to as $toParams) {
            $singleMessageParams = new MessageParamsVO();
            $singleMessageParams->to = $toParams;
            $singleMessageParams->subject = $messageParams->subject;
            $singleMessageParams->contentType = $messageParams->contentType;
            $singleMessageParams->content = $messageParams->content;

            SendEmail::dispatch($singleMessageParams);
        }
    }

    public function sendMessage(MessageParamsVO $messageParams): bool
    {
        $isMessageSent = false;
        foreach (EmailDeliveryPlatformEnom::getPlatformsNames() as $platformName) {
            if (EmailDeliveryPlatformEnom::MAILJET_PLATFORM == $platformName) {
                $isMessageSent = $this->mailjetService->sendMessage($messageParams);
            } elseif (EmailDeliveryPlatformEnom::SENDGRID_PLATFORM == $platformName) {
                $isMessageSent = $this->sendgridService->sendMessage($messageParams);
            }

            if ($isMessageSent) {
                break;
            }
        }

        return $isMessageSent;
    }

}
