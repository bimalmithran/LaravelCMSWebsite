<?php
namespace App\Http;

class LaravelApiClient implements ApiClientInterface
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = rtrim($baseUrl, "/");
        $this->apiKey = $apiKey;
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

    public function resolveUrl(?string $path): string
    {
        if ($path === null) {
            return "";
        }

        $path = trim($path);

        if ($path === "") {
            return "";
        }

        if (
            preg_match('/^https?:\/\//i', $path) === 1 ||
            str_starts_with($path, "//") ||
            str_starts_with($path, "assets/")
        ) {
            return $path;
        }

        return $this->getOriginUrl() . "/" . ltrim($path, "/");
    }

    private function executeRequest(
        string $method,
        string $url,
        array $data = [],
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

        if ($method === "POST" && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400 || !$response) {
            return null;
        }
        return json_decode($response, true);
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
