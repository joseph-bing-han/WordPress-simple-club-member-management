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
    const MAIL_TYPE = 'club_mail';

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
                        'add_new' => _x('Add New Member', ClubMemberManagement::MEMBER_TYPE, CMM_KEY),
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
                        'add_new' => _x('Add New Group', ClubMemberManagement::GROUP_TYPE, CMM_KEY),
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

            register_post_type(ClubMemberManagement::MAIL_TYPE, array(
                    'labels' => array(
                        'name' => _x('Group Mail', 'post type general name', CMM_KEY),
                        'singular_name' => _x('Group Mail', 'post type singular name', CMM_KEY),
                        'menu_name' => _x('Group Mail', 'admin menu', CMM_KEY),
                        'name_admin_bar' => _x('Group Mail', 'add new on admin bar', CMM_KEY),
                        'add_new' => _x('Send Mail', ClubMemberManagement::MAIL_TYPE, CMM_KEY),
                        'add_new_item' => __('Send Mail', CMM_KEY),
                        'new_item' => __('New Mail', CMM_KEY),
                        'edit_item' => __('Edit Mail', CMM_KEY),
                        'view_item' => __('View Mail', CMM_KEY),
                        'all_items' => __('All Mails', CMM_KEY),
                        'search_items' => __('Search Group Mail', CMM_KEY),
//                        'parent_item_colon' => __('Parent Group:', CMM_KEY),
                        'not_found' => __('No Group Mail found.', CMM_KEY),
                        'not_found_in_trash' => __('No Group Mail found in Trash.', CMM_KEY),
                    ),
                    // Frontend
                    'has_archive' => false,
                    'public' => false,
                    'publicly_queryable' => false,
                    // Admin
                    'capability_type' => 'post',
                    'menu_icon' => 'dashicons-email-alt',
                    'menu_position' => 12,
                    'query_var' => true,
                    'show_in_menu' => true,
                    'show_ui' => true,
                    'supports' => ['title',],
                    'register_meta_box_cb' => [$this, 'addMetaBox4Mail'],
                )
            );

            // add custom type save action
            add_action('save_post', array($this, 'saveMetaBox'));


            // add column on mail list view
            add_filter('manage_edit-' . ClubMemberManagement::MEMBER_TYPE . '_columns', [$this, 'addMemberListColumn']);

            // add display column
            add_action('manage_' . ClubMemberManagement::MEMBER_TYPE . '_posts_custom_column',
                [$this, 'displayMemberListColumnData'], 10, 2);

            // add sortable column
            add_filter('manage_edit-' . ClubMemberManagement::MEMBER_TYPE . '_sortable_columns',
                [$this, 'addMemberListSortableColumn']);

            // add sortable query
            add_filter('request', [$this, 'orderby_sortable_table_columns']);


            add_filter('restrict_manage_posts', [$this, 'group_filter_list']);
            add_filter('parse_query', [$this, 'group_filter_request_query'], 10);

            add_action('phpmailer_init', [$this, 'set_mail_smtp']);

            add_filter('bulk_actions-edit-' . ClubMemberManagement::MEMBER_TYPE, [$this, 'member_bulk_actions']);
            add_filter('handle_bulk_actions-edit-' . ClubMemberManagement::MEMBER_TYPE,
                [$this, 'member_bulk_action_handler'], 10, 3);

        }
    }

    /***
     * config smtp for phpmailer
     * @param $phpmailer
     * @return mixed
     */
    function set_mail_smtp($phpmailer)
    {
        $phpmailer->FromName = 'NewZealand Jeep Club';
        $phpmailer->Host = 'smtp.163.com';
        $phpmailer->Port = 994;
        $phpmailer->Username = 'han-bingbing-1@163.com';
        $phpmailer->Password = 'xxxxxx';
        $phpmailer->From = 'han-bingbing-1@163.com';   // same as Username
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'ssl'; //tls or ssl
        $phpmailer->IsSMTP();
        return $phpmailer;
    }

    function member_bulk_action_handler($redirect, $doaction, $object_ids)
    {
        if ($doaction == 'send_group_mail') {
            $redirect = add_query_arg([
                'post_type' => ClubMemberManagement::MAIL_TYPE,
                'member_id' => $object_ids
            ], admin_url('post-new.php'));
        }
        return $redirect;
    }

    function member_bulk_actions($actions)
    {
        $actions['send_group_mail'] = 'Send Group Mail';
        return $actions;
    }


    public function group_filter_list()
    {
        global $typenow;
        if ($typenow == ClubMemberManagement::MEMBER_TYPE) {
            $selected = get_query_var('member_group');
            $output = "<select name='member_group' class='postform'>\n";
            $output .= '<option ' . selected($selected, 0, false) . ' value="">All Groups</option>';
            $groups = query_posts([
                'post_status' => 'publish',
                'post_type' => ClubMemberManagement::GROUP_TYPE
            ]);
            wp_reset_query();
            foreach ($groups as $group) {
                $output .= "<option " . selected($selected, $group->ID, false) .
                    " value='{$group->ID}'>{$group->post_title}</option>";
            }
            $output .= "</select>\n";
            echo $output;
        }
    }

    public function group_filter_request_query($query)
    {
        //modify the query only if it is admin and main query.
        if (!(is_admin() AND $query->is_main_query())) {
            return $query;
        }
        //we want to modify the query for the targeted custom post.
        if ($query->query['post_type'] !== ClubMemberManagement::MEMBER_TYPE) {
            return $query;
        }
        //type filter
        if (isset($_REQUEST['member_group']) && 0 != $_REQUEST['member_group']) {
            $group = $_REQUEST['member_group'];
            $query->query_vars['member_group'] = $group;
            $query->set('meta_query', [[
                'key' => ClubMemberManagement::MEMBER_TYPE . '_member_group',
                'value' => $group,
                'compare' => 'like',
            ]]);
        }

        return $query;
    }

    public function addMetaBox4Member()
    {
        add_meta_box(
            'club-member-details',
            'Club Member Details',
            [$this, 'displayMetaBox4Member'],
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

    public function addMetaBox4Mail()
    {
        add_meta_box(
            'group-mail-details',
            'Mail Body',
            [$this, 'displayMetaBox4MailBody'],
            ClubMemberManagement::MAIL_TYPE,
            'normal',
            'high'
        );
        add_meta_box(
            'group-mail-members',
            'Mail Group Member List',
            [$this, 'displayMetaBox4MailMember'],
            ClubMemberManagement::MAIL_TYPE,
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
            'meta_query' => [
                [
                    'key' => ClubMemberManagement::MEMBER_TYPE . '_member_group',
                    'value' => $post->ID,
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        require CMM_PLUGIN_VIEWS_DIR . 'group.view.php';
    }

    public function displayMetaBox4MailBody($post)
    {
        // Add a nonce field so we can check for it later.
        echo wp_nonce_field('save_contact', 'contacts_nonce');

        $settings = array(
            'wpautop' => true,
            'media_buttons' => false,
            'tinymce' => array(
                'theme_advanced_buttons1' => 'bold,italic,underline,blockquote,|,undo,redo,|,fullscreen',
                'theme_advanced_buttons2' => '',
                'theme_advanced_buttons3' => '',
                'theme_advanced_buttons4' => ''
            ),
            'quicktags' => array(
                'buttons' => 'b,i,ul,ol,li,link,close'
            ),
            'media_uploads' => false
        );
        $content = '';
        if ($post->filter == 'edit') {
            $content = get_post_meta($post->ID, ClubMemberManagement::MAIL_TYPE . '_mail_body', true);
        }
        echo wp_editor($content, 'mail_body', $settings);

    }

    public function displayMetaBox4MailMember($post)
    {
        $data = [];
        if ($post->filter == 'edit') {
            $ids = get_post_meta($post->ID, ClubMemberManagement::MAIL_TYPE . '_member_id', true);
            if (!empty($ids)) {
                $data = get_posts([
                    'post_type' => ClubMemberManagement::MEMBER_TYPE,
                    'post__in' => $ids
                ]);
            }
        } else {
            if (!empty($_REQUEST['member_id'])) {
                $data = get_posts([
                    'post_type' => ClubMemberManagement::MEMBER_TYPE,
                    'post__in' => $_REQUEST['member_id']
                ]);
            }
        }

        $members = [];
        $all_members = query_posts([
            'post_status' => 'publish',
            'post_type' => ClubMemberManagement::MEMBER_TYPE
        ]);
        foreach ($all_members as $member) {
            $group = get_post_meta($member->ID, ClubMemberManagement::MEMBER_TYPE . '_member_group');
            if (!empty($group)) {
                $group_data = get_post($group[0]);
                $group = $group_data->post_title;
            }
            $mail = get_post_meta($member->ID, ClubMemberManagement::MEMBER_TYPE . '_email');
            if (!empty($mail)) {
                $mail = $mail[0];
            }
            $members[$group][$member->ID] = [
                'mail_address' => $mail,
                'full_name' => $member->post_title
            ];
        }

        require CMM_PLUGIN_VIEWS_DIR . 'mail_member.view.php';
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
                if ($field['key'] == 'member_group') {
                    $ids = implode($_POST['member_group'], ',') . ',';
                    update_post_meta($post_id, ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'], $ids);
                } else {
                    $$field['key'] = sanitize_text_field($_POST[$field['key']]);
                    update_post_meta($post_id, ClubMemberManagement::MEMBER_TYPE . '_' . $field['key'], $$field['key']);
                }

            }

        } elseif (ClubMemberManagement::GROUP_TYPE == $_POST['post_type']) {
            // Check the logged in user has permission to edit this post
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
            // OK to save meta data
            $description = sanitize_text_field($_POST['description']);
            update_post_meta($post_id, ClubMemberManagement::GROUP_TYPE . '_description', $description);
        } elseif (ClubMemberManagement::MAIL_TYPE == $_POST['post_type']) {
            // Check the logged in user has permission to edit this post
            if (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
            update_post_meta($post_id, ClubMemberManagement::MAIL_TYPE . '_mail_body', $_POST['mail_body']);
            update_post_meta($post_id, ClubMemberManagement::MAIL_TYPE . '_member_id', $_POST['member_id']);
            // OK to save meta data

            //send mail to each member
            if ($_POST['post_status'] === 'publish') {
                $subject = $_POST['post_title'];
                $body = $_POST['mail_body'];
                $body = str_replace('\"', '"', $body);
                $headers = array('Content-Type: text/html; charset=UTF-8');
                foreach ($_POST['member_id'] as $id) {
                    $to = get_post_meta($id, ClubMemberManagement::MEMBER_TYPE . '_email');
                    wp_mail($to, $subject, $body, $headers);
                }
            }

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
            $groups = [];
            foreach (explode(',', get_post_field($columnName, $post_id)) as $id) {
                if (!empty($id)) {
                    $groups[] = '<a href="' . add_query_arg(['post' => $id, 'action' => 'edit'], admin_url('post.php'))
                        . '">' . get_post_field('post_title', $id) . '</a>';
                }
            }
            echo implode($groups, ',&nbsp;');
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