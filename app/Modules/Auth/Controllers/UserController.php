<?php


namespace App\Modules\Auth\Controllers;

use App\Common\Bases\Controller;
use App\Common\Tools\APIResponse;
use App\Modules\Auth\Actions\UserAction;
use App\Modules\Auth\Entities\User;
use App\Modules\Auth\Requests\UserRequest;
use App\Modules\Auth\Resource\User\UserCollection;
use App\Modules\Auth\Resource\User\User as UserResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 * @package App\Modules\Auth\Controllers
 */
class UserController extends Controller
{
    /**
     * @var UserAction
     */
    private UserAction $userAction;

    /**
     * UserController constructor.
     * @param UserAction $userAction
     */
    public function __construct(UserAction $userAction)
    {
        $this->userAction = $userAction;
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function index(UserRequest $request): JsonResponse
    {
        return APIResponse::collectionResponse(
            new UserCollection($this->userAction->sortPaginate($request)),
            Response::HTTP_OK
        );
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user)
    {
        return APIResponse::successResponse(
            new UserResource($user),
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        return APIResponse::successResponse(
            new UserResource($this->userAction->update($request, $user)),
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $this->userAction->destroy($user);
        return APIResponse::noContentResponse();
    }
}
