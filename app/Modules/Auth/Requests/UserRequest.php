<?php


namespace App\Modules\Auth\Requests;

use App\Common\Bases\Request;
use App\Common\Tools\Setting;

/**
 * Class RegisterRequest
 * @package App\Modules\Auth\Requests
 */
class UserRequest extends Request
{
    const ORDERABLE = [
        'id', 'email', 'name', 'created_at'
    ];

    /**
     * @return string[]
     */
    protected function postRules(): array
    {
        return [];
    }

    /**
     * @return string[]
     */
    protected function putRules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email'.$this->user->id,
            'password' => 'sometimes|confirmed|string|min:6',
        ];
    }

    /**
     * @return string[]
     */
    protected function getRules(): array
    {
        return [
            'perPage' => "sometimes|integer",
            'search' => "sometimes|integer",
            'page' => "sometimes|integer",
            'orderType' => "sometimes|in:".implode(",", Setting::ORDERS),
            'orderBy' => "sometimes|in:".implode(",", self::ORDERABLE)
        ];
    }
}
