<?php

namespace Tests\Console\Commands;

use App\Support\Rss\RssRepository;
use Tests\Feature\Fakes\RssRepositoryFake;
use Tests\TestCase;

class ListExternalPostsCommandTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_show_externals()
    {
        config()->set('services.external_feeds', ['https://a.test/rss', 'https://b.test/rss']);

        $this->artisan('list:externals')
            ->expectsTable(
                ['Feed'],
                [
                    ['https://a.test/rss'],
                    ['https://b.test/rss'],
                ]
            );
    }

    /**
     * @test
     * @return void
     */
    public function it_show_externals_sync()
    {
        RssRepositoryFake::setUp();

        config()->set('services.external_feeds', ['https://a.test/rss', 'https://b.test/rss']);

        $this->artisan('sync:externals')
            ->expectsOutput('Fetching 2 feeds')
            ->expectsOutput("\t- https://a.test/rss")
            ->expectsOutput("\t- https://b.test/rss")
            ->expectsOutput("Done")
            ->assertExitCode(0);
            //->expectsQuestion()


    }
}
