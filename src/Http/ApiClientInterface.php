<?php
namespace App\Http;

interface ApiClientInterface 
{
    public function get(string $endpoint, array $queryParams = []): ?array;
    public function post(string $endpoint, array $data = []): ?array;
    public function resolveUrl(?string $path): string;
}
