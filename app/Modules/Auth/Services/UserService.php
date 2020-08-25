<?php


namespace App\Modules\Auth\Services;

use App\Common\Bases\Service;
use App\Common\Exceptions\RepositoryException;
use App\Modules\Auth\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\UnauthorizedException;

/**
 * Class UserService
 * @package App\Modules\Auth\Services
 */
class UserService extends Service
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return Model
     * @throws RepositoryException
     */
    public function create(array $data): Model
    {
        return $this->userRepository->create($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        if (!$token = auth()->attempt($data)) {
            throw new UnauthorizedException();
        }

        return [
            'token_type' => 'bearer',
            'access_token' => $token,
            'expires_in' => toDate(auth()->factory()->getTTL()),
        ];
    }

    /**
     * logout
     */
    public function logout(): void
    {
        auth()->logout();
    }
}
