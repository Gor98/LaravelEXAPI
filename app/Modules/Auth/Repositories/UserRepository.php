<?php


namespace App\Modules\Auth\Repositories;



use App\Common\Bases\Repository;
use App\Modules\Auth\Entities\User;

class UserRepository extends Repository
{

    protected function model(): string
    {
        return User::class;
    }

}
