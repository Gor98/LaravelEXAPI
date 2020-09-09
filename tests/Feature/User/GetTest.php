<?php

namespace Tests\Feature\User;

use App\Modules\Auth\Entities\User;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Tests\BaseTest;

/**
 * Class GetTest
 * @package Tests\Feature\User
 */
class GetTest extends BaseTest
{
    /**
     * get users list test success
     */
    public function testUsersListSuccess()
    {
        $this->auth();
        $response = $this->getJson(route('users.index'), $this->headers);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data', 'pagination']);
    }

    /**
     * get users list test fail no ath
     */
    public function testLogInFailBadData()
    {
        $response = $this->getJson(route('users.index'), $this->headers);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * get users list test filtering
     * @dataProvider userFilters
     * @param array $data
     */
    public function testUsersListSuccessFiltered(array $data)
    {
        $this->auth();
        $response = $this->getJson(route('users.index', [$data['scope'] => $data['value']]), $this->headers);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data', 'pagination']);
    }

    /**
     * bad login data provider
     * @return array[]
     */
    public function userFilters()
    {
        return [
           [
               [ 'scope' => 'IsActive', 'value' => 'IsActive'],
               [ 'scope' => 'page', 'value' => 2],
               [ 'scope' => 'perPage', 'value' => 2],
               [ 'scope' => 'oderBy', 'value' => 'id'],
               [ 'scope' => 'search', 'value' => 'thing'],
           ]
        ];
    }

    /**
     * get single user test success
     */
    public function testUserSingleSuccess()
    {
        $this->auth();
        $user = factory(User::class)->create();
        $response = $this->getJson(route('users.index').'/'.$user->id, $this->headers);
        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(['data']);
    }

    /**
     * get single user test fail wrong id
     * @throws Exception
     */
    public function testUserSingleFailWrongId()
    {
        $this->auth();
        $response = $this->getJson(route('users.index').'/'.random_int(9999, 999999), $this->headers);
        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure(['error' => ['message', 'status']]);
    }
}
