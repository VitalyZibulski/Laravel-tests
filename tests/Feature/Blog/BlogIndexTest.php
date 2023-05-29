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

        // BlogPost::factory()->count(4)->create()

        BlogPost::factory()
            ->count(4)
            ->sequence([
                'title' => 'Parallel php',
                'status' => BlogPostStatus::PUBLISHED()
            ], [
                'title' => 'Parallel php1',
                'status' => BlogPostStatus::PUBLISHED()
            ], [
                'title' => 'Parallel php2',
                'status' => BlogPostStatus::PUBLISHED()
            ], [
                'title' => 'Draft post',
                'status' => BlogPostStatus::DRAFT()
            ])
            ->create();

//        BlogPost::factory()->create([
//           'title' => 'Parallel php',
//           'status'=> BlogPostStatus::PUBLISHED()
//        ]);
//
//        BlogPost::factory()->create([
//            'title' => 'Parallel php1',
//            'status'=> BlogPostStatus::PUBLISHED()
//        ]);
//
//        BlogPost::factory()->create([
//            'title' => 'Parallel php2',
//            'status'=> BlogPostStatus::PUBLISHED()
//        ]);
//
//        BlogPost::factory()->create([
//            'title' => 'Draft post',
//            'status'=> BlogPostStatus::DRAFT()
//        ]);

        $this
            ->get('/')
            ->assertSuccessful()
            ->assertSee('Parallel php')
            ->assertSeeInOrder([
               'Parallel php1',
               'Parallel php'
            ])
            ->assertDontSee('Draft post');
    }
}
