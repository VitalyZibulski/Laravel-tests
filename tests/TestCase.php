<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function login(User $user = null)
    {
        $user ??= User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function createRequest($method, $uri): Request
    {
        $symfonyRequest = SymfonyRequest::create(
          $uri,
          $method,
        );

        return Request::createFromBase($symfonyRequest);
    }
}
