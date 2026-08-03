<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

if (class_exists(Timber\Timber::class)) {
    Timber\Timber::init();
}

function tealforge_get_context(): array
{
    if (! class_exists(Timber\Timber::class)) {
        return [];
    }

    $context = Timber\Timber::context();

    $context['menus'] = [
        'primary' => has_nav_menu('primary') ? Timber\Timber::get_menu('primary') : null,
        'footer' => has_nav_menu('footer') ? Timber\Timber::get_menu('footer') : null,
    ];

    $context['options'] = tealforge_get_site_options();

    return $context;
}

function tealforge_get_site_options(): array
{
    if (! function_exists('get_field')) {
        return [];
    }

    return [
        'logo_header_light' => tealforge_prepare_image_value(get_field('logo_header_light', 'option')),
        'logo_header_dark' => tealforge_prepare_image_value(get_field('logo_header_dark', 'option')),
        'logo_hero' => tealforge_prepare_image_value(get_field('logo_hero', 'option')),
        'logo_footer' => tealforge_prepare_image_value(get_field('logo_footer', 'option')),
    ];
}

function tealforge_render(string $template, array $context = []): void
{
    if (! class_exists(Timber\Timber::class)) {
        wp_die(
            esc_html__(
                'Les dependances Composer du theme Tealforge sont absentes. Lancez composer install dans le theme.',
                'tealforge'
            )
        );
    }

    Timber\Timber::render($template, $context);
}
