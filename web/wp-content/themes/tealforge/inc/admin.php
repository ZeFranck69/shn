<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

const TEALFORGE_ADMIN_LOGO_URL = 'https://ledomainedupacifique.tealforge.dsp.nc/wp-content/uploads/2026/08/Le-Domaine-du-Pacifique_logo_Logo-primaire_horizontal_blanc-scaled.webp';
const TEALFORGE_LOGIN_LOGO_URL = 'https://ledomainedupacifique.tealforge.dsp.nc/wp-content/uploads/2026/08/Le-Domaine-du-Pacifique_logo_Logo-primaire_vertical_sable-scaled.webp';

function tealforge_get_admin_logo(): ?array
{
    return [
        'url' => TEALFORGE_ADMIN_LOGO_URL,
        'alt' => 'Le Domaine du Pacifique',
    ];
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

function tealforge_classic_editor_post_types(): array
{
    return ['page', 'hebergement', 'experience'];
}

function tealforge_disable_block_editor_for_post_type(bool $use_block_editor, string $post_type): bool
{
    if (in_array($post_type, tealforge_classic_editor_post_types(), true)) {
        return false;
    }

    return $use_block_editor;
}

add_filter('use_block_editor_for_post_type', 'tealforge_disable_block_editor_for_post_type', 10, 2);

function tealforge_disable_block_editor_for_post(bool $use_block_editor, WP_Post $post): bool
{
    if (in_array($post->post_type, tealforge_classic_editor_post_types(), true)) {
        return false;
    }

    return $use_block_editor;
}

add_filter('use_block_editor_for_post', 'tealforge_disable_block_editor_for_post', 10, 2);

function tealforge_login_styles(): void
{
    ?>
    <style id="tealforge-login-styles">
        :root {
            --tf-login-ocean: #033744;
            --tf-login-ocean-dark: #022b36;
            --tf-login-vermeil: #e55938;
            --tf-login-lagon: #84d1bf;
            --tf-login-sable: #f4ece9;
            --tf-login-white: #ffffff;
        }

        body.login {
            min-height: 100vh;
            background:
                radial-gradient(120% 90% at 20% 10%, rgba(132, 209, 191, 0.18) 0%, rgba(132, 209, 191, 0) 55%),
                linear-gradient(155deg, #06485a 0%, var(--tf-login-ocean) 56%, var(--tf-login-ocean-dark) 100%);
            color: var(--tf-login-sable);
            font-family: "Nunito Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .login #login {
            width: min(420px, calc(100% - 40px));
            padding-top: clamp(52px, 8vh, 90px);
        }

        .login h1 a {
            width: 240px;
            height: 178px;
            margin-bottom: 28px;
            background-image: url("<?php echo esc_url(TEALFORGE_LOGIN_LOGO_URL); ?>");
            background-position: center;
            background-size: contain;
        }

        .login form {
            border: 1px solid rgba(132, 209, 191, 0.22);
            border-radius: 0;
            background: rgba(244, 236, 233, 0.96);
            box-shadow: 0 24px 70px rgba(2, 43, 54, 0.28);
            color: var(--tf-login-ocean);
        }

        .login label {
            color: rgba(3, 55, 68, 0.72);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        .login form .input,
        .login input[type="text"] {
            border: 1px solid rgba(3, 55, 68, 0.16);
            border-radius: 0;
            background: var(--tf-login-white);
            color: var(--tf-login-ocean);
            font-size: 1rem;
            box-shadow: none;
        }

        .login form .input:focus,
        .login input[type="text"]:focus {
            border-color: var(--tf-login-lagon);
            box-shadow: 0 0 0 3px rgba(132, 209, 191, 0.24);
        }

        .wp-core-ui.login .button-primary {
            border-color: var(--tf-login-vermeil);
            border-radius: 0;
            background: var(--tf-login-vermeil);
            color: var(--tf-login-white);
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            transition: background 0.25s ease, border-color 0.25s ease, transform 0.25s ease;
        }

        .wp-core-ui.login .button-primary:hover,
        .wp-core-ui.login .button-primary:focus {
            border-color: #c9482a;
            background: #c9482a;
            transform: translateY(-1px);
        }

        .login #backtoblog a,
        .login #nav a,
        .login .privacy-policy-page-link a {
            color: rgba(244, 236, 233, 0.78);
            text-decoration: none;
        }

        .login #backtoblog a:hover,
        .login #nav a:hover,
        .login .privacy-policy-page-link a:hover {
            color: var(--tf-login-lagon);
        }

        .login .message,
        .login .notice,
        .login .success {
            border-left-color: var(--tf-login-lagon);
        }
    </style>
    <?php
}

add_action('login_head', 'tealforge_login_styles');

function tealforge_login_logo_url(): string
{
    return home_url('/');
}

add_filter('login_headerurl', 'tealforge_login_logo_url');
