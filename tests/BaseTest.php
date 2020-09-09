<?php

namespace Tests;

use App\Modules\Auth\Entities\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;

/**
 * Class BaseTest
 * @package Tests
 */
abstract class BaseTest extends TestCase
{
    use WithFaker, DatabaseTransactions, DatabaseMigrations;

    const AUTH_URL = '/api/auth/token';
    const USERS_URL = '/api/users';


    protected $headers = [
        'Accept' => 'application/json'
    ];
    protected $scopes = ['*'];
    protected $user;
    protected $baseUrl = 'http://localhost';


    public function setUp() :void
    {
        parent::setUp();
    }

    public function auth()
    {
        $this->user = factory(User::class)->create();
        $this->headers['Authorization'] = 'Bearer '.auth()->login($this->user);
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return TestResponse
     */
    final public function getJson($uri, array $headers = []): TestResponse
    {
        return parent::getJson($uri, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    final public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::postJson($uri, $data, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    final public function putJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::putJson($uri, $data, array_merge($this->headers, $headers));
    }

    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    final public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::patchJson($uri, $data, array_merge($this->headers, $headers));
    }


    /**
     * @param string $uri
     * @param array $data
     * @param array $headers
     * @return TestResponse
     */
    final public function deleteJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::deleteJson($uri, $data, array_merge($this->headers, $headers));
    }
}
