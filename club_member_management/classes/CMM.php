<?php
/**
 * Created by: Joseph Han
 * Date Time: 18-6-8 下午7:58
 * Email: joseph.bing.han@gmail.com
 * Blog: http://blog.joseph-han.net
 */

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
define('CMM_KEY', 'club_member_management');

/***
 * Class Club Member Management
 */
class ClubMemberManagement
{

    const VERSION = '1.0';
    const MEMBER_TYPE = 'club_member';
    const GROUP_TYPE = 'club_group';
    private static $initiated = false;

    const MEMBER_FIELDS = [
        'member_group' => [
            'key' => 'member_group',
            'label' => 'Member Group',
            'type' => 'member_group',
            'list' => true,
        ],
        'email' => [
            'key' => 'email',
            'label' => 'Email Address',
            'type' => 'input',
            'custom_attribute' => 'required type="email"',
            'list' => true,
        ],
        'phone_number' => [
            'key' => 'phone_number',
            'label' => 'Phone Number',
            'type' => 'input',
            'custom_attribute' => 'required',
            'list' => true,
        ],
        'address' => [
            'key' => 'address',
            'label' => 'Home Address',
            'type' => 'textarea',
            'custom_attribute' => 'cols="50" rows="3"',
        ],
        'description' => [
            'key' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
            'custom_attribute' => 'cols="100" rows="5"',
        ],
    ];

    public function init()
    {
        if (!self::$initiated) {
            $this->initHooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private function initHooks()
    {
        self::$initiated = true;

        $this->addPostType();

    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function pluginActivation()
    {

    }

    /**
     * Removes all connection options
     * @static
     */
    public function pluginDeactivation()
    {
    }


    private function addPostType()
    {
        if (is_admin()) {
            register_post_type(ClubMemberManagement::MEMBER_TYPE, array(
                    'labels' => array(
                        'name' => _x('Club Members', 'post type general name', CMM_KEY),
                        'singular_name' => _x('Club Members', 'post type singular name', CMM_KEY),
                        'menu_name' => _x('Club Members', 'admin menu', CMM_KEY),
                        'name_admin_bar' => _x('Club Members', 'add new on admin bar', CMM_KEY),
                        'add_new' => _x('Add New', ClubMemberManagement::MEMBER_TYPE, CMM_KEY),
                        'add_new_item' => __('Add New Member', CMM_KEY),
                        'new_item' => __('New Member', CMM_KEY),
                        'edit_item' => __('Edit Member', CMM_KEY),
                        'view_item' => __('View Member', CMM_KEY),
                        'all_items' => __('All Members', CMM_KEY),
                        'search_items' => __('Search Members', CMM_KEY),
//                        'parent_item_colon' => __('Parent Member:', CMM_KEY),
                        'not_found' => __('No Members found.', CMM_KEY),
                        'not_found_in_trash' => __('No Members found in Trash.', CMM_KEY),
                    ),
                    // Frontend
                    'has_archive' => false,
                    'public' => false,
                    'publicly_queryable' => false,
                    // Admin
                    'capability_type' => 'post',
                    'menu_icon' => 'dashicons-businessman',
                    'menu_position' => 10,
                    'query_var' => true,
                    'show_in_menu' => true,
                    'show_ui' => true,
                    'supports' => ['title', 'author',],
                    'register_meta_box_cb' => [$this, 'addMetaBox4Member'],
                )
            );

            register_post_type(ClubMemberManagement::GROUP_TYPE, array(
                    'labels' => array(
                        'name' => _x('Members Group', 'post type general name', CMM_KEY),
                        'singular_name' => _x('Members Group', 'post type singular name', CMM_KEY),
                        'menu_name' => _x('Members Group', 'admin menu', CMM_KEY),
                        'name_admin_bar' => _x('Members Group', 'add new on admin bar', CMM_KEY),
                        'add_new' => _x('Add New', ClubMemberManagement::GROUP_TYPE, CMM_KEY),
                        'add_new_item' => __('Add New Group', CMM_KEY),
                        'new_item' => __('New Group', CMM_KEY),
                        'edit_item' => __('Edit Group', CMM_KEY),
                        'view_item' => __('View Group', CMM_KEY),
                        'all_items' => __('All Groups', CMM_KEY),
                        'search_items' => __('Search Groups', CMM_KEY),
//                        'parent_item_colon' => __('Parent Group:', CMM_KEY),
                        'not_found' => __('No Groups found.', CMM_KEY),
                        'not_found_in_trash' => __('No Group found in Trash.', CMM_KEY),
                    ),
                    // Frontend
                    'has_archive' => false,
                    'public' => false,
                    'publicly_queryable' => false,
                    // Admin
                    'capability_type' => 'post',
                    'menu_icon' => 'dashicons-networking',
                    'menu_position' => 11,
                    'query_var' => true,
                    'show_in_menu' => true,
                    'show_ui' => true,
                    'supports' => ['title', 'author',],
                    'register_meta_box_cb' => [$this, 'addMetaBox4Group'],
                )
            );

            // add custom type save action
            add_action('save_post', array($this, 'saveMetaBox'));


            // add column on member list view
            add_filter('manage_edit-' . ClubMemberManagement::MEMBER_TYPE . '_columns', [$this, 'addMemberListColumn']);

            // add display column
            add_action('manage_' . ClubMemberManagement::MEMBER_TYPE . '_posts_custom_column',
                [$this, 'displayMemberListColumnData'], 10, 2);

            // add sortable column
            add_filter('manage_edit-' . ClubMemberManagement::MEMBER_TYPE . '_sortable_columns',
                [$this, 'addMemberListSortableColumn']);

            // add sortable query
            add_filter('request', array($this, 'orderby_sortable_table_columns'));
        }
    }

    public function addMetaBox4Member()
    {
        add_meta_box(
            'club-member-details',
            'Club Member Details',
            array($this, 'displayMetaBox4Member'),
            ClubMemberManagement::MEMBER_TYPE,
            'normal',
            'high'
        );

    }

    public function addMetaBox4Group()
    {
        add_meta_box(
            'member-group-details',
            'Member Group Details',
            [$this, 'displayMetaBox4Group'],
            ClubMemberManagement::GROUP_TYPE,
            'normal',
            'high'
        );
        add_meta_box(
            'member-group-members',
            'Group Member List',
            [$this, 'displayMetaBox4GroupMember'],
            ClubMemberManagement::GROUP_TYPE,
            'normal',
            'high'
        );

    }


    /** * Output a Contact Details meta box *
     * @param WP_Post $post WordPress Post object
     */
    public function displayMetaBox4Member($post)
    {

        // Add a nonce field so we can check for it later.
        wp_nonce_field('save_contact', 'contacts_nonce');

        $data = [];
        foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
            $data[$field['key']] = get_post_meta($post->ID,
                ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'],
                true
            );
        }

        $member_groups = query_posts([
            'post_status' => 'publish',
            'post_type' => ClubMemberManagement::GROUP_TYPE
        ]);

        require CMM_PLUGIN_VIEWS_DIR . 'member.view.php';

    }

    public function displayMetaBox4Group($post)
    {
        $description = get_post_meta($post->ID, ClubMemberManagement::GROUP_TYPE . '_description', true);

        // Add a nonce field so we can check for it later.
        wp_nonce_field('save_contact', 'contacts_nonce');

        // Output label and field
        echo('<label for="description"><h4>' . __('Description:', CMM_KEY) . '</h4></label>');
        echo("<textarea cols='100' rows='10' id='description' name='description'>{$description}</textarea>");
    }

    public function displayMetaBox4GroupMember($post)
    {
        $data = get_posts([
            'post_type' => ClubMemberManagement::MEMBER_TYPE,
            ClubMemberManagement::MEMBER_TYPE . '_member_group' => $post->ID
        ]);

        require CMM_PLUGIN_VIEWS_DIR . 'group.view.php';
    }

    public function saveMetaBox($post_id)
    {
        // Check if our nonce is set.
        if (!isset($_POST['contacts_nonce'])) {
            return $post_id;
        }
        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['contacts_nonce'], 'save_contact')) {
            return $post_id;
        }
        // Check this is the Contact Custom Post Type
        if (ClubMemberManagement::MEMBER_TYPE == $_POST['post_type']) {
            // Check the logged in user has permission to edit this post
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
                $$field['key'] = sanitize_text_field($_POST[$field['key']]);
                update_post_meta($post_id, ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'], $$field['key']);
            }

        } elseif (ClubMemberManagement::GROUP_TYPE == $_POST['post_type']) {
            // Check the logged in user has permission to edit this post
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
            // OK to save meta data
            $description = sanitize_text_field($_POST['description']);
            update_post_meta($post_id, ClubMemberManagement::GROUP_TYPE . '_description', $description);
        } else {
            return $post_id;
        }

    }

    public function addMemberListColumn($columns)
    {
        foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
            if ($field['list']) {
                $columns[ClubMemberManagement::MEMBER_TYPE . '_' . $field['key']] = __($field['label'], CMM_KEY);
            }
        }
        return $columns;
    }

    public function displayMemberListColumnData($columnName, $post_id)
    {
        if ($columnName == ClubMemberManagement::MEMBER_TYPE . '_member_group') {
            echo get_post_field('post_title', get_post_field($columnName, $post_id));
        } else {
            echo get_post_field($columnName, $post_id);
        }


    }

    function addMemberListSortableColumn($columns)
    {
        foreach (ClubMemberManagement::MEMBER_FIELDS as $field) {
            if ($field['list']) {
                $key = ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'];
                $columns[$key] = $key;
            }
        }
        return $columns;
    }

    function orderby_sortable_table_columns($vars)
    {
        // Don't do anything if we are not on the Contact Custom Post Type
        if ('contact' != $vars['post_type']) {
            return $vars;
        }

        // Don't do anything if no orderby parameter is set
        if (!isset($vars['orderby'])) {
            return $vars;
        }
        // Check if the orderby parameter matches one of our sortable columns
        if (strpos($vars['orderby'], ClubMemberManagement::MEMBER_TYPE . '_') !== false) {
            // Add orderby meta_value and meta_key parameters to the query
            $vars = array_merge($vars, ['meta_key' => $vars['orderby'], 'orderby' => 'meta_value']);
        }
        return $vars;
    }
}