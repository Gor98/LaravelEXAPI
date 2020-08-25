<?php


namespace App\Modules\Auth\Services;

use App\Common\Bases\Service;
use App\Common\Exceptions\RepositoryException;
use App\Modules\Auth\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @return object
     */
    public function login(array $data): object
    {
        if (!$token = auth()->attempt($data)) {
            throw new UnauthorizedException();
        }

        return (object) [
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

    /**
     * @param array $filters
     * @param array $meta
     * @return LengthAwarePaginator
     */
    public function sortPaginate(array $filters, array $meta): LengthAwarePaginator
    {
        return $this->userRepository->sortPaginate($filters, $meta);
    }
}
