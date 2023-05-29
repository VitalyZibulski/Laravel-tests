<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
use App\Models\Enums\BlogPostStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @return void
     */
    public function it_show_a_list_of_blog_posts()
    {
        $this->withoutExceptionHandling();

        BlogPost::create([
           'title' => 'Parallel php',
           'date' => '2021-02-01',
           'body' => 'test',
           'author' => 'User',
           'status'=> BlogPostStatus::PUBLISHED()
        ]);

        BlogPost::create([
            'title' => 'Parallel php1',
            'date' => '2021-01-01',
            'body' => 'test',
            'author' => 'User',
            'status'=> BlogPostStatus::PUBLISHED()
        ]);

        BlogPost::create([
            'title' => 'Draft post',
            'date' => '2021-02-02',
            'body' => 'test',
            'author' => 'User',
            'status'=> BlogPostStatus::DRAFT()
        ]);

        $this
            ->get('/')
            ->assertSuccessful()
            ->assertSee('Parallel php')
            ->assertSeeInOrder([
               'Parallel php',
               'Parallel php1'
            ])
            ->assertDontSee('Draft post');
    }
}
