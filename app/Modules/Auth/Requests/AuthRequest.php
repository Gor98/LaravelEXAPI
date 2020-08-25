<?php


namespace App\Modules\Auth\Requests;

use App\Common\Bases\Request;

/**
 * Class AuthRequest
 * @package App\Modules\Auth\Requests
 */
class AuthRequest extends Request
{

    /**
     * @return string[]
     */
    protected function postRules(): array
    {
        return [
            'email' => 'required|email|exists:users,email|max:255',
            'password' => 'required|string|min:6'
        ];
    }
}
