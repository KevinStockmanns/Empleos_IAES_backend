<?php

namespace App\Exceptions;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Log;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
            //Log::error($e->getMessage(), [
            //    'exception' => $e
            //]);
        });
    }

    public function render($request, Throwable $exception)
    {
        //Manejo específico para MethodNotAllowedHttpException
        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Método no permitido.',
                'status' => 'fail',
                //'supported_methods' => $exception->getAllowedMethods(),
            ], 405);
        }

        //Manejo específico para NotFoundHttpException
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Ruta no encontrada.',
                'status' => 'fail',
            ], 404);
        }

        if ($exception instanceof ValidationException){
            return response()->json([
                'message'=>'errores de validación',
                'errors'=> $exception->errors(),
            ], 400);
        }

        if($exception instanceof CustomException){
            return response()->json([
                'message'=>'error de logica',
                'errors'=> $exception->getMessage()
            ], $exception->getStatus());
        }

        return response()->json([
            'error' => 'Ocurrió un error inesperado.',
            'status' => 'error',
            'e'=>$exception->getTrace(),
        ], 500);
    }
}
