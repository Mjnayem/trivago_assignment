<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
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
        \Illuminate\Validation\ValidationException::class,
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {

            parent::report($exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

        $response['status'] = 'error';
        $response['content-type'] = 'application/json';
        $response['message'] = 'Unauthorized';
        $response['date'] = date('Y-m-d H:i:s');

        return response()->json($response, 200);
    }



    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //instance of ValidationException
        if($exception instanceof  ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'data' => [],
                    'message' => $exception->validator->getMessageBag()
                ], 422);
            }
        }

        return parent::render($request, $exception);
    }
}
