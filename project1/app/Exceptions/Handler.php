<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $response = new Response;
        $data = [
            'success' => false,
            'message' => $exception->getMessage(),
        ];

        // Custom logic to handle specific exceptions
        if ($exception instanceof ValidationException) {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
            $data['errors'] = $exception->errors();

        } else if ($exception instanceof AuthenticationException)
            $response->setStatusCode(Response::HTTP_UNAUTHORIZED);

        else
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);


        $response->setContent($data);

        return $response;
        // return parent::render($request, $exception);
    }
}
