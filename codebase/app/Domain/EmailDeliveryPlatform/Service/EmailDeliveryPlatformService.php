<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Jobs\SendEmail;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Domain\EmailDeliveryPlatform\Service\MailjetService;
use App\Domain\EmailDeliveryPlatform\Service\SendgridService;
use App\Exceptions\InvalidRequestException;
use App\Models\EmailDeliveryPlatform;
use App\Domain\EmailDeliveryPlatform\Enom\EmailDeliveryPlatformEnom;
use App\Models\MessageLog;
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
//            'to.*.name' => ['required',],
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

        $service = null;
        foreach (EmailDeliveryPlatformEnom::getPlatformsNames() as $platformName) {
            if (EmailDeliveryPlatformEnom::MAILJET_PLATFORM == $platformName) {
                $service = $this->mailjetService;
            } elseif (EmailDeliveryPlatformEnom::SENDGRID_PLATFORM == $platformName) {
                $service = $this->sendgridService;
            }

            $isMessageSent = $service->sendMessage($messageParams);
            if ($isMessageSent) {
                $platform = $service->getPlatform();
                $this->logMessage($platform, $messageParams->subject, EmailDeliveryPlatformEnom::MESSAGE_SENT_STATUS);

                break;
            }
        }

        return $isMessageSent;
    }

    public function logMessage(EmailDeliveryPlatform $platform, $subject, $status): void
    {
        $messageLog = new MessageLog();
        $messageLog->subject = $subject;
        $messageLog->status = $status;
        $platform->messageLog()->save($messageLog);
    }
}
