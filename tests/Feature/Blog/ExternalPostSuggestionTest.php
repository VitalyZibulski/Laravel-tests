<?php

namespace Tests\Feature\Blog;

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ExternalPostSuggestionController;
use App\Mail\ExternalPostSuggestedMail;
use App\Models\ExternalPost;
use App\Models\User;
use Event;
use Http;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Bus;
use Mail;
use Notification;
use Queue;
use Storage;
use Tests\TestCase;

class ExternalPostSuggestionTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * @test
     * @return void
     */
    public function it_external_post_can_be_submitted()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();

        Mail::fake();

        $this->post(action(ExternalPostSuggestionController::class), [
            'title' => 'test',
            'url' => 'https://test.com'
        ])
        ->assertRedirect(action([BlogPostController::class, 'index']))
        ->assertSessionHas('laravel_flash_message');

        Mail::assertSent(ExternalPostSuggestedMail::class);
        // or
        Mail::assertSent(function (ExternalPostSuggestedMail $mail) use ($user) {
            return $mail->to[0]['address'] === $user->email;
        });

        // others fakers
        Bus::fake();
        Event::fake();
        Http::fake();
        Notification::fake();
        Queue::fake();
        Storage::fake();

        $this->assertDatabaseHas(ExternalPost::class, [
            'title' => 'test',
            'url' => 'https://test.com'
        ]);
    }
}
