<?php

namespace Tests\Feature\Blog;

use App\Actions\SyncExternalPost;
use App\Models\ExternalPost;
use App\Support\Rss\RssEntry;
use App\Support\Rss\RssRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Http;
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

    /**
     * @test
     * @return void
     */
    public function it_synced_posts_are_stored_in_database_fake_http()
    {
        Http::fake([
           // 'https://test.com/feed' => Http::response($this->getFeed()),
            // or
            'https://test.com/*' => Http::response($this->getFeed()),
            // or any http
            '*' => Http::response($this->getFeed()),
        ]);

        $sync = app(SyncExternalPost::class);
        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test'
        ]);
    }

    /**
     * @test
     * @return void
     */
    public function it_synced_posts_are_stored_in_database_fake_http_with_sequence()
    {
        Http::fake([
            'https://test.com/*' => Http::sequence()
                ->push($this->getFeed('test-a'))
                ->push($this->getFeed('test-b')),
        ]);

        $sync = app(SyncExternalPost::class);
        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-a'
        ]);

        $this->assertDatabaseMissing(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-b'
        ]);

        $sync('https://test.com/feed');

        $this->assertDatabaseHas(ExternalPost::class, [
            'url' => 'https://test.com/blog/test',
            'title' => 'test-b'
        ]);
    }

    private function getFeed(string $title = 'test'): string
    {
        return  <<<XML
       <feed xmlns="http://www.w3.org/2005/Atom">
           <id>https://test.com/rss</id>
           <link href="https://test.com/rss"/>
           <title><![CDATA[ https://test.com ]]></title>
           <updated>2021-08-11T11:00:30+00:00</updated>

           <entry>
               <title><![CDATA[$title]]></title>

               <link rel="alternate" href="https://test.com/blog/test"/>

               <id>https://test.com/blog/test</id>

               <author>
                   <name><![CDATA[ Brent Roose ]]></name>
               </author>

               <summary type="html"><![CDATA[$title]]></summary>

               <updated>2021-07-29T00:00:00+00:00</updated>
           </entry>

           <entry>
               <title><![CDATA[$title]]></title>

               <link rel="alternate" href="https://test.com/blog/test"/>

               <id>https://test.com/blog/test</id>

               <author>
                   <name><![CDATA[ Brent Roose ]]></name>
               </author>

               <summary type="html"><![CDATA[$title]]></summary>

               <updated>2021-07-29T00:00:00+00:00</updated>
           </entry>
       </feed>
       XML;
    }
}
