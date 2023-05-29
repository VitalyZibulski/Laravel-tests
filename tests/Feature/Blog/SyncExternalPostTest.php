<?php

namespace Tests\Feature\Blog;

use App\Actions\SyncExternalPost;
use App\Models\ExternalPost;
use App\Support\Rss\RssEntry;
use App\Support\Rss\RssRepository;
use Carbon\CarbonImmutable;
use Mockery\MockInterface;

class SyncExternalPostTest extends \Tests\TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_synced_posts_are_stored_in_database()
    {
        $rss = $this->mock(RssRepository::class, function (MockInterface $mock) {
           $mock->shouldReceive('fetch')
               ->andReturn(collect([
                   new RssEntry(
                       url: 'https://test.com',
                       title: 'test',
                       date: CarbonImmutable::make('2021-01-01')
                   )
               ]));
        });

        $sync = new SyncExternalPost($rss);
        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
           'url' => 'https://test.com',
           'title' => 'test'
        ]);

    }
}
