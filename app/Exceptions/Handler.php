<?php

namespace App\Exceptions;

use App\APIError;
use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (app()->environment('production')) {
            if ($this->isHttpException($exception)) {
                $error = new APIError;
                $error->setStatus($exception->getStatusCode());
                $error->setMessage($exception->getMessage());

                if (!$exception->getMessage()) {
                    if ($exception->getStatusCode() == 404) {
                        $error->setMessage("Not found !");
                    } elseif ($exception->getStatusCode() == 500) {
                        $error->setMessage("Internal server error !");
                    } elseif ($exception->getStatusCode() == 403) {
                        $error->setMessage("Access denied.");
                    }
                }

                return response()->json($error, $exception->getStatusCode());
            }
        }

        return parent::render($request, $exception);
    }


    /**
     * Handle 401 exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            $error = new APIError;
            $error->setStatus("401");
            $error->setCode("AUTH_00");
            $error->setMessage("Unauthenticated.");
            return response()->json($error, 401);
        }

        return redirect()->guest(url('/'));
    }
}
