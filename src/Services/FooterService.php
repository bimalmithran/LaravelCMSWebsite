<?php

namespace App\Services;

use App\Http\ApiClientInterface;

class FooterService
{
    public function __construct(private readonly ApiClientInterface $api) {}

    /**
     * Build the complete dataset needed to render the footer.
     *
     * @return array{
     *   logo_url: string,
     *   site_name: string,
     *   site_description: string,
     *   contact_email: string,
     *   contact_phone: string,
     *   contact_address: string,
     *   social: array{facebook: string, twitter: string, instagram: string},
     *   footer_product_links: list<array{name: string, url: string|null}>,
     *   footer_bottom_links: list<array{name: string, url: string|null}>,
     * }
     */
    public function getFooterData(): array
    {
        $settings = $this->api->get('/settings/global') ?? [];
        $menus    = $this->api->get('/menus', ['placement' => 'footer']) ?? [];

        return [
            'logo_url'          => $this->api->resolveUrl($settings['site_logo'] ?? null),
            'site_name'         => (string) ($settings['site_name'] ?? ''),
            'site_description'  => (string) ($settings['site_description'] ?? ''),
            'contact_email'     => (string) ($settings['contact_email'] ?? ''),
            'contact_phone'     => (string) ($settings['contact_phone'] ?? ''),
            'contact_address'   => (string) ($settings['contact_address'] ?? ''),
            'social'            => [
                'facebook'  => (string) ($settings['social_facebook'] ?? ''),
                'twitter'   => (string) ($settings['social_twitter'] ?? ''),
                'instagram' => (string) ($settings['social_instagram'] ?? ''),
            ],
            'footer_product_links' => $this->resolveMenuLinks($menus, 'footer-product'),
            'footer_policies_links'=> $this->resolveMenuLinks($menus, 'footer-policies'),
            'footer_bottom_links'  => $this->resolveMenuLinks($menus, 'footer-bottom'),
        ];
    }

    /**
     * Find a root-level menu by slug and return its children as flat link items.
     *
     * @param  array<int, array<string, mixed>>  $menus
     * @param  string  $slug
     * @return list<array{name: string, url: string|null}>
     */
    private function resolveMenuLinks(array $menus, string $slug): array
    {
        foreach ($menus as $menu) {
            if (($menu['slug'] ?? '') !== $slug) {
                continue;
            }

            return array_map(
                fn (array $child): array => [
                    'name' => (string) ($child['name'] ?? ''),
                    'url'  => $child['url'] ?? null,
                ],
                array_values($menu['children'] ?? []),
            );
        }

        return [];
    }
}
