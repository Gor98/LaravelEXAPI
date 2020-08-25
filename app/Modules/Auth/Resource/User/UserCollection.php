<?php


namespace App\Modules\Auth\Resource\User;

use App\Common\Tools\Collection;
use Illuminate\Http\Request;

class UserCollection extends Collection
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->makeResponse();
    }
}
