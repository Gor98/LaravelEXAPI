<?php


namespace App\Modules\Auth\Services;

use App\Common\Bases\Service;
use App\Common\Exceptions\RepositoryException;
use App\Modules\Auth\Repositories\UserRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\UnauthorizedException;
use phpDocumentor\Reflection\Types\Mixed_;

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
            throw new UnauthorizedException(trans('errors.UnauthorizedHttpException'));
        }
        $this->checkUser();

        return $this->authData($token);
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

    /**
     * @param array $data
     * @param Model|int|array $object
     * @return Model
     */
    public function update(array $data, $object): Model
    {
        return $this->userRepository->update($data, $object);
    }

    /**
     * @param Model|array|int $object
     * @return bool|null
     * @throws Exception
     */
    public function destroy($object)
    {
        return $this->userRepository->delete($object);
    }

    /**
     * @param Model|array|int $object
     * @return Model
     */
    public function show($object): Model
    {
        return $this->userRepository->fetch($object);
    }

    /**
     * @param string $token
     * @return object
     */
    public function authData(string $token): object
    {
        return (object) [
            'token_type' => 'bearer',
            'access_token' => $token,
            'expires_in' => toDate(auth()->factory()->getTTL()),
        ];
    }

    public function checkUser()
    {
        if (is_null(auth()->user()->email_verified_at)) {
            throw new UnauthorizedException(trans('errors.UnauthorizedHttpException'));
        }
    }
}
