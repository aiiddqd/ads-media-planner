<?php

namespace AdsMediaPlanner;

AdBlock::init();

class AdBlock
{
    public static function init()
    {
        add_filter('wp_editor_settings', [self::class, 'disable_wysiwyg_for_ad_block']);

        add_action('init', [self::class, 'register_ad_block_post_type']);


    }

    public static function register_ad_block_post_type()
    {
        register_post_type('ad-block', array(
            'labels' => array(
                'name' => 'Ad Blocks',
                'singular_name' => 'Ad Block',
                'menu_name' => 'Advertising',
                'all_items' => 'Ad Blocks',
                'edit_item' => 'Edit Ad Block',
                'view_item' => 'View Ad Block',
                'view_items' => 'View Ad Blocks',
                'add_new_item' => 'Add New',
                'add_new' => 'Add New Ad Block',
                'new_item' => 'New Ad Block',
                'parent_item_colon' => 'Parent Ad Block:',
                'search_items' => 'Search Ad Blocks',
                'not_found' => 'No ad blocks found',
                'not_found_in_trash' => 'No ad blocks found in trash',
                'archives' => 'Ad Block Archives',
                'attributes' => 'Ad Block Attributes',
                'insert_into_item' => 'Insert into ad block',
                'uploaded_to_this_item' => 'Uploaded to this ad block',
                'filter_items_list' => 'Filter ad blocks list',
                'filter_by_date' => 'Filter ad blocks by date',
                'items_list_navigation' => 'Ad Blocks List Navigation',
                'items_list' => 'Ad Blocks List',
                'item_published' => 'Ad Block published.',
                'item_published_privately' => 'Ad Block published privately.',
                'item_reverted_to_draft' => 'Ad Block reverted to draft.',
                'item_scheduled' => 'Ad Block scheduled for publication.',
                'item_updated' => 'Ad Block updated.',
                'item_link' => 'Link to Ad Block',
                'item_link_description' => 'Link to ad block.',
            ),
            'public' => false,
            'show_ui' => true,
            'show_in_rest' => false,
            'menu_icon' => 'dashicons-analytics',
            'supports' => [
                'title',
                'slug',
                'author', 'comments', 'editor', 'excerpt', 'revisions', 'thumbnail', 'custom-fields',
                'post-formats',
                'page-attributes',
            ],

            'rewrite' => array(
                'with_front' => false,
                'pages' => false,
            ),
            'delete_with_user' => false,
        ));
    }

    public static function disable_wysiwyg_for_ad_block($settings)
    {
        $screen = get_current_screen();
        if ($screen && $screen->post_type === 'ad-block') {
            $settings['tinymce'] = false; // Disable TinyMCE (WYSIWYG)
            $settings['quicktags'] = true; // Enable plain text editor with basic HTML tags
        }
        return $settings;
    }
}





