<?php

namespace Tests\Feature\Blog;

use App\Models\BlogPost;
use App\Models\BlogPostLike;
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
}
