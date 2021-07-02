<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_success()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [
            'to' => [
                ['email' => "joemailtester@gmail.com"]
            ],
            'subject' => 'Test email without queue fake',
            'contentType' => 'text/plain',
            'content' => 'Some content'
        ]);

        $response
            ->assertStatus(202)
            ->assertJson([
                'status' => 202,
                'message' => 'Request Accepted'
            ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_wrong_email()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [
            'to' => [
                ['email' => "joemailtester"]
            ],
            'subject' => 'Test email without queue fake',
            'contentType' => 'text/plain',
            'content' => 'Some content'
        ]);

        $response
            ->assertStatus(400);

        Queue::assertNothingPushed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_wrong_content_type()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [
            'to' => [
                ['email' => "joemailtester@gmail.com"]
            ],
            'subject' => 'Test email without queue fake',
            'contentType' => 'maraba',
            'content' => 'Some content'
        ]);

        $response
            ->assertStatus(400);

        Queue::assertNothingPushed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_empty_subject()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [
            'to' => [
                ['email' => "joemailtester@gmail.com"]
            ],

            'contentType' => 'text/plain',
            'content' => 'Some content'
        ]);

        $response
            ->assertStatus(400);

        Queue::assertNothingPushed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_empty_content()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [
            'to' => [
                ['email' => "joemailtester@gmail.com"]
            ],
            'subject' => 'Test email without queue fake',
            'contentType' => 'text/plain',
        ]);

        $response
            ->assertStatus(400);

        Queue::assertNothingPushed();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_making_an_api_request_empty_to()
    {
        Queue::fake();

        $response = $this->postJson('/api/send/email', [

            'subject' => 'Test email without queue fake',
            'contentType' => 'text/plain',
            'content' => 'Some content'
        ]);

        $response
            ->assertStatus(400);

        Queue::assertNothingPushed();
    }
}
