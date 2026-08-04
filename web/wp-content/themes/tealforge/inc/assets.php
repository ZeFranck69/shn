<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_get_asset_manifest(): array
{
    static $manifest = null;

    if (null !== $manifest) {
        return $manifest;
    }

    $manifest = [];
    $manifest_path = get_theme_file_path('dist/manifest.json');

    if (! is_readable($manifest_path)) {
        return $manifest;
    }

    $manifest_json = file_get_contents($manifest_path);

    if (false === $manifest_json) {
        return $manifest;
    }

    $decoded_manifest = json_decode($manifest_json, true);

    if (is_array($decoded_manifest)) {
        $manifest = $decoded_manifest;
    }

    return $manifest;
}

function tealforge_enqueue_assets(): void
{
    $entry = tealforge_get_asset_manifest()['assets/scripts/main.js'] ?? null;
    $theme_version = wp_get_theme()->get('Version');

    if (is_array($entry) && ! empty($entry['file'])) {
        if (! empty($entry['css']) && is_array($entry['css'])) {
            foreach ($entry['css'] as $index => $css_file) {
                wp_enqueue_style(
                    'tealforge-main-' . $index,
                    get_theme_file_uri('dist/' . ltrim((string) $css_file, '/')),
                    [],
                    null
                );
            }
        }

        wp_enqueue_script(
            'tealforge-main',
            get_theme_file_uri('dist/' . ltrim((string) $entry['file'], '/')),
            [],
            null,
            true
        );

        return;
    }

    wp_enqueue_style(
        'tealforge-main',
        get_theme_file_uri('assets/styles/main.css'),
        [],
        $theme_version
    );

    wp_enqueue_script(
        'tealforge-main',
        get_theme_file_uri('assets/scripts/app.js'),
        [],
        $theme_version,
        true
    );
}

add_action('wp_enqueue_scripts', 'tealforge_enqueue_assets');

function tealforge_print_wpforms_contact_overrides(): void
{
    ?>
    <style id="tealforge-wpforms-contact-overrides">
        .tf-contact__form .wpforms-container,
        .tf-contact__form .wpforms-form,
        .tf-contact__form .wpforms-field-container,
        .tf-contact__form .wpforms-field {
            width: 100% !important;
            max-width: none !important;
        }

        .tf-contact__form .wpforms-field {
            padding: 0 0 1.5rem !important;
        }

        .tf-contact__form .wpforms-field-label {
            display: block !important;
            margin: 0 0 0.55rem !important;
            color: rgba(3, 55, 68, 0.6) !important;
            font-size: 0.68rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.18em !important;
            line-height: 1.35 !important;
            text-transform: uppercase !important;
        }

        .tf-contact__form .wpforms-field input[type="text"],
        .tf-contact__form .wpforms-field input[type="email"],
        .tf-contact__form .wpforms-field select,
        .tf-contact__form .wpforms-field textarea,
        .tf-contact__form .wpforms-field-medium,
        .tf-contact__form .wpforms-field-large {
            width: 100% !important;
            max-width: none !important;
        }

        .tf-contact__form .wpforms-field input[type="text"],
        .tf-contact__form .wpforms-field input[type="email"],
        .tf-contact__form .wpforms-field select {
            min-height: 52px !important;
        }

        .tf-contact__form .wpforms-field textarea {
            min-height: 130px !important;
        }

        .tf-contact__form .wpforms-submit-container {
            padding: 0 !important;
        }

        .tf-contact__form button[type="submit"] {
            display: inline-flex !important;
            width: auto !important;
            align-items: center !important;
            justify-content: center !important;
            border: 1px solid #e55938 !important;
            padding: 0.95rem 1.9rem !important;
            background: #e55938 !important;
            color: #ffffff !important;
            font: inherit !important;
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.16em !important;
            text-transform: uppercase !important;
            transition: background 0.55s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.55s cubic-bezier(0.22, 1, 0.36, 1), transform 0.55s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.55s cubic-bezier(0.22, 1, 0.36, 1) !important;
        }

        .tf-contact__form button[type="submit"]:hover,
        .tf-contact__form button[type="submit"]:focus {
            border-color: #c9482a !important;
            background: #c9482a !important;
            color: #ffffff !important;
            box-shadow: 0 14px 32px rgba(229, 89, 56, 0.18) !important;
            transform: translateY(-2px) !important;
        }
    </style>
    <?php
}

add_action('wp_footer', 'tealforge_print_wpforms_contact_overrides', 999);
