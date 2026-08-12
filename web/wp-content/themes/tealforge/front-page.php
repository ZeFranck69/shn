<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();
$context['post'] = Timber\Timber::get_post();
$front_page_id = (int) get_option('page_on_front');

if (function_exists('pll_get_post')) {
    $translated_front_page_id = (int) pll_get_post($front_page_id, tealforge_current_language());

    if ($translated_front_page_id > 0) {
        $front_page_id = $translated_front_page_id;
    }
}

$context['post'] = $front_page_id > 0 ? Timber\Timber::get_post($front_page_id) : $context['post'];
$context['sections'] = tealforge_prepare_page_sections($front_page_id);

tealforge_render('pages/front-page.twig', $context);
