<?php


namespace App\Http\Api\MailController;

use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use App\Domain\EmailDeliveryPlatform\Service\EmailDeliveryPlatformService;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class MailController extends BaseController
{
    private $service;

    public function __construct(EmailDeliveryPlatformService $deliveryPlatformService)
    {
        $this->service = $deliveryPlatformService;
    }

    public function sendEmail(Request $request)
    {
        if ($request->expectsJson()) {
            $messageParams = new MessageParamsVO();
            $messageParams->to = $request->input('to');
            $messageParams->subject = $request->input('subject');
            $messageParams->contentType = $request->input('contentType');
            $messageParams->content = $request->input('content');

            try {
                $this->service->sendMessageToQueue($messageParams);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);
            }

            return response()->json(null, 202);
        }

        return response()->json([], 400);
    }

//    private function sendByMailjet()
//    {
//        $params = [
//            'Messages' => [
//                [
//                    'From' => [
//                        'Email' => 'joekesov@gmail.com',
//                        'Name' => 'Jorjo'
//                    ],
//                    'To' => [
//                        [
//                            'Email' => 'joemailtester@gmail.com',
//                            'Name' => 'Jorjo'
//                        ]
//                    ],
//                    'Subject' => 'Sent from Laravel Yeap',
//                    'TextPart' => 'Text loren ipsun',
//                    'HTMLPart' => '<h1>Html part </h1>',
//                ]
//            ]
//        ];
//
//        $response = Http::withBasicAuth('apikey', 'secretkey')
//            ->post('https://api.mailjet.com/v3.1/send', $params);
//
//        dump($response);
//    }


//    private function sendBySendGrid()
//    {
//        $response = Http::withToken('token')
//            ->post('https://api.sendgrid.com/v3/mail/send', [
//                'personalizations' => [
//                    [
//                        'to' => [
//                            [
//                                'email' => 'joemailtester@gmail.com',
//                            ]
//                        ]
//                    ]
//                ],
//                'from' => [
//                    'email' => 'joemailtester@gmail.com',
//                ],
//                'subject' => 'Laravel Sendgrid send',
//                'content' => [
//                    [
//                        'type' => 'text/plain',
//                        'value' => 'We are trying'
//                    ]
//                ]
//            ]);
//
//        return $response;
//    }
}
