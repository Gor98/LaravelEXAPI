<?php


namespace App\Modules\Auth\Actions;

use App\Common\Exceptions\RepositoryException;
use App\Modules\Auth\Requests\AuthRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Services\UserService;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AuthAction
 * @package App\Modules\Auth\Actions
 */
class AuthAction
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * AuthAction constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param AuthRequest $request
     * @return object
     */
    public function loginUser(AuthRequest $request): object
    {
        return $this->userService->login($request->only(['email', 'password']));
    }

    /**
     * logout user
     */
    public function logoutUser(): void
    {
        $this->userService->logout();
    }

    /**
     * @param RegisterRequest $request
     * @return Model
     * @throws RepositoryException
     */
    public function makeUser(RegisterRequest $request): Model
    {
        return $this->userService->create($request->all());
    }
}
