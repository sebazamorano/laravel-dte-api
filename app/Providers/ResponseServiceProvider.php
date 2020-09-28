<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    protected function registerResponseMacros()
    {
        $response = app(ResponseFactory::class);
        $response->macro('success', function ($data, $message = '') use ($response) {
            return $response->json([
                'message' => $message,
                'errors' => false,
                'data' => $data
            ]);
        });
        $response->macro('error', function ($message, $status = 422, $additional_info = []) use ($response) {
            return $response->json([
                'message' => $status . ' error',
                'errors' => [
                    'message' => $message,
                    'info' => $additional_info,
                ],
                'status_code' => $status
            ], $status);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResponseMacros();
        //
    }
}
