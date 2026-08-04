<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

function tealforge_prepare_page_sections(int $post_id): array
{
    $sections = [];

    if (function_exists('get_field') && $post_id > 0) {
        $rows = get_field('page_sections', $post_id);

        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (! is_array($row) || empty($row['acf_fc_layout'])) {
                    continue;
                }

                $section = tealforge_prepare_shn_section($row);

                if ($section) {
                    $sections[] = $section;
                }
            }
        }
    }

    return $sections ?: tealforge_get_default_page_sections();
}

function tealforge_prepare_shn_section(array $row): ?array
{
    $layout = sanitize_key((string) $row['acf_fc_layout']);

    return match ($layout) {
        'hero' => [
            'layout' => 'hero',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'accueil'),
            'background_image' => tealforge_prepare_image_value($row['background_image'] ?? null),
            'hero_image' => tealforge_prepare_image_value($row['hero_image'] ?? null),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'subtitle' => wp_kses_post((string) ($row['subtitle'] ?? '')),
            'buttons' => tealforge_prepare_buttons($row['buttons'] ?? []),
            'scroll_label' => sanitize_text_field((string) ($row['scroll_label'] ?? 'Découvrir')),
            'scroll_target' => esc_url((string) ($row['scroll_target'] ?? '#groupe')),
        ],
        'manifest' => [
            'layout' => 'manifest',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'groupe'),
            'eyebrow' => sanitize_text_field((string) ($row['eyebrow'] ?? '')),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'lead' => wp_kses_post((string) ($row['lead'] ?? '')),
            'content' => wp_kses_post((string) ($row['content'] ?? '')),
        ],
        'destinations' => [
            'layout' => 'destinations',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'destinations'),
            'eyebrow' => sanitize_text_field((string) ($row['eyebrow'] ?? '')),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'intro' => wp_kses_post((string) ($row['intro'] ?? '')),
            'items' => tealforge_prepare_destinations($row['items'] ?? []),
        ],
        'accommodations' => [
            'layout' => 'accommodations',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'hebergements'),
            'eyebrow' => sanitize_text_field((string) ($row['eyebrow'] ?? '')),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'intro' => wp_kses_post((string) ($row['intro'] ?? '')),
            'items' => tealforge_prepare_cards($row['items'] ?? [], 'accommodation'),
        ],
        'quote' => [
            'layout' => 'quote',
            'image' => tealforge_prepare_image_value($row['image'] ?? null),
            'quote' => sanitize_text_field((string) ($row['quote'] ?? '')),
            'attribution' => sanitize_text_field((string) ($row['attribution'] ?? '')),
        ],
        'experiences', 'expériences' => [
            'layout' => 'experiences',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'experiences'),
            'eyebrow' => sanitize_text_field((string) ($row['eyebrow'] ?? '')),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'intro' => wp_kses_post((string) ($row['intro'] ?? '')),
            'items' => tealforge_prepare_cards($row['items'] ?? [], 'experience'),
            'cta' => [
                'title' => sanitize_text_field((string) ($row['cta_title'] ?? '')),
                'text' => wp_kses_post((string) ($row['cta_text'] ?? '')),
                'button_label' => sanitize_text_field((string) ($row['cta_button_label'] ?? '')),
                'button_url' => esc_url((string) ($row['cta_button_url'] ?? '#contact')),
            ],
        ],
        'contact' => [
            'layout' => 'contact',
            'anchor_id' => tealforge_prepare_anchor($row['anchor_id'] ?? 'contact'),
            'eyebrow' => sanitize_text_field((string) ($row['eyebrow'] ?? '')),
            'title' => wp_kses_post((string) ($row['title'] ?? '')),
            'intro' => wp_kses_post((string) ($row['intro'] ?? '')),
            'infos' => tealforge_prepare_contact_infos($row['infos'] ?? []),
            'wpforms_id' => absint($row['wpforms_id'] ?? 0),
        ],
        default => null,
    };
}

function tealforge_prepare_anchor(mixed $value): string
{
    return sanitize_title((string) $value);
}

function tealforge_prepare_buttons(mixed $rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $buttons = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $label = sanitize_text_field((string) ($row['label'] ?? ''));
        $url = esc_url((string) ($row['url'] ?? ''));

        if ('' === $label || '' === $url) {
            continue;
        }

        $buttons[] = [
            'label' => $label,
            'url' => $url,
            'style' => sanitize_key((string) ($row['style'] ?? 'primary')),
            'target_blank' => ! empty($row['target_blank']),
        ];
    }

    return $buttons;
}

function tealforge_prepare_destinations(mixed $rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $destinations = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $destinations[] = [
            'index' => sanitize_text_field((string) ($row['index'] ?? '')),
            'title' => sanitize_text_field((string) ($row['title'] ?? '')),
            'place' => sanitize_text_field((string) ($row['place'] ?? '')),
            'links' => tealforge_prepare_destination_links($row['links'] ?? []),
        ];
    }

    return $destinations;
}

function tealforge_prepare_destination_links(mixed $rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $links = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $label = sanitize_text_field((string) ($row['label'] ?? ''));

        if ('' === $label) {
            continue;
        }

        $type = sanitize_title((string) ($row['type'] ?? 'accommodation'));

        $links[] = [
            'label' => $label,
            'type' => 'experience' === $type ? 'experience' : 'accommodation',
        ];
    }

    return $links;
}

function tealforge_prepare_cards(mixed $rows, string $kind): array
{
    if (! is_array($rows)) {
        return [];
    }

    $cards = [];

    foreach ($rows as $index => $row) {
        if (! is_array($row)) {
            continue;
        }

        $cards[] = [
            'kind' => $kind,
            'image' => tealforge_prepare_image_value($row['image'] ?? null),
            'image_note' => sanitize_text_field((string) ($row['image_note'] ?? '')),
            'place' => sanitize_text_field((string) ($row['place'] ?? '')),
            'title' => sanitize_text_field((string) ($row['title'] ?? '')),
            'endorsement' => sanitize_text_field((string) ($row['endorsement'] ?? '')),
            'description' => wp_kses_post((string) ($row['description'] ?? '')),
            'link_label' => sanitize_text_field((string) ($row['link_label'] ?? '')),
            'link_url' => esc_url((string) ($row['link_url'] ?? '')),
            'target_blank' => ! empty($row['target_blank']),
            'reverse' => 'accommodation' === $kind && 1 === ((int) $index % 2),
        ];
    }

    return $cards;
}

function tealforge_prepare_contact_infos(mixed $rows): array
{
    if (! is_array($rows)) {
        return [];
    }

    $infos = [];

    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $label = sanitize_text_field((string) ($row['label'] ?? ''));
        $value = wp_kses_post((string) ($row['value'] ?? ''));

        if ('' === $label || '' === $value) {
            continue;
        }

        $infos[] = [
            'label' => $label,
            'value' => $value,
        ];
    }

    return $infos;
}

function tealforge_prepare_image_value(mixed $image): ?array
{
    if (is_numeric($image)) {
        $image_id = (int) $image;

        return [
            'id' => $image_id,
            'alt' => (string) get_post_meta($image_id, '_wp_attachment_image_alt', true),
            'url' => (string) wp_get_attachment_image_url($image_id, 'full'),
        ];
    }

    if (is_array($image) && ! empty($image['ID'])) {
        $image_id = (int) $image['ID'];

        return [
            'id' => $image_id,
            'alt' => (string) ($image['alt'] ?? get_post_meta($image_id, '_wp_attachment_image_alt', true)),
            'url' => (string) ($image['url'] ?? wp_get_attachment_image_url($image_id, 'full')),
        ];
    }

    return null;
}

function tealforge_get_default_page_sections(): array
{
    return [
        [
            'layout' => 'hero',
            'anchor_id' => 'accueil',
            'background_image' => null,
            'hero_image' => null,
            'title' => 'Vivre la Nouvelle-Calédonie<br><em>dans toute son intensité</em>',
            'subtitle' => 'Hôtels, restaurants, spas, golfs, casinos et expériences exclusives. Le seul groupe calédonien a reunir hébergement et loisirs premium.',
            'buttons' => [
                ['label' => 'Nos hébergements', 'url' => '#hebergements', 'style' => 'primary', 'target_blank' => false],
                ['label' => 'Nos expériences', 'url' => '#experiences', 'style' => 'light', 'target_blank' => false],
            ],
            'scroll_label' => 'Découvrir',
            'scroll_target' => '#groupe',
        ],
        [
            'layout' => 'manifest',
            'anchor_id' => 'groupe',
            'eyebrow' => 'Le Domaine du Pacifique',
            'title' => 'Découvrez<br>Le Domaine<br>du Pacifique',
            'lead' => "Le Domaine du Pacifique est une invitation a vivre la Nouvelle-Calédonie dans toute son intensité. A travers nos hôtels, restaurants, spas, golfs, casinos et expériences exclusives, nous revelons les plus belles facettes du territoire en proposant des séjours ou confort, decouverte et emotion ne font qu'un.",
            'content' => "<p>Seul groupe neo-calédonien a reunir une offre complete d'hébergements et de loisirs premium, nous imaginons des expériences fluides et sur mesure, guidees par une hospitalité sincere, élégante et attentive. Chaque detail est pense pour offrir a nos hotes une parenthèse d'exception, ou le luxe s'exprime avec simplicite, authenticite et generosite.</p><p>Animes par la passion de notre territoire, nous partageons avec fierte ses paysages, ses cultures et son art de vivre. Plus qu'un séjour, nous creons des expériences immersives qui invitent a explorer, ressentir et conserver des souvenirs durables de la Nouvelle-Calédonie.</p>",
        ],
        [
            'layout' => 'destinations',
            'anchor_id' => 'destinations',
            'eyebrow' => 'Un même Domaine, trois territoires',
            'title' => 'Où que vous alliez, vous êtes chez nous',
            'intro' => "Nos établissements et nos activités se répondent d'un bout à l'autre du territoire. Composez votre séjour d'une destination à l'autre, avec le même niveau d'attention partout.",
            'items' => tealforge_get_default_destinations(),
        ],
        [
            'layout' => 'accommodations',
            'anchor_id' => 'hebergements',
            'eyebrow' => 'Hébergements',
            'title' => 'Séjournez au cœur des plus belles destinations de Nouvelle-Calédonie',
            'intro' => "Des hôtels d'exception ou confort, élégance et hospitalité se conjuguent pour vous offrir une expérience authentique, au plus près des plus beaux paysages du territoire.",
            'items' => tealforge_get_default_accommodations(),
        ],
        [
            'layout' => 'quote',
            'image' => null,
            'quote' => "Le même niveau d'attention sur chacun de nos sites.",
            'attribution' => 'Notre promesse',
        ],
        [
            'layout' => 'experiences',
            'anchor_id' => 'experiences',
            'eyebrow' => 'Expériences',
            'title' => 'Vivez la Nouvelle-Calédonie autrement',
            'intro' => 'Golf, spa, gastronomie, casino, nature et découvertes : des expériences uniques imaginées pour prolonger chaque séjour et révéler toute la richesse du territoire.',
            'items' => tealforge_get_default_experiences(),
            'cta' => [
                'title' => 'Composez votre séjour',
                'text' => "De la réservation a l'organisation de vos itinéraires, nos équipes construisent avec vous un parcours d'une destination à l'autre.",
                'button_label' => 'Nous écrire',
                'button_url' => '#contact',
            ],
        ],
        [
            'layout' => 'contact',
            'anchor_id' => 'contact',
            'eyebrow' => 'Contact',
            'title' => 'Parlons de votre séjour',
            'intro' => 'Une question sur nos établissements, un projet de groupe ou un séminaire ? Ecrivez-nous, nous vous répondons sous 48 heures ouvrées.',
            'infos' => [
                ['label' => 'Siege', 'value' => 'Société Hôtelière de Nouvelle-Calédonie<br>Adresse a confirmer, Nouméa'],
                ['label' => 'Telephone', 'value' => '+687 00 00 00 <span class="tf-caption">(a confirmer)</span>'],
                ['label' => 'E-mail', 'value' => 'contact@ledomainedupacifique.com <span class="tf-caption">(a confirmer)</span>'],
                ['label' => 'Suivez-nous', 'value' => 'Facebook - Instagram - LinkedIn <span class="tf-caption">(comptes a confirmer)</span>'],
            ],
            'wpforms_id' => 0,
        ],
    ];
}

function tealforge_get_default_destinations(): array
{
    return [
        [
            'index' => 'I',
            'title' => 'Nouméa',
            'place' => 'et le Grand Nouméa',
            'links' => [
                ['label' => 'Le Domaine Nouméa, Series by Marriott', 'type' => 'accommodation'],
                ['label' => 'Île aux Canards', 'type' => 'experience'],
                ['label' => 'Golf de Dumbéa', 'type' => 'experience'],
                ['label' => 'Casinos de Nouméa', 'type' => 'experience'],
                ['label' => 'Deep Nature Spa Nouméa', 'type' => 'experience'],
            ],
        ],
        [
            'index' => 'II',
            'title' => 'Déva',
            'place' => 'Côte Ouest, Bourail',
            'links' => [
                ['label' => 'Le Domaine Déva, Series by Marriott', 'type' => 'accommodation'],
                ['label' => 'La Résidence Déva', 'type' => 'accommodation'],
                ['label' => 'Golf de Déva', 'type' => 'experience'],
                ['label' => 'Deep Nature Spa Déva', 'type' => 'experience'],
            ],
        ],
        [
            'index' => 'III',
            'title' => 'Île des Pins',
            'place' => "Baie d'Oro et baie de Kanuméra",
            'links' => [
                ['label' => 'Le Domaine Oro, Series by Marriott', 'type' => 'accommodation'],
                ['label' => 'Le Domaine Kanuméra', 'type' => 'accommodation'],
                ['label' => 'Deep Nature Spa Oro', 'type' => 'experience'],
            ],
        ],
    ];
}

function tealforge_get_default_accommodations(): array
{
    return [
        tealforge_default_card('Nouméa, baie de l\'Anse Vata', 'Le Domaine Nouméa', 'Series by Marriott', "Face au plus grand lagon du monde, Le Domaine Nouméa, Series by Marriott est une adresse emblematique ou élégance contemporaine et douceur tropicale se rencontrent. Niche au bout de la baie de l'Anse Vata, l'etablissement offre des chambres et suites spacieuses, une offre gastronomique raffinee, un spa d'exception et un acces privilegie au bord de mer. Véritable havre de sérénité, il invite a la détente tout en restant a quelques minutes du centre-ville de Nouméa.", 'Découvrir l\'hotel', 'https://www.marriott.com/fr/hôtels/nousr-le-domaine-noumea-series/overview/', 'Visuel 02 - Le Domaine Nouméa, vue lagon ou façade en lumière de fin de journee. 1600 x 1200 px.', false),
        tealforge_default_card('Bourail, Domaine de Déva', 'Le Domaine Déva', 'Series by Marriott', "Entre ocean, forêt sèche et parcours de golf, Le Domaine Déva, Series by Marriott s'integre harmonieusement au cœur du Domaine de Déva, site naturel d'exception inscrit au patrimoine mondial de l'UNESCO. Ses chambres, suites et bungalows offrent un cadre privilegie pour se ressourcer dans une nature prèservee. Entre gastronomie, bien-être, activités de plein air et découvertes culturelles, le resort propose une expérience complete, au rythme de la côte Ouest calédonienne.", 'Découvrir l\'hotel', 'https://www.marriott.com/fr/hôtels/noudr-le-domaine-deva-series/overview/', 'Visuel 03 - Le Domaine Déva, resort dans son environnement de forêt sèche ou vue golf. 1600 x 1200 px.', true),
        tealforge_default_card('Bourail, Domaine de Déva', 'La Résidence Déva', 'By Le Domaine du Pacifique', "Au cœur du Domaine de Déva, La Résidence Déva propose une nouvelle manière de séjourner en Nouvelle-Calédonie, alliant le confort d'un hébergement premium a la liberté d'un séjour en toute autonomie. Composee de suites spacieuses, elle offre un cadre privilegie pour les voyageurs en quête d'espace, d'intimité et de flexibilité. Ideale pour les séjours prolongés, les familles ou les voyageurs souhaitant vivre a leur rythme.", 'Découvrir la residence', 'https://www.residencedeva.nc', 'Visuel 04 - La Résidence Déva, suite ou terrasse, ambiance séjour en autonomie. 1600 x 1200 px.', false),
        tealforge_default_card("Île des Pins, baie d'Oro", 'Le Domaine Oro', 'Series by Marriott', "Au cœur de l'ile surnommée l'ile la plus proche du paradis, Le Domaine Oro, Series by Marriott célèbre la beauté naturelle de l'un des plus beaux joyaux du Pacifique. Borde par les eaux cristallines de la baie d'Oro, l'hotel offre une expérience exclusive entre plages de sable blanc, nature prèservee et hospitalité raffinee. Chaque séjour est une invitation a ralentir, a se reconnecter a l'essentiel et a vivre une immersion unique au cœur de la culture calédonienne.", 'Découvrir l\'hotel', 'https://www.marriott.com/fr/hôtels/ilpse-le-domaine-oro-series/overview/', 'Visuel 05 - Le Domaine Oro, baie d\'Oro, eaux cristallines et sable blanc. 1600 x 1200 px.', true),
        tealforge_default_card('Île des Pins, baie de Kanuméra', 'Le Domaine Kanuméra', 'By Le Domaine du Pacifique', "Situé au cœur de la magnifique baie de Kanuméra, Le Domaine Kanuméra offre une expérience authentique dans l'un des plus beaux paysages de l'Île des Pins. Ses bungalows nichés dans un jardin tropical, a quelques pas des plages de sable blanc et des eaux cristallines, invitent a la déconnexion et a la sérénité. Véritable havre de paix, il est le point de depart ideal pour explorer les trésors de l'ile, entre baignades, randonnées et découvertes culturelles.", 'Découvrir l\'etablissement', 'https://www.ourelodge.nc/', 'Visuel 06 - Le Domaine Kanuméra, bungalows dans le jardin tropical ou baie de Kanuméra. 1600 x 1200 px.', false),
    ];
}

function tealforge_get_default_experiences(): array
{
    return [
        tealforge_default_card('Nouméa, lagon', 'Île aux Canards', 'By Le Domaine du Pacifique', "A seulement quelques minutes de Nouméa, l'Île aux Canards est une parenthèse tropicale au cœur du lagon calédonien. Reputee pour son sentier sous-marin, ses eaux turquoise et son cadre naturel prèserve, elle offre une expérience unique entre decouverte, détente et loisirs nautiques.", 'Découvrir', 'https://www.ileauxcanards.nc/', 'Visuel 07 - Île aux Canards, sentier sous-marin. 1200 x 900 px.', false),
        tealforge_default_card('Nouméa, Déva, Oro', 'Deep Nature Spa', 'By Le Domaine du Pacifique', 'Presents au sein de nos établissements de Nouméa, Déva et Oro, les Deep Nature Spa sont des espaces entierement dédiés au bien-être et a la relaxation. Inspires par la nature calédonienne, ils proposent une selection de soins, massages et rituels réalisés par des praticiennes expertes.', 'Découvrir', 'https://deepnature.nc/', 'Visuel 08 - Deep Nature Spa, cabine de soin ou rituel. 1200 x 900 px.', false),
        tealforge_default_card('Bourail, Domaine de Déva', 'Golf de Déva', 'By Le Domaine du Pacifique', "Dessiné par l'architecte Dye Designs, le Golf de Déva est l'un des plus beaux parcours du Pacifique. Ses 18 trous serpentent entre lagon, collines et végétation endemique, offrant un cadre spectaculaire aux golfeurs de tous niveaux, avec des panoramas classés au patrimoine mondial de l'UNESCO.", 'Découvrir', 'https://www.exclusivgolf-deva.com/', 'Visuel 09 - Golf de Déva, fairway avec vue lagon. 1200 x 900 px.', false),
        tealforge_default_card('Dumbéa, Grand Nouméa', 'Golf de Dumbéa', 'By Le Domaine du Pacifique', "A quelques minutes de Nouméa, le Golf de Dumbéa accueille joueurs debutants comme confirmés dans un environnement verdoyant et convivial. Son parcours de 18 trous seduit par sa technicite, la qualité de ses installations et son atmosphere chaleureuse. Véritable lieu de vie, il accueille competitions, initiations et evenements tout au long de l'année.", 'Découvrir', 'https://www.gardengolf-dumbea.com/', 'Visuel 10 - Golf de Dumbéa, parcours et club house. 1200 x 900 px.', false),
        tealforge_default_card('Nouméa', 'Casinos de Nouméa', 'By Le Domaine du Pacifique', "Les Casinos de Nouméa offrent une expérience de divertissement élégante au cœur de la capitale. Machines a sous, jeux de table, animations et evenements rythment des lieux pensés pour allier convivialite et raffinement. Ouverts aux visiteurs comme aux résidents, ils prolongent l'expérience du séjour.", 'Découvrir', 'https://casinodenoumea.nc/', 'Visuel 11 - Casinos de Nouméa, ambiance de soirée. 1200 x 900 px.', false),
    ];
}

function tealforge_default_card(string $place, string $title, string $endorsement, string $description, string $link_label, string $link_url, string $image_note, bool $reverse): array
{
    return [
        'image' => null,
        'image_note' => $image_note,
        'place' => $place,
        'title' => $title,
        'endorsement' => $endorsement,
        'description' => $description,
        'link_label' => $link_label,
        'link_url' => $link_url,
        'target_blank' => true,
        'reverse' => $reverse,
    ];
}
