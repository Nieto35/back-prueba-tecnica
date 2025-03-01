<?php

namespace App\Http\App\Controllers;

use Symfony\Component\HttpFoundation\Response;

class AppController
{
    /**
     * @return Response
     */
    public function index()
    {

        $app_data = [
            'name' => config('app.name'),
            'domain' => config('app.domain'),
            'frontend' => [
                'discovery' => config('frontend.discovery')
            ]
        ];

        return response()->view('app', [
            'app_data' => $app_data
        ]);
    }

}
