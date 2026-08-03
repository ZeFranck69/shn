<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();
$context['post'] = Timber\Timber::get_post();
$context['sections'] = tealforge_prepare_page_sections($context['post']->ID ?? 0);

tealforge_render('pages/front-page.twig', $context);
