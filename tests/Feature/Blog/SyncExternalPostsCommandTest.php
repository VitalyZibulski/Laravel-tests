<?php

namespace Tests\Feature\Blog;

use App\Models\ExternalPost;
use App\Support\Rss\RssRepository;
use Tests\Feature\Fakes\RssRepositoryFake;
use Tests\TestCase;

class SyncExternalPostsCommandTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_sync_several_repositories_at_once()
    {
        RssRepositoryFake::setUp();

        $urls = [
          'https://test-a.com',
          'https://test-b.com',
          'https://test-c.com',
        ];

        config()->set('services.external_feeds', $urls);

        $this->artisan('sync:externals')->assertExitCode(0);
        $this->assertEquals($urls, RssRepositoryFake::getUrls());
        $this->assertDatabaseCount(ExternalPost::class, 3);
    }
}
