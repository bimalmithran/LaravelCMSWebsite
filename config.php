<?php
return [
    // Internal URL used by PHP (server-to-server). Can be localhost if both
    // frontend and backend run on the same machine.
    "api_base_url"      => "http://localhost:8000/api/v1",

    // Public-facing base URL of the backend (e.g. "https://api.example.com").
    // Used for image/asset URLs in HTML and for browser-side AJAX calls.
    // Leave empty to derive it automatically from api_base_url (only works
    // when the backend is publicly accessible at that same URL).
    "public_base_url"   => "",

    "api_key"           => "your_storefront_api_key_here",
    "currency_symbol"   => "₹",

    // Set your Google OAuth Client ID here to enable "Sign in with Google".
    // Create credentials at https://console.cloud.google.com/
    // Leave empty string "" to disable the Google sign-in button.
    "google_client_id"  => "",
];
