<?php

namespace App\Exceptions;

use App\Common\Tools\APIResponse;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public final function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request   $request
     * @param  Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public final function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
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

        if ($exception instanceof \Illuminate\Http\Exceptions\HttpResponseException) {
            $exception = $exception->getResponse();
        } else if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        } else if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    private function customApiResponse(Throwable $exception): JsonResponse
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $response = [];

        switch ($statusCode) {
            case Response::HTTP_UNAUTHORIZED:
                $response['message'] = 'Unauthorized';
                break;
            case Response::HTTP_FORBIDDEN:
                $response['message'] = 'Forbidden';
                break;
            case Response::HTTP_NOT_FOUND:
                $response['message'] = 'Not Found';
                break;
            case Response::HTTP_METHOD_NOT_ALLOWED:
                $response['message'] = 'Method Not Allowed';
                break;
            case Response::HTTP_UNPROCESSABLE_ENTITY:
                $response['message'] = $exception->getMessage();
                $response['errors'] =  $exception->errors();
                break;
            default:
                $response['message'] = ($statusCode == Response::HTTP_INTERNAL_SERVER_ERROR)
                    ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $statusCode;

        return APIResponse::errorResponse($response, $statusCode);
    }
}
