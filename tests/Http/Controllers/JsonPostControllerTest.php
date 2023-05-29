<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\JsonPostController;
use App\Models\BlogPost;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;


class JsonPostControllerTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_show_all_blog_posts()
    {
        BlogPost::factory()->count(2)->create();

        // 1
        $this->get(action([JsonPostController::class, 'index']))
            ->assertSuccessful()
            ->assertJsonCount(2, 'data');

        // 2
        $this->get(action([JsonPostController::class, 'index']))
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', 2)
                    ->has('data.0', function (AssertableJson $json) {
                       $json
                           ->has('id')
                           ->has('date')
                           ->has('slug')
                           ->etc();
                    });
            });
    }

    /**
     * @test
     * @return void
     */
    public function it_detail_show_blog_post()
    {
        [$postA, $postB] = BlogPost::factory()->count(2)->create();

        $this->get(action([JsonPostController::class, 'show'], $postA->slug))
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) =>
            $json
                ->has('id')
                ->whereType('id', 'integer')
                ->whereType('date', 'string')
                // or
                ->whereAllType([
                    'id' => 'integer',
                    'date' => 'string'
                ])
                ->where('id', $postA->id)
                ->etc()
            );
    }
}
