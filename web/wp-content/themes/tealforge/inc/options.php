<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_register_acf_options_page(): void
{
    if (! function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page([
        'page_title' => 'Options du site',
        'menu_title' => 'Options du site',
        'menu_slug' => 'tealforge-site-options',
        'capability' => 'edit_posts',
        'redirect' => false,
        'position' => 59,
    ]);
}

add_action('acf/init', 'tealforge_register_acf_options_page');
