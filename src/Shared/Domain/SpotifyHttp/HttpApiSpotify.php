<?php

namespace Project\Shared\Domain\SpotifyHttp;


interface HttpApiSpotify
{
    public function getToken(): string;

    public function get(string $url, string $spotifyToken): array;

}
