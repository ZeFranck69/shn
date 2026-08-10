<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();
$context['post'] = Timber\Timber::get_post();

if ($context['post'] && function_exists('get_field')) {
    $section_rows = get_field('page_sections', $context['post']->ID);

    if (is_array($section_rows) && $section_rows) {
        $context['sections'] = tealforge_prepare_page_sections($context['post']->ID);
    }
}

tealforge_render('pages/page.twig', $context);
