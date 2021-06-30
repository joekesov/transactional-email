<?php

namespace App\Console\Commands;

use App\Models\EmailDeliveryPlatform;
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
        $name = $this->ask('What is the name of the Email Delivery Platform?');
        $emailDeliveryPlatform = EmailDeliveryPlatform::firstWhere('name', $name);

        if (!empty($emailDeliveryPlatform)) {
            $this->table(
                ['id', 'name', 'url', 'from_email', 'from_name'],
                [$emailDeliveryPlatform->toArray()]
            );

            $basicAuth = $emailDeliveryPlatform->basicAuth;
            if (!empty($basicAuth)) {
                $this->table(
                    ['id', ],
                    [$basicAuth->toArray()]
                );
            }

            $barerToken = $emailDeliveryPlatform->barerToken;
            if (!empty($barerToken)) {
                $this->table(
                    ['id', ],
                    [$barerToken->toArray()]
                );
            }

            if (!$this->confirm('Do you wish to edit this email delivery platform?')) {
                return 0;
            }
        }


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



        print_r($name); exit;

        return 0;
    }
}
