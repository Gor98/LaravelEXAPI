<?php

namespace Tests\Feature\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseTest;

/**
 * Class PutTest
 * @package Tests\Feature\User
 */
class PutTest extends BaseTest
{
    /**
     * update user test success
     * @dataProvider updateData
     * @param array $data
     */
    public function testUserUpdateSuccess(array $data)
    {
        $this->auth();
        $response = $this->putJson(route('users.update', ['user' => $this->user->id]), $data, $this->headers);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data' => $data]);
    }

    /**
     * user data provider
     * @return \array[][]
     */
    public function updateData()
    {
        return [
            [
                [
                    'email' => $this->faker->unique()->email,
                    'name' => $this->faker->name
                ],
                [
                    'name' => $this->faker->name
                ],
                []
            ]
        ];
    }

    /**
     * get single user test fail wrong id
     * @throws Exception
     */
    public function testUserSingleFailWrongId()
    {
        $this->auth();
        $response = $this->putJson(route('users.update', ['user' => random_int(9999, 999999)]), $this->headers);
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure(['error' => ['message', 'status']]);
    }
}
