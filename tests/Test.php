<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;

abstract class Test extends TestCase
{
    use WithFaker, DatabaseTransactions, DatabaseMigrations;

    const AUTH_URL = '/api/auth/token';


    protected $headers = [
        'Accept' => 'application/json'
    ];
    protected $scopes = ['*'];
    protected $user;
    protected $baseUrl = 'http://localhost';


    public final function setUp() :void
    {
        $this->baseUrl = env('APP_URL');
        parent::setUp();
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return TestResponse
     */
    public final function getJson($uri, array $headers = []): TestResponse
    {
        return parent::getJson($uri, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    public final function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::postJson($uri, $data, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    public final function putJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::putJson($uri, $data, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    public final function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::patchJson($uri, $data, array_merge($this->headers, $headers));
    }


    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    public final function deleteJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::deleteJson($uri, $data, array_merge($this->headers, $headers));
    }
}
