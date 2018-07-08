<?php
/*
Plugin Name: New Zealand Jeep Club Member Management
Plugin URI: https://github.com/joseph-bing-han/WordPress-simple-club-member-management
Description: Management member information using group
Version: 1.0
Author: Joseph Han
Author URI: http://blog.joseph-han.net
*/

/*  Copyright 2018  Joseph Han  (email : joseph.bing.han@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!function_exists('add_action')) {
    echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
    exit;
}
// define dir of plugin
define('CMM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CMM_PLUGIN_CLASS_DIR', CMM_PLUGIN_DIR . 'classes' . DIRECTORY_SEPARATOR);
define('CMM_PLUGIN_IMAGES_DIR', plugin_dir_url(__FILE__) . 'images' . DIRECTORY_SEPARATOR);
define('CMM_PLUGIN_VIEWS_DIR', CMM_PLUGIN_DIR . 'views' . DIRECTORY_SEPARATOR);


// require all class files
require_once(CMM_PLUGIN_CLASS_DIR . 'CMM.php');

$cmm = new ClubMemberManagement();

// register activation and deactivation hook
register_activation_hook(__FILE__, [$cmm, 'pluginActivation']);
register_deactivation_hook(__FILE__, [$cmm, 'pluginDeactivation']);


// init
add_action('init', array($cmm, 'init'));

