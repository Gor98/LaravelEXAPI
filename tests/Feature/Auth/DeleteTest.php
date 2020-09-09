<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\BaseTest;

class DeleteTest extends BaseTest
{
    /**
     * login test success
     */
    public function testLogoutSuccess()
    {
        $this->auth();
        $response = $this->deleteJson(route('logout'), [], $this->headers);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * login fil test
     */
    public function testLogInFailBadData()
    {
        $response = $this->deleteJson(route('logout'), [], $this->headers);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertJsonStructure([
            'error' => ['message', 'status']
        ]);
    }


}
