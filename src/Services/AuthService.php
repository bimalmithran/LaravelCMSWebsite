<?php

namespace App\Services;

use App\Http\LaravelApiClient;

/**
 * Wraps the backend customer auth API endpoints.
 *
 * All methods return an array with at least:
 *   ['success' => bool, 'message' => string, 'data' => [...]]
 * or null on network/server error.
 */
class AuthService
{
    public function __construct(private LaravelApiClient $client) {}

    /**
     * Sign in with email or phone number + password.
     */
    public function login(string $identifier, string $password): ?array
    {
        return $this->client->post('auth/login', [
            'identifier' => $identifier,
            'password'   => $password,
        ]);
    }

    /**
     * Create a new customer account.
     */
    public function register(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        string $passwordConfirmation,
        ?string $phone = null
    ): ?array {
        $payload = [
            'first_name'            => $firstName,
            'last_name'             => $lastName,
            'email'                 => $email,
            'password'              => $password,
            'password_confirmation' => $passwordConfirmation,
        ];

        if ($phone !== null && $phone !== '') {
            $payload['phone'] = $phone;
        }

        return $this->client->post('auth/register', $payload);
    }

    /**
     * Sign in / register via Google ID token (from Google Identity Services).
     */
    public function googleLogin(string $idToken): ?array
    {
        return $this->client->post('auth/google', ['id_token' => $idToken]);
    }

    /**
     * Revoke the stored API token.
     */
    public function logout(string $token): void
    {
        $this->client->postWithAuth('auth/logout', [], $token);
    }
}
