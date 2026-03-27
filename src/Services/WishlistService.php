<?php

namespace App\Services;

use App\Http\LaravelApiClient;

class WishlistService
{
    public function __construct(private LaravelApiClient $client) {}

    public function getWishlist(string $token): ?array
    {
        return $this->client->getWithAuth('wishlist', [], $token);
    }

    public function addItem(string $token, int $productId): ?array
    {
        return $this->client->postWithAuth('wishlist/add', [
            'product_id' => $productId,
        ], $token);
    }

    public function removeItem(string $token, int $productId): ?array
    {
        return $this->client->deleteWithAuth('wishlist/remove', [
            'product_id' => $productId,
        ], $token);
    }
}
