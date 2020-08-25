<?php


namespace App\Modules\Auth\Repositories;

use App\Common\Bases\Repository;
use App\Modules\Auth\Entities\User;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends Repository
{

    /**
     * @var array|string[]
     */
    protected array $fillable = ['name', 'email', 'password'];

    /**
     * @return string
     */
    protected function model(): string
    {
        return User::class;
    }
}
