<?php


namespace App\Modules\Auth\Controllers;

use App\Common\Bases\Controller;
use App\Common\Exceptions\RepositoryException;
use App\Common\Tools\APIResponse;
use App\Modules\Auth\Actions\AuthAction;
use App\Modules\Auth\Requests\AuthRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Auth\Resource\Auth as AuthResource;
use App\Modules\Auth\Resource\User\User as UserResource;

/**
 * Class AuthController
 * @package App\Modules\Auth\Controllers
 */
class AuthController extends Controller
{
    /**
     * @var AuthAction
     */
    private AuthAction $authAction;

    /**
     * AuthController constructor.
     * @param AuthAction $authAction
     */
    public function __construct(AuthAction $authAction)
    {
        $this->authAction = $authAction;
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        return APIResponse::successResponse(
            new AuthResource($this->authAction->loginUser($request)),
            Response::HTTP_OK
        );
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        $this->authAction->logoutUser();
        return APIResponse::noContentResponse(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        return APIResponse::successResponse(
            new UserResource($this->authAction->makeUser($request)),
            Response::HTTP_CREATED
        );
    }
}
