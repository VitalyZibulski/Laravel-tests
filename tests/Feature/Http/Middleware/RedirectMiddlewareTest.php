<?php

namespace Tests\Feature\Http\Middleware;

use App\Http\Middleware\RedirectMiddleware;
use App\Models\Redirect;
use Illuminate\Http\Response;
use Tests\TestCase;

class RedirectMiddlewareTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function it_middleware_in_isolation()
    {
        $middleware = app(RedirectMiddleware::class);
        $response = $middleware->handle(
            $this->createRequest('get', '/'),
            fn() => new Response()
        );

        $this->assertTrue($response->isSuccessful());

        Redirect::factory()->create(['from' => '/', 'to' => '/new-home-page']);

        $response = $middleware->handle(
            $this->createRequest('get', '/'),
            fn() => new Response()
        );

        $this->assertTrue($response->isRedirect('http://testing-laravel.test/new-home-page'));

    }

    /**
     * @test
     * @return void
     */
    public function it_middleware_as_integration()
    {
        $this->get('/')->assertSuccessful();

        Redirect::factory()->create(['from' => '/', 'to' => '/new-home-page']);

        $this->get('/')->assertRedirect('/new-home-page');
    }
}
