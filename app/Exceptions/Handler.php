<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use DB;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        DB::rollback();
        $response = [
            'errors' => 'Sorry, something went wrong.'
        ];

        $response['exception'] = get_class($e); // Reflection might be better here
        $response['message'] = $e->getMessage();
        $response['code'] = $e->getCode();

        // Default response of 400
        $status = 400;

        if(method_exists($e,"getStatusCode")) {
            $status = $e->getStatusCode();
            if($status == 404) {
                return response()->json(["message" => "Not Found"],$status);
            }
        }
        

        // Return a JSON response with the response array and status code
        //return response()->json($response, $status);
        //println($response);
        return parent::render($request,$e);
    }
}
