<?php

namespace App\Console\Commands;

use App\Models\EmailDeliveryPlatform;
use App\Models\BasicAuth;
use App\Models\BarerToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class AddEmailDeliveryPlatform extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email_delivery_platform:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add email delivery platform into the database';

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
    public function handle()
    {
        $editDeliveryPlatform = true;

        $name = $this->choice(
            'What is the name of the Email Delivery Platform?',
            ['mailjet', 'sendgrid'],
            0
        );

        $emailDeliveryPlatform = EmailDeliveryPlatform::firstWhere('name', $name);
        if (!empty($emailDeliveryPlatform)) {
            $hasCredential = false;

            $this->line('--------------- Email Delivery Platform ------------------');
            $this->table(
                ['id', 'name', 'url', 'from_email', 'from_name'],
                [$emailDeliveryPlatform->toArray()]
            );
            $this->newLine(2);

            $basicAuth = $emailDeliveryPlatform->basicAuth;
            if (!empty($basicAuth)) {
                $this->line('------------- Basic Auth ---------- ');
                $this->table(
                    ['id', 'platform_id', 'username', 'password'],
                    [$basicAuth->toArray()]
                );
                $hasCredential = true;
            }

            $barerToken = $emailDeliveryPlatform->barerToken;
            if (!empty($barerToken)) {
                $this->line('---------------- Barer token ---------------');
                $this->table(
                    ['id', 'platform_id', 'token'],
                    [$barerToken->toArray()]
                );

                $hasCredential = true;
            }

            if (!$hasCredential) {
                $this->error('This email delivery platform does not have credentials!');
            }

            $editDeliveryPlatform = $this->confirm('Do you wish to edit this email delivery platform?');
        }

        if ($editDeliveryPlatform) {
            if (empty($emailDeliveryPlatform)) {
                $emailDeliveryPlatform = new EmailDeliveryPlatform();
                $emailDeliveryPlatform->name = $name;
            }

            $url = $this->ask(sprintf('What is the url of the Email Delivery Platform [%s]?', $emailDeliveryPlatform->url));
            $url = $url ?? $emailDeliveryPlatform->url;


            $fromEmail = $this->ask(sprintf('What is the from email of the Email Delivery Platform [%s]?', $emailDeliveryPlatform->from_email));
            $fromEmail = $fromEmail ?? $emailDeliveryPlatform->from_email;

            $fromName = $this->ask(sprintf('What is the from name of the Email Delivery Platform [%s]?', $emailDeliveryPlatform->from_name));
            $fromName = $fromName ?? $emailDeliveryPlatform->from_name;


            $validator = Validator::make([
                'name' => $name,
                'url' => $url,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
            ], [
                'name' => [
                    'required',
//                'unique:email_delivery_platform'
                ],
                'url' => ['required', 'url'],
                'from_email' => ['required', 'email'],
                'from_name' => ['required'],
            ]);

            if ($validator->fails()) {
                print_r($validator->errors()); exit;
            }



            $emailDeliveryPlatform->url = $url;
            $emailDeliveryPlatform->from_email = $fromEmail;
            $emailDeliveryPlatform->from_name = $fromName;
            $emailDeliveryPlatform->save();
        }


        if ($this->confirm('Do you wish to add/edit this email delivery platform credentials?')) {
            $basicAuth = $emailDeliveryPlatform->basicAuth;
            if (!empty($basicAuth)) {
                $basicAuth->delete();
            }
            $barerToken = $emailDeliveryPlatform->barerToken;
            if (!empty($barerToken)) {
                $barerToken->delete();
            }

            $credentialType = $this->choice(
                'What is the name of the Email Delivery Platform?',
                ['basicAuth', 'barerToken'],
                0
            );

            if ('basicAuth' == $credentialType) {
                $basicAuth = new BasicAuth();
                $basicAuth->username = $this->ask('Please fill up the user name');
                $basicAuth->password = $this->ask('Please fill up the password');
                $emailDeliveryPlatform->basicAuth()->save($basicAuth);

            } else if ('barerToken' == $credentialType) {
                $barerToken = new BarerToken();
                $barerToken->token = $this->ask('Please fill up the token');
                $emailDeliveryPlatform->barerToken()->save($barerToken);
            }
        }

        return 0;
    }
}
