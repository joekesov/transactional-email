<?php


namespace App\Domain\EmailDeliveryPlatform\Service;

use App\Jobs\SendEmail;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Domain\EmailDeliveryPlatform\Service\MailjetService;
use App\Exceptions\InvalidRequestException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmailDeliveryPlatformService
{
    private $mailjetService;

    public function __construct(MailjetService $mailjetService)
    {
        $this->mailjetService = $mailjetService;
    }

    public function sendMessageToQueue(MessageParamsVO $messageParams)
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

        $messageParamsCollection = [];
        foreach ($messageParams->to as $toParams) {
            $singleMessageParams = new MessageParamsVO();
            $singleMessageParams->to = $toParams;
            $singleMessageParams->subject = $messageParams->subject;
            $singleMessageParams->contentType = $messageParams->contentType;
            $singleMessageParams->content = $messageParams->content;

            SendEmail::dispatch($singleMessageParams);
            print_r('Are we here'); exit;
        }

    }

    public function sendMessage(MessageParamsVO $messageParams)
    {
        $this->mailjetService->sendMessage($messageParams);
    }

}
