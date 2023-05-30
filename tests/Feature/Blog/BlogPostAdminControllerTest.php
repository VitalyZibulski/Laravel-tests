<?php

namespace Tests\Feature\Blog;

use App\Http\Controllers\BlogPostAdminController;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class BlogPostAdminControllerTest extends TestCase
{
    private Model $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->post = BlogPost::factory()->create();
    }

    /**
     * @test
     * @return void
     */
    public function it_only_logged_user_can_make_changes_to_post()
    {
        $sendRequest = fn() => $this
            ->post(action([BlogPostAdminController::class, 'update'], $this->post->slug), [
               'title' => 'test',
               'author' => $this->post->author,
               'body' => $this->post->body,
               'date' => $this->post->date->format('Y-m-d')
            ]);

        $sendRequest()->assertRedirect(route('login'));

        $this->assertNotEquals('test', $this->post->refresh()->title);

        $this->login();

        $sendRequest();

        $this->assertEquals('test', $this->post->refresh()->title);
    }

    /**
     * @test
     * @return void
     */
    public function it_required_fields()
    {
        $this->login();

        $this->post(action([BlogPostAdminController::class, 'update'], $this->post->slug), [])
            ->assertSessionHasErrors(['title', 'author', 'body', 'date']);

        $this->post(action([BlogPostAdminController::class, 'update'], $this->post->slug), [
            'title' => $this->post->title,
            'author' => $this->post->author,
            'body' => $this->post->body,
            'date' => $this->post->date->format('Y-m-d'),
        ])
            ->assertSessionHasNoErrors();
    }

    /**
     * @test
     * @return void
     */
    public function it_date_format_is_validated()
    {
        $this->login();

        $this->post(action([BlogPostAdminController::class, 'update'], $this->post->slug), [
            'title' => $this->post->title,
            'author' => $this->post->author,
            'body' => $this->post->body,
            'date' => '01/01/2021',
        ])
            ->dumpSession()
//            ->assertSessionHasErrors(['date']);
            // or
            ->assertSessionHasErrors([
                'date' => 'The date does not match the format Y-m-d.'
            ]);
    }
}
