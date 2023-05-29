<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
use App\Models\BlogPostLike;
use App\Models\Enums\BlogPostStatus;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * @return void
     */
    public function it_with_factories_relations()
    {
        //1
//        $post = BlogPost::factory()
//            ->has(BlogPostLike::factory()->count(5), )
//            ->create();

        //2
//        $post = BlogPost::factory()
//            ->has(BlogPostLike::factory()->count(5), 'postLikes')
//            ->create();

        // 3
        $post = BlogPost::factory()
            ->hasPostLikes(5)
            ->create();

        // 4
        // belongsTo

        $postLike = BlogPostLike::factory()
            ->for(BlogPost::factory()->published())
            ->create();

        $this->assertCount(5, $post->postLikes);
        $this->assertTrue($postLike->blogPost->isPublished());
    }

    /**
     * @test
     * @return void
     */
    public function it_test_published_scope()
    {
        BlogPost::factory()->create([
           'date' => '2021-06-01',
           'status' => BlogPostStatus::PUBLISHED()
        ]);

        $this->travelTo(Carbon::make('2021-01-01'));

        $this->assertEquals(0, BlogPost::query()->wherePublished()->count());

        $this->travelTo(Carbon::make('2021-07-01'));

        $this->assertEquals(1, BlogPost::query()->wherePublished()->count());

        // travelBack - to original time
        // travel(1)->years() - move time forward 1 year
    }
}
