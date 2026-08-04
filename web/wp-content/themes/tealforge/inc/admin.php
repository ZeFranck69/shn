<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_get_admin_logo(): ?array
{
    if (! function_exists('get_field')) {
        return null;
    }

    $logo = get_field('logo_header_dark', 'option')
        ?: get_field('logo_header_light', 'option')
        ?: get_field('logo_footer', 'option');

    return tealforge_prepare_image_value($logo);
}

function tealforge_admin_styles(): void
{
    $logo = tealforge_get_admin_logo();

    ?>
    <style id="tealforge-admin-styles">
        :root {
            --tf-admin-ocean: #033744;
            --tf-admin-vermeil: #e55938;
            --tf-admin-lagon: #84d1bf;
            --tf-admin-sable: #f4ece9;
        }

        #adminmenu,
        #adminmenu .wp-submenu,
        #adminmenuback,
        #adminmenuwrap {
            background: var(--tf-admin-ocean);
        }

        #adminmenu a,
        #adminmenu div.wp-menu-image::before {
            color: rgba(244, 236, 233, 0.78);
        }

        #adminmenu li.menu-top:hover,
        #adminmenu li.opensub > a.menu-top,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu,
        #adminmenu li.current a.menu-top,
        #adminmenu .wp-submenu a:focus,
        #adminmenu .wp-submenu a:hover,
        #adminmenu a:hover {
            background: #022b36;
            color: #ffffff;
        }

        #adminmenu li.wp-has-current-submenu .wp-menu-image::before,
        #adminmenu li.current .wp-menu-image::before,
        #adminmenu li:hover .wp-menu-image::before {
            color: var(--tf-admin-lagon);
        }

        #wpadminbar {
            background: #022b36;
        }

        .wp-core-ui .button-primary {
            border-color: var(--tf-admin-vermeil);
            background: var(--tf-admin-vermeil);
        }

        .wp-core-ui .button-primary:hover,
        .wp-core-ui .button-primary:focus {
            border-color: #c9482a;
            background: #c9482a;
        }

        .wrap h1,
        .acf-postbox > .postbox-header .hndle,
        .acf-field .acf-label label {
            color: var(--tf-admin-ocean);
        }

        #adminmenu .tf-admin-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 82px;
            margin: 0;
            padding: 16px 12px 18px;
            border-bottom: 1px solid rgba(132, 209, 191, 0.22);
            background: #022b36;
            box-sizing: border-box;
        }

        #adminmenu .tf-admin-brand a {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: transparent;
        }

        #adminmenu .tf-admin-brand a:hover,
        #adminmenu .tf-admin-brand a:focus {
            background: transparent;
        }

        #adminmenu .tf-admin-brand img {
            display: block;
            width: 136px;
            max-width: 100%;
            max-height: 54px;
            object-fit: contain;
        }

        @media (max-width: 960px) {
            #adminmenu .tf-admin-brand {
                display: none;
            }
        }
    </style>
    <?php if ($logo && ! empty($logo['url'])) : ?>
        <script id="tealforge-admin-brand-script">
            document.addEventListener('DOMContentLoaded', function () {
                var menu = document.getElementById('adminmenu');

                if (!menu || menu.querySelector('.tf-admin-brand')) {
                    return;
                }

                var item = document.createElement('li');
                item.className = 'tf-admin-brand';
                item.innerHTML = '<a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr__('Voir le site', 'tealforge'); ?>"><img src="<?php echo esc_url((string) $logo['url']); ?>" alt="<?php echo esc_attr((string) ($logo['alt'] ?: get_bloginfo('name'))); ?>"></a>';
                menu.insertBefore(item, menu.firstElementChild);
            });
        </script>
    <?php endif; ?>
    <?php
}

add_action('admin_head', 'tealforge_admin_styles');
