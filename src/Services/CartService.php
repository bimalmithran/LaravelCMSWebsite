<?php

namespace App\Services;

use App\Http\LaravelApiClient;

class CartService
{
    public function __construct(private LaravelApiClient $client) {}

    public function getCart(string $token): ?array
    {
        return $this->client->getWithAuth('cart', [], $token);
    }

    public function addItem(string $token, int $productId, int $quantity): ?array
    {
        return $this->client->postWithAuth('cart/add', [
            'product_id' => $productId,
            'quantity'   => $quantity,
        ], $token);
    }

    public function updateItem(string $token, int $productId, int $quantity): ?array
    {
        return $this->client->putWithAuth('cart/update', [
            'product_id' => $productId,
            'quantity'   => $quantity,
        ], $token);
    }

    public function removeItem(string $token, int $productId): ?array
    {
        return $this->client->deleteWithAuth('cart/remove', [
            'product_id' => $productId,
        ], $token);
    }

    public function clearCart(string $token): ?array
    {
        return $this->client->deleteWithAuth('cart/clear', [], $token);
    }
}
