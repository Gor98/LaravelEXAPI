<?php

namespace Tests\Feature\Auth;

use App\Common\Tools\Setting;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseTest;

/**
 * Class PostTest
 * @package Tests\Feature\Auth
 */
class PostTest extends BaseTest
{
    /**
     * login test success
     */
    public function testLogInSuccess()
    {
        $this->auth();
        $response = $this->postJson(route('login'), ['email' => $this->user->email, 'password' => Setting::USER_PASS]);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'data' => ['token_type', 'access_token', 'expires_in']
        ]);
    }

    /**
     * login test fail
     * @param $data
     * @dataProvider badLoginData
     */
    public function testLogInFailBadData($data)
    {
        $response = $this->postJson(route('login'), $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'error' => ['message', 'errors']
        ]);
    }

    /**
     * bad login data provider
     * @return array[]
     */
    public function badLoginData()
    {
        return [
           [
               [ 'email' => 'badEmailExample.com', 'password' => "secret"],
               [ 'email' => 'emailNotExistInDB@gmail.com', 'password' => "secret"],
               []
           ]
        ];
    }

    /**
     * register test success
     */
    public function testRegisterSuccess()
    {
        $this->auth();
        $response = $this->postJson(route('register'), [
            'email' => $this->faker->unique()->email,
            'name' => $this->faker->name,
            'password' => Setting::USER_PASS,
            'password_confirmation' => Setting::USER_PASS,
        ]);
        $response->assertStatus(Response::HTTP_CREATED)->assertJsonStructure([
            'data' => ['id', 'name', 'email', 'is_active', 'created_at', 'updated_at']
        ]);
    }

    /**
     * register test fail
     * @param $data
     * @dataProvider badLoginData
     */
    public function testRegisterFailBadData(array $data)
    {
        $response = $this->postJson(route('register'), $data);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonStructure([
            'error' => ['message', 'errors']
        ]);
    }


    /**
     * bad register data provider
     * @return array[]
     */
    public function badRegisterData()
    {
        return [
            [
                [
                    'email' => $this->faker->unique()->email,
                    'name' => $this->faker->name,
                    'password' => 'password',
                    'password_confirmation' => 'not match',
                ],
                [
                    'email' => 'bedemail.com',
                    'name' => $this->faker->name,
                    'password' => Setting::USER_PASS,
                    'password_confirmation' => Setting::USER_PASS,
                ],
                []
            ]
        ];
    }
}
