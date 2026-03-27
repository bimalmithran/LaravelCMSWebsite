<?php

namespace App\Services;

use App\Http\ApiClientInterface;

class HeaderService
{
    public function __construct(private readonly ApiClientInterface $api) {}

    /**
     * Build the dataset needed to render the header <head> and logo.
     *
     * @return array{
     *   logo_url: string,
     *   favicon_url: string,
     *   site_name: string,
     *   meta_description: string,
     * }
     */
    public function getHeaderData(): array
    {
        $settings = $this->api->get('/settings/global') ?? [];

        return [
            'logo_url'         => $this->api->resolveUrl($settings['site_logo'] ?? null),
            'favicon_url'      => $this->api->resolveUrl($settings['site_favicon'] ?? null),
            'site_name'        => (string) ($settings['site_name'] ?? ''),
            'meta_description' => (string) ($settings['default_meta_description'] ?? ''),
            'contact_phone'    => (string) ($settings['contact_phone'] ?? ''),
        ];
    }
}
