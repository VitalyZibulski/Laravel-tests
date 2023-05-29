<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
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

        //1. BlogPost::factory()->count(4)->create()

//      //2.  BlogPost::factory()
//            ->count(4)
//            ->sequence([
//                'title' => 'Parallel php',
//                'status' => BlogPostStatus::PUBLISHED()
//            ], [
//                'title' => 'Parallel php1',
//                'status' => BlogPostStatus::PUBLISHED()
//            ], [
//                'title' => 'Parallel php2',
//                'status' => BlogPostStatus::PUBLISHED()
//            ], [
//                'title' => 'Draft post',
//                'status' => BlogPostStatus::DRAFT()
//            ])
//            ->create();

//        // 3. BlogPost::factory()->create([
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

        // 4
        BlogPost::factory()
            ->count(3)
            ->published()
            ->sequence(
                ['title' => 'Parallel php'],
                ['title' => 'Parallel php1'],
                ['title' => 'Parallel php2']
            )
            ->create();

        BlogPost::factory()
            ->draft()
            ->create(['title' => 'Draft post']);

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
