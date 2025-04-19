<?php
class NFL_Team_Roster_Shortcode {
    private $api;

    public function render_team_display($atts) {
        $atts = shortcode_atts([
            'team_id' => '16',
            'default_view' => 'simple', // simple/full
            'show_raw' => false
        ], $atts);
    
        $data = $this->api->fetch_team_roster($atts['team_id']);
        
        if (!$data) {
            return '<div class="nfl-error">Team data currently unavailable</div>';
        }
    
        ob_start();
        ?>
        <div class="nfl-team-display" data-view="<?php echo esc_attr($atts['default_view']); ?>">
            <!-- Team Header -->
            <div class="team-header">
                <?php if (!empty($data['team']['logo'])) : ?>
                <img src="<?php echo esc_url($data['team']['logo']); ?>" 
                     alt="<?php echo esc_attr($data['team']['name']); ?> Logo" 
                     class="team-logo">
                <?php endif; ?>
                <h2><?php echo esc_html($data['team']['name']); ?></h2>
                <button class="toggle-view">
                    <?php echo $atts['default_view'] === 'simple' ? 'Show Full Roster' : 'Hide Full Roster'; ?>
                </button>
            </div>
    
            <!-- Simple View (Default) -->
            <div class="simple-view">
                <div class="team-meta">
                    <?php if (!empty($data['team']['venue'])) : ?>
                    <div class="meta-item">
                        <i class="fas fa-stadium"></i>
                        <span><?php echo esc_html($data['team']['venue']); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span><?php echo count($data['athletes']); ?> Players</span>
                    </div>
                </div>
            </div>
    
            <!-- Full Roster View (Hidden by Default) -->
            <div class="full-roster" style="display: none;">
                <div class="player-filters">
                    <input type="text" placeholder="Search players..." class="player-search">
                    <select class="position-filter">
                        <option value="">All Positions</option>
                        <?php
                        $positions = array_unique(array_column($data['athletes'], 'position'));
                        foreach ($positions as $pos) {
                            echo '<option value="' . esc_attr($pos) . '">' . esc_html($pos) . '</option>';
                        }
                        ?>
                    </select>
                </div>
    
                <div class="players-grid">
                    <?php foreach ($data['athletes'] as $player) : ?>
                    <div class="player-card" data-position="<?php echo esc_attr($player['position']); ?>">
                    <div class="player-header">
    <?php if (!empty($player['headshot']['href'])) : ?>
    <img src="<?php echo esc_url($player['headshot']['href']); ?>" 
         alt="<?php echo esc_attr($player['displayName']); ?>" 
         class="player-headshot">
    <?php endif; ?>
    <div class="player-info">
        <h3><?php echo esc_html($player['displayName']); ?></h3>
        <?php 
        // Only show jersey div if number exists and isn't empty
        if (isset($player['jersey']) && $player['jersey'] !== '') : ?>
            <div class="jersey">#<?php echo esc_html($player['jersey']); ?></div>
        <?php endif; ?>
    </div>
</div>
                        <div class="player-details">
                            <div class="detail-row">
                                <span class="label">Position:</span>
                                <span><?php echo esc_html($player['position']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Height:</span>
                                <span><?php echo esc_html($player['displayHeight']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Weight:</span>
                                <span><?php echo esc_html($player['displayWeight']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="label">Age:</span>
                                <span><?php echo esc_html($player['age']); ?></span>
                            </div>
                            <?php if (!empty($player['college']['name'])) : ?>
                            <div class="detail-row">
                                <span class="label">College:</span>
                                <span><?php echo esc_html($player['college']['name']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="detail-row">
                                <span class="label">Experience:</span>
                                <span><?php echo esc_html($player['experience']['years'] ?? '0'); ?> yrs</span>
                            </div>
                            <div class="detail-row">
                                <span class="label">DOB</span>
                                <span><?php echo esc_html($player['dateOfBirth'] ?? '0'); ?> yrs</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
    
            <?php if ($atts['show_raw']) : ?>
            <div class="raw-data-toggle">
                <button class="toggle-raw">Show Raw API Data</button>
                <pre class="api-raw-data" style="display:none;">
                    <?php echo json_encode($data, JSON_PRETTY_PRINT); ?>
                </pre>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function __construct() {
        $this->api = new NFL_Team_Roster_API();
        add_shortcode('nfl_raw_data', [$this, 'render_raw_json']);
        add_shortcode('nfl_team_display', [$this, 'render_team_display']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }
    
    public function enqueue_assets() {
        wp_enqueue_style(
            'nfl-team-roster-style',
            NFL_ROSTER_URL . 'assets/css/style.css',
            [],
            NFL_ROSTER_VERSION
        );
        
        wp_enqueue_script(
            'nfl-team-roster-script',
            NFL_ROSTER_URL . 'assets/js/script.js',
            ['jquery'],
            NFL_ROSTER_VERSION,
            true
        );
    }
    
    public function render_raw_json($atts) {
        $atts = shortcode_atts([
            'team_id' => '16',
            'pretty_print' => true
        ], $atts);

        $data = $this->api->fetch_team_roster($atts['team_id']);
        
        if (!$data) {
            return '<div class="api-error">Failed to fetch API data</div>';
        }

        ob_start();
        include NFL_ROSTER_PATH . 'templates/raw-output.php';
        return ob_get_clean();
    }

    public function render_team_logo($atts) {
        $atts = shortcode_atts([
            'team_id' => '16', // Default to Vikings
            'show_raw' => false,
            'logo_size' => 'medium' // small/medium/large
        ], $atts);

        $data = $this->api->fetch_team_roster($atts['team_id']);
        
        if (!$data || empty($data['team'])) {
            return '<div class="nfl-error">Team data unavailable</div>';
        }

        ob_start();
        ?>
        <div class="nfl-team-logo-display">
            <!-- Team Logo & Name -->
            <div class="team-branding">
                <?php if (!empty($data['team']['logo'])) : ?>
                <img src="<?php echo esc_url($data['team']['logo']); ?>" 
                     alt="<?php echo esc_attr($data['team']['name']); ?> Logo"
                     class="team-logo <?php echo esc_attr($atts['logo_size']); ?>">
                <?php endif; ?>
                <div class="team-name"><?php echo esc_html($data['team']['name']); ?></div>
            </div>

            <!-- Optional Raw Data Toggle -->
            <?php if ($atts['show_raw']) : ?>
            <div class="raw-data-toggle">
                <button class="toggle-raw">Show API Data</button>
                <pre class="api-raw-data" style="display:none;">
                    <?php echo json_encode($data, JSON_PRETTY_PRINT); ?>
                </pre>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    


}