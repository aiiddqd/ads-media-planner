<?php

namespace AdsMediaPlanner;

Places::init();

class Places
{
    public static $places = [];

    public static function init()
    {
        add_action('init', [self::class, 'addPlaces']);
        add_action('add_meta_boxes', [self::class, 'addMetaBox']);
        add_action('save_post', [self::class, 'saveMetaBox']);
        add_action('manage_posts_custom_column', [self::class, 'addAdminListColumnContent'], 10, 2);
        add_filter('manage_edit-ad-block_columns', [self::class, 'addAdminListColumn']);
        add_action('init', function () {

            if (Settings::isEnabled()) {
                add_action('wp_footer', [self::class, 'render_fullscreen_in_footer']);
                add_action('wp_body_open', [self::class, 'render_fullscreen_in_body']);

                add_filter('the_content', [self::class, 'renderAfterContent'], 100);
                add_filter('the_content', [self::class, 'renderBeforeContent'], 5);
            }
        });

    }


    public static function addPlaces()
    {
        self::$places = [
            'fullscreen-in-footer' => 'Fullscreen in Footer',
            'fullscreen-in-body' => 'Fullscreen in Body',
            'before_content' => 'Before Content',
            'after_content' => 'After Content',
        ];

        self::$places = apply_filters('ads_media_planner_places', self::$places);
    }

    public static function renderAfterContent($content)
    {
        $advertising_content = self::getBlocksForPlace('after_content');
        if(empty(trim($advertising_content))) {
            return $content;
        }
        $advertising_content = sprintf('<div class="ad-media-planner ad-after-content">%s</div>', $advertising_content);

        return $content . $advertising_content;
    }

    public static function renderBeforeContent($content)
    {
        $advertising_content = self::getBlocksForPlace('before_content');
        if(empty(trim($advertising_content))) {
            return $content;
        }
        $advertising_content = sprintf('<div class="ad-media-planner ad-before-content">%s</div>', $advertising_content);

        return $advertising_content . $content;
    }

    public static function render_fullscreen_in_body()
    {
        return self::getBlocksForPlace('fullscreen-in-body');
    }

    public static function render_fullscreen_in_footer()
    {
        return self::getBlocksForPlace('fullscreen-in-footer');
    }

    public static function addAdminListColumnContent($column, $post_id)
    {
        if ($column === 'ad_places') {
            $places = get_post_meta($post_id, '_ad_places', true);
            if (is_array($places)) {
                $places = implode(', ', $places);
            }
            echo esc_html($places);
        }

        if ($column === 'slug') {
            $post = get_post($post_id);
            if ($post) {
                echo esc_html($post->post_name);
            }
        }
    }

    public static function addAdminListColumn($columns)
    {
        // Move 'Ad Places' column after the 'title' column
        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ($key === 'title') {
                $new_columns['slug'] = 'Slug';
                $new_columns['ad_places'] = 'Ad Places';
            }
        }
        return $new_columns;
    }

    public static function getBlocksForPlace($place)
    {
        $args = [
            'post_type' => 'ad-block',
            'meta_query' => [
                [
                    'key' => '_ad_places',
                    'value' => $place,
                    'compare' => 'LIKE'
                ]
            ],
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        $query = new \WP_Query($args);
        $content = '';
        foreach ($query->posts as $post) {
            $content .= $post->post_content;
        }
        return $content;
    }

    public static function saveMetaBox($post_id)
    {
        if (array_key_exists('ad_places', $_POST)) {
            $places = array_map('sanitize_text_field', (array) $_POST['ad_places']);
            update_post_meta($post_id, '_ad_places', $places);
        } else {
            delete_post_meta($post_id, '_ad_places');
        }
    }

    public static function addMetaBox()
    {
        add_meta_box(
            'ad-places',
            'Ad Places',
            function ($post) {
                $selected_place = get_post_meta($post->ID, '_ad_places', true);
                if (is_array($selected_place)) {
                    $selected_place = reset($selected_place);
                }
                ?>
            <div>
                <select name="ad_places[]">
                    <option value="">Select a place</option>
                    <?php foreach (self::$places as $key => $label) : ?>
                        <option value="<?php echo esc_attr($key); ?>" <?php selected($selected_place, $key); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
            },
            'ad-block',
            'side',
            'default'
        );


    }

}
