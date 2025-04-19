<?php
/*
Plugin Name: NFL Team Roster
Description: Displays NFL team rosters with API data
Version: 1.0
Author: Your Name
*/

defined('ABSPATH') or die('Direct access not allowed');

// Define constants
define('NFL_ROSTER_VERSION', '1.0');
define('NFL_ROSTER_PATH', plugin_dir_path(__FILE__));
define('NFL_ROSTER_URL', plugin_dir_url(__FILE__));

// Include files
require_once NFL_ROSTER_PATH . 'includes/api-handler.php';
require_once NFL_ROSTER_PATH . 'includes/shortcode.php';
require_once NFL_ROSTER_PATH . 'includes/admin-settings.php';

class NFL_Team_Roster {
    public function __construct() {
        new NFL_Team_Roster_API();
        new NFL_Team_Roster_Shortcode();
        new NFL_Team_Roster_Admin();
    }
}

new NFL_Team_Roster();