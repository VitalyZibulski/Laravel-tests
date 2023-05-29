<?php

namespace Tests\Feature\Blog;

use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\ExternalPostSuggestionController;
use App\Models\ExternalPost;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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

        User::factory()->create();

        $this->post(action(ExternalPostSuggestionController::class), [
            'title' => 'test',
            'url' => 'https://test.com'
        ])
        ->assertRedirect(action([BlogPostController::class, 'index']))
        ->assertSessionHas('laravel_flash_message');

        $this->assertDatabaseHas(ExternalPost::class, [
            'title' => 'test',
            'url' => 'https://test.com'
        ]);
    }
}
