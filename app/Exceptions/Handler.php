<?php

namespace App\Exceptions;

use Exception;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
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
        return parent::render($request, $exception);
        $str = $request->header('accept');
        $arr = explode(',', $str);
        if (isset($arr[0]) && $arr[0] == "application/json") return parent::render($request, $exception);
        return response()->view('errors.404');
        if ($exception instanceof CustomException) {
            // return response()->view('errors.custom');
            switch ($exception->getStatusCode()) {
                // not found
                case '404':
                        return $this->renderHttpException($exception); 
                    break;
                case '422': return $this->renderHttpException($exception); 
                        break;
                // internal server error
                case '500':
                    return response()->view('errors.custom');   
                    break;
                default:
                    return response()->view('errors.custom');
                    break;
            }
        }
        return parent::render($request, $exception);
        // return response()->view('errors.custom');
        // if($this->isHttpException($exception))
        // {
        //   //  dd('2');
        //   echo $exception->getStatusCode();
        //   die();
        //     switch ($exception->getStatusCode()) {
        //         // not found
        //         case '404':
        //              return $this->renderHttpException($exception); 
        //             break;
        //         case '422': return $this->renderHttpException($exception); 
        //                 break;
        //         // internal server error
        //         case '500':
        //             return response()->view('errors.custom');   
        //             break;
        //         default:
        //             return response()->view('errors.custom');
        //             break;
        //     }
        // } else
        // {
        //     return parent::render($request, $exception);
        // }
    }
}
