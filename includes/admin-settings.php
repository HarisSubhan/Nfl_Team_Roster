<?php
class NFL_Team_Roster_Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }
    
    public function add_admin_menu() {
        add_options_page(
            'NFL Team Roster Settings',
            'NFL Team Roster',
            'manage_options',
            'nfl-team-roster',
            [$this, 'render_settings_page']
        );
    }
    
    public function register_settings() {
        register_setting('nfl_team_roster_options', 'nfl_team_roster_api_key');
    }
    
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>NFL Team Roster Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('nfl_team_roster_options'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">RapidAPI Key</th>
                        <td>
                            <input type="password" name="nfl_team_roster_api_key" 
                                   value="<?php echo esc_attr(get_option('nfl_team_roster_api_key')); ?>" 
                                   class="regular-text">
                            <p class="description">Get your API key from <a href="https://rapidapi.com/" target="_blank">RapidAPI</a></p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}