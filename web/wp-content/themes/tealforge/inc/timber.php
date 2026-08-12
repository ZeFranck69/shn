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
        'footer_accommodations' => has_nav_menu('footer_accommodations') ? Timber\Timber::get_menu('footer_accommodations') : null,
        'footer_experiences' => has_nav_menu('footer_experiences') ? Timber\Timber::get_menu('footer_experiences') : null,
        'footer_group' => has_nav_menu('footer_group') ? Timber\Timber::get_menu('footer_group') : null,
        'footer_legal' => has_nav_menu('footer_legal') ? Timber\Timber::get_menu('footer_legal') : null,
    ];

    $context['options'] = tealforge_get_site_options();
    $context['current_language'] = tealforge_current_language();
    $context['language_home_url'] = function_exists('pll_home_url') ? pll_home_url($context['current_language']) : home_url('/');
    $context['language_switcher'] = function_exists('pll_the_languages')
        ? pll_the_languages([
            'raw' => 1,
            'hide_current' => 0,
            'hide_if_empty' => 0,
            'show_flags' => 0,
            'show_names' => 0,
        ])
        : [];

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
        'footer_credit_link' => tealforge_prepare_link_field(get_field('footer_credit_link', 'option')),
        'footer_legal_notice_link' => tealforge_prepare_link_field(get_field('footer_legal_notice_link', 'option')),
        'footer_privacy_link' => tealforge_prepare_link_field(get_field('footer_privacy_link', 'option')),
        'footer_cookies_link' => tealforge_prepare_link_field(get_field('footer_cookies_link', 'option')),
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
