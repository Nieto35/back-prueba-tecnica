<?php

namespace App\Http\App\Controllers;


use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Project\App\Application\Action\GetArtistAction;
use Project\App\Domain\ValueObject\ArtistId;
use Project\Auth\Domain\Exception\InvalidArgumentException;
use Project\Auth\Domain\ValueObject\Token;


class GetArtistController
{
    #[PathParameter('id', required: true, type: 'string', example: "7GQDI5Vmxs92RsIRZzYT11")]
    public function __invoke(string $id, Request $request, GetArtistAction $action): JsonResponse
    {
        try {
            $artistId = new ArtistId($id);
            $authorizationHeader = $request->header('Authorization');
            $token = new Token(str_replace('Bearer ', '', $authorizationHeader));
            $data = $action->execute($artistId, $token);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        return response()->json($data);
    }
}
