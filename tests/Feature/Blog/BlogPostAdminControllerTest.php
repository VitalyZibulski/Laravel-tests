<?php

namespace Tests\Feature\Blog;

use App\Http\Controllers\BlogPostAdminController;
use App\Models\BlogPost;
use App\Models\User;
use Tests\TestCase;

class BlogPostAdminControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_only_logged_user_can_make_changes_to_post()
    {
        $post = BlogPost::factory()->create();

        $sendRequest = fn() => $this
            ->post(action([BlogPostAdminController::class, 'update'], $post->slug), [
               'title' => 'test',
               'author' => $post->author,
               'body' => $post->body,
               'date' => $post->date->format('Y-m-d')
            ]);

        $sendRequest()->assertRedirect(route('login'));

        $this->assertNotEquals('test', $post->refresh()->title);

        $this->actingAs(User::factory()->create());

        $sendRequest();

        $this->assertEquals('test', $post->refresh()->title);
    }
}
