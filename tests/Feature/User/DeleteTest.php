<?php

namespace Tests\Feature\User;

use App\Modules\Auth\Entities\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseTest;

/**
 * Class DeleteTest
 * @package Tests\Feature\User
 */
class DeleteTest extends BaseTest
{
    /**
     * delete single user test success
     */
    public function testUserDeleteSuccess()
    {
        $this->auth();
        $user = factory(User::class)->create();
        $response = $this->deleteJson(route('users.destroy', ['user' => $user->id]), [], $this->headers);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * delete user test fail wrong id
     * @throws Exception
     */
    public function testUserDeleteFailWrongId()
    {
        $this->auth();
        $response = $this->deleteJson(route('users.destroy', ['user' => random_int(9999, 999999)]), [], $this->headers);
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure(['error' => ['message', 'status']]);
    }
}
