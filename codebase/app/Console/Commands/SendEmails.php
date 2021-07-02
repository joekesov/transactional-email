<?php

namespace App\Console\Commands;


use App\Domain\EmailDeliveryPlatform\Service\EmailDeliveryPlatformService;
use App\Domain\EmailDeliveryPlatform\ValueObject\MessageParamsVO;
use Illuminate\Console\Command;


class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send transactional emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(EmailDeliveryPlatformService $service)
    {
        $messageParams = new MessageParamsVO();

        $messageParams->to = $this->addRecipient();
        $messageParams->subject = $this->ask('What is the subject of the email?');
        $messageParams->contentType = $this->choice(
            'What is the content type?',
            ['text/plain', 'text/html'],
            0
        );
        $messageParams->content = $this->ask('What is the content of the email?');

        try {
            $service->sendMessageToQueue($messageParams);
        } catch (\Exception $e) {
            $errors = explode(';', $e->getMessage());
            foreach ($errors as $error) {
                $this->error($error);
            }

            return 1;
        }

        return 0;
    }

    private function addRecipient(array $recipients = [])
    {
        $recipient = [
            'email' => $this->ask('Recipient Email:'),
        ];

        $recipients[] = $recipient;

        if ($this->confirm('Do you want to add other recipient?')) {
            $recipients = $this->addRecipient($recipients);
        }

        return $recipients;
    }

}
