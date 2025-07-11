<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
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
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse|RedirectResponse|Response
    {
        $response = parent::render($request, $e);

        if (app()->environment(['local', 'testing'])) {
            return $response;
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode >= 400) {
            return Inertia::render('Error', [
                'status' => $statusCode,
            ])
                ->toResponse($request)
                ->setStatusCode($statusCode);
        }

        return $response;
    }
}
