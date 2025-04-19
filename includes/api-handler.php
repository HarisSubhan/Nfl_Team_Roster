<?php
class NFL_Team_Roster_API {
    private $api_key;
    
    public function __construct() {
        $this->api_key = get_option('nfl_team_roster_api_key', '');
    }
    
    public function fetch_team_roster($team_id) {
        $transient_key = 'nfl_roster_' . $team_id;
        
        if (false === ($data = get_transient($transient_key))) {
            $response = wp_remote_get(
                'https://nfl-football-api.p.rapidapi.com/nfl-team-roster?id=' . $team_id,
                [
                    'headers' => [
                        'x-rapidapi-host' => 'nfl-football-api.p.rapidapi.com',
                        'x-rapidapi-key' => $this->api_key
                    ],
                    'timeout' => 30
                ]
            );
            
            if (is_wp_error($response)) {
                error_log('NFL API Error: ' . $response->get_error_message());
                return false;
            }
            
            $data = json_decode(wp_remote_retrieve_body($response), true);
            
            if ($data) {
                set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);
            }
        }
        
        return $data;
    }
}