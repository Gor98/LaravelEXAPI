<?php


namespace App\Modules\Auth\Controllers;

use App\Common\Bases\Controller;
use App\Common\Tools\APIResponse;
use App\Modules\Auth\Actions\UserAction;
use App\Modules\Auth\Requests\UserRequest;
use App\Modules\Auth\Resource\User\UserCollection;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
