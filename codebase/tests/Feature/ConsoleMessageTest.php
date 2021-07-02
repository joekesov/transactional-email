<?php

namespace Tests\Feature;

use App\Jobs\SendEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ConsoleMessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_console_command()
    {
        Queue::fake();

        $this->artisan('emails:send')
            ->expectsQuestion('Recipient Email:', 'joemailtester@gmail.com')
            ->expectsConfirmation('Do you want to add other recipient?', 'no')
            ->expectsQuestion('What is the subject of the email?', 'Subject')
            ->expectsQuestion('What is the content type?', 'text/plain')
            ->expectsQuestion('What is the content of the email?', 'Email content')
            ->assertExitCode(0);

        Queue::assertPushedOn('default', SendEmail::class);
    }
}
