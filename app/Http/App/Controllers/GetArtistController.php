<?php

namespace App\Http\App\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class GetArtistController
{
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([]);
    }
}
