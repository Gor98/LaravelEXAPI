<?php

namespace App\Exceptions;

use App\Common\Tools\APIResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  Throwable $exception
     * @throws Exception
     */
    final public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     *
     * @throws Throwable
     */
    final public function render($request, Throwable $exception): JsonResponse
    {
        if ($request->header()) {
            return $this->handleApiException($request, $exception);
        } else {
            return parent::render($request, $exception);
        }
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function handleApiException(Request $request, Throwable $exception): JsonResponse
    {
        $exception = $this->prepareException($exception);

        return $this->customApiResponse($exception);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function customApiResponse(Throwable $exception): JsonResponse
    {
        $exceptionName = get_class($exception);
        switch ($exceptionName) {
            case ValidationException::class:
                $response['message'] = trans("errors.".getClassName($exception));
                $response['errors'] = $exception->errors();
                $response['status'] = Response::HTTP_UNPROCESSABLE_ENTITY;
                break;
            case AuthenticationException::class:
                $response['message'] = trans("errors.".getClassName($exception));
                $response['status'] = Response::HTTP_UNAUTHORIZED;
                break;
            case UnauthorizedException::class:
                $response['message'] = trans("errors.".getClassName($exception));
                $response['status'] = Response::HTTP_FORBIDDEN;
                break;
            case NotFoundHttpException::class:
                $response['message'] = trans("errors.".getClassName($exception));
                $response['status'] = Response::HTTP_NOT_FOUND;
                break;
            case MethodNotAllowedHttpException::class:
                $response['message'] = trans("errors.".getClassName($exception));
                $response['status'] = Response::HTTP_METHOD_NOT_ALLOWED;
                break;
            default:
                $response['message'] =  trans("errors.default");
                $response['status'] = Response::HTTP_INTERNAL_SERVER_ERROR;
                break;
        }


        if (config('app.debug')) {
            $response['message'] = $exception->getMessage();
            $response['code'] = $exception->getCode();
//            $response['trace'] = $exception->getTrace();
        }

        return $this->makeResponse($response, $response['status']);
    }

    /**
     * @param $response
     * @param $statusCode
     * @return JsonResponse
     */
    public function makeResponse($response, $statusCode): JsonResponse
    {
        return APIResponse::errorResponse($response, $statusCode);
    }
}
