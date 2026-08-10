<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_register_content_post_types(): void
{
    register_post_type('hebergement', [
        'labels' => [
            'name' => __('Hébergements', 'tealforge'),
            'singular_name' => __('Hébergement', 'tealforge'),
            'add_new_item' => __('Ajouter un hébergement', 'tealforge'),
            'edit_item' => __('Modifier l’hébergement', 'tealforge'),
            'new_item' => __('Nouvel hébergement', 'tealforge'),
            'view_item' => __('Voir l’hébergement', 'tealforge'),
            'search_items' => __('Rechercher des hébergements', 'tealforge'),
            'not_found' => __('Aucun hébergement trouvé', 'tealforge'),
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-building',
        'menu_position' => 21,
        'rewrite' => [
            'slug' => 'hebergements',
            'with_front' => false,
        ],
        'show_in_rest' => true,
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);

    register_post_type('experience', [
        'labels' => [
            'name' => __('Expériences', 'tealforge'),
            'singular_name' => __('Expérience', 'tealforge'),
            'add_new_item' => __('Ajouter une expérience', 'tealforge'),
            'edit_item' => __('Modifier l’expérience', 'tealforge'),
            'new_item' => __('Nouvelle expérience', 'tealforge'),
            'view_item' => __('Voir l’expérience', 'tealforge'),
            'search_items' => __('Rechercher des expériences', 'tealforge'),
            'not_found' => __('Aucune expérience trouvée', 'tealforge'),
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-palmtree',
        'menu_position' => 22,
        'rewrite' => [
            'slug' => 'experiences',
            'with_front' => false,
        ],
        'show_in_rest' => true,
        'supports' => ['title', 'thumbnail', 'page-attributes'],
    ]);
}

add_action('init', 'tealforge_register_content_post_types');
