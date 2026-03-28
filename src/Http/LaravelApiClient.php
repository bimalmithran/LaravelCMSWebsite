<?php
namespace App\Http;

class LaravelApiClient implements ApiClientInterface
{
    private string $baseUrl;
    private string $apiKey;
    private string $publicBaseUrl;

    public function __construct(string $baseUrl, string $apiKey, string $publicBaseUrl = "")
    {
        $this->baseUrl = rtrim($baseUrl, "/");
        $this->apiKey = $apiKey;
        $this->publicBaseUrl = rtrim($publicBaseUrl, "/");
    }

    public function get(string $endpoint, array $queryParams = []): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        if (!empty($queryParams)) {
            $url .= "?" . http_build_query($queryParams);
        }
        $response = $this->executeRequest("GET", $url);
        return $this->respondGetData($response);
    }

    public function post(string $endpoint, array $data = []): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        return $this->executeRequest("POST", $url, $data);
    }

    /**
     * POST to an authenticated endpoint using a customer Bearer token.
     * Returns the full decoded response array (not just ->data).
     */
    public function postWithAuth(string $endpoint, array $data, string $token): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        return $this->executeRequest("POST", $url, $data, $token);
    }

    public function getWithAuth(string $endpoint, array $queryParams, string $token): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        if (!empty($queryParams)) {
            $url .= "?" . http_build_query($queryParams);
        }
        $response = $this->executeRequest("GET", $url, [], $token);
        return $this->respondGetData($response);
    }

    public function putWithAuth(string $endpoint, array $data, string $token): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        return $this->executeRequest("PUT", $url, $data, $token);
    }

    public function deleteWithAuth(string $endpoint, array $data, string $token): ?array
    {
        $url = $this->baseUrl . "/" . ltrim($endpoint, "/");
        return $this->executeRequest("DELETE", $url, $data, $token);
    }

    public function resolveUrl(?string $path): string
    {
        if ($path === null) {
            return "";
        }

        $path = trim($path);

        if ($path === "") {
            return "";
        }

        if (str_starts_with($path, "//") || str_starts_with($path, "assets/")) {
            return $path;
        }

        // Absolute URL: if public_base_url is configured, replace the stored host
        // with the correct public host. This handles existing DB records that were
        // saved with a localhost or incorrect APP_URL baked in.
        if (preg_match('/^https?:\/\//i', $path) === 1) {
            if ($this->publicBaseUrl !== "") {
                $parsed = parse_url($path);
                $urlPath = $parsed["path"] ?? "/";
                if (isset($parsed["query"])) {
                    $urlPath .= "?" . $parsed["query"];
                }
                return rtrim($this->publicBaseUrl, "/") . $urlPath;
            }
            return $path;
        }

        return $this->getOriginUrl() . "/" . ltrim($path, "/");
    }

    private function executeRequest(
        string $method,
        string $url,
        array $data = [],
        ?string $bearerToken = null,
    ): ?array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
            "X-Storefront-Key: " . $this->apiKey,
        ];

        if ($bearerToken !== null) {
            $headers[] = "Authorization: Bearer " . $bearerToken;
        }

        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response) {
            return null;
        }
        $decoded = json_decode($response, true);
        // For GET-style callers that use respondGetData, a 4xx decoded response
        // with success:false is handled there. For POST/PUT/DELETE callers the
        // actual error payload (message, errors) is returned so they can surface it.
        if ($httpCode >= 500 && $decoded === null) {
            return null;
        }
        return $decoded;
    }

    /**
     * Responds with data from the Get API response.
     *
     * @param array $response The API response.
     * @return array The data from the API response.
     */
    private function respondGetData($response): array
    {
        if ($response === null) {
            return [];
        }

        if ($response["success"] === false) {
            return [];
        }

        if (!isset($response["data"]) || !is_array($response["data"])) {
            return [];
        }

        return $response["data"];
    }

    private function getOriginUrl(): string
    {
        if ($this->publicBaseUrl !== "") {
            return $this->publicBaseUrl;
        }

        $parts = parse_url($this->baseUrl);

        if ($parts === false || !isset($parts["scheme"], $parts["host"])) {
            return rtrim($this->baseUrl, "/");
        }

        $origin = $parts["scheme"] . "://" . $parts["host"];

        if (isset($parts["port"])) {
            $origin .= ":" . $parts["port"];
        }

        return $origin;
    }
}
