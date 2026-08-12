<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$context = tealforge_get_context();
$context['post'] = Timber\Timber::get_post();
$context['entry'] = $context['post'] ? tealforge_prepare_single_content_post($context['post']->ID, 'hebergement') : null;

tealforge_render('pages/single-content-post.twig', $context);
