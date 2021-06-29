<?php


namespace App\Http\Api\MailController;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MailController extends BaseController
{
    public function sendEmail(Request $request)
    {
        if ($request->expectsJson()) {
            $to = $request->input('to');
            $subject = $request->input('subject');

            // Mailjet -----------------------
//            $this->sendByMailjet();

            // SendGrid ---------------------
//            $this->sendBySendGrid();

        }
    }

    private function sendByMailjet()
    {
        $params = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => 'joekesov@gmail.com',
                        'Name' => 'Jorjo'
                    ],
                    'To' => [
                        [
                            'Email' => 'joemailtester@gmail.com',
                            'Name' => 'Jorjo'
                        ]
                    ],
                    'Subject' => 'Sent from Laravel Yeap',
                    'TextPart' => 'Text loren ipsun',
                    'HTMLPart' => '<h1>Html part </h1>',
                ]
            ]
        ];

        $response = Http::withBasicAuth('db2550342463edf498e44c520c5d0d1c', '3c55ce95e746ba9707c4f75c3fa67b51')
            ->post('https://api.mailjet.com/v3.1/send', $params);

        dump($response);
    }


    private function sendBySendGrid()
    {
        $response = Http::withToken('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')
            ->post('https://api.sendgrid.com/v3/mail/send', [
                'personalizations' => [
                    [
                        'to' => [
                            [
                                'email' => 'joemailtester@gmail.com',
                            ]
                        ]
                    ]
                ],
                'from' => [
                    'email' => 'joemailtester@gmail.com',
                ],
                'subject' => 'Laravel Sendgrid send',
                'content' => [
                    [
                        'type' => 'text/plain',
                        'value' => 'We are trying'
                    ]
                ]
            ]);

        return $response;
    }
}
