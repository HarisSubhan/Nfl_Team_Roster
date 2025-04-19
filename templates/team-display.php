<div class="nfl-team-container" data-team-id="<?php echo esc_attr($atts['team_id']); ?>">
    
    <!-- Team Header -->
    <div class="team-header">
        <?php if (!empty($data['team']['logo'])) : ?>
        <img src="<?php echo esc_url($data['team']['logo']); ?>" 
             alt="<?php echo esc_attr($data['team']['name']); ?> Logo" 
             class="team-logo">
        <?php endif; ?>
        
        <div class="team-info">
            <h2><?php echo esc_html($data['team']['name']); ?></h2>
            <div class="team-meta">
                <?php if (!empty($data['team']['venue'])) : ?>
                <span class="team-venue">
                    <i class="fas fa-stadium"></i> <?php echo esc_html($data['team']['venue']); ?>
                </span>
                <?php endif; ?>
                <?php if (!empty($data['team']['location'])) : ?>
                <span class="team-location">
                    <i class="fas fa-map-marker-alt"></i> <?php echo esc_html($data['team']['location']); ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Coaching Staff -->
    <?php if ($atts['show_coaches'] && !empty($data['coaches'])) : ?>
    <div class="coaching-staff">
        <h3><i class="fas fa-clipboard-list"></i> Coaching Staff</h3>
        <div class="coaches-grid">
            <?php foreach ($data['coaches'] as $coach) : ?>
            <div class="coach-card">
                <div class="coach-name"><?php echo esc_html($coach['name']); ?></div>
                <div class="coach-position"><?php echo esc_html($coach['position']); ?></div>
                <?php if (!empty($coach['experience'])) : ?>
                <div class="coach-exp"><?php echo (int)$coach['experience']; ?> yrs experience</div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Player Roster -->
    <?php if ($atts['show_players'] && !empty($data['players'])) : ?>
    <div class="player-roster">
        <h3><i class="fas fa-users"></i> Player Roster (<?php echo count($data['players']); ?>)</h3>
        
        <div class="player-filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search players..." class="player-search">
            </div>
            <select class="position-filter">
                <option value="">All Positions</option>
                <?php
                $positions = array_unique(array_column($data['players'], 'position'));
                foreach ($positions as $pos) {
                    echo '<option value="' . esc_attr($pos) . '">' . esc_html($pos) . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="players-table-wrapper">
            <table class="players-table">
                <thead>
                    <tr>
                        <th data-sort="number">#</th>
                        <th data-sort="name">Player</th>
                        <th data-sort="position">Position</th>
                        <th data-sort="height">Height</th>
                        <th data-sort="weight">Weight</th>
                        <th data-sort="experience">Exp</th>
                        <th data-sort="dateOfBirth">DOB</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['players'] as $player) : ?>
                    <tr>
                        <td><?php echo esc_html($player['number']); ?></td>
                        <td>
                            <span class="player-name"><?php echo esc_html($player['name']); ?></span>
                            <?php if (!empty($player['college'])) : ?>
                            <div class="player-college"><?php echo esc_html($player['college']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($player['position']); ?></td>
                        <td><?php echo esc_html($player['height']); ?></td>
                        <td><?php echo (int)$player['weight']; ?> lbs</td>
                        <td><?php echo (int)$player['experience']; ?> yrs</td>
                        <td><?php echo (int)$player['dateOfBirth']; ?></td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($atts['players_per_page'] > 0) : ?>
        <div class="pagination-controls">
            <button class="prev-page" disabled><i class="fas fa-chevron-left"></i></button>
            <span class="page-info">Page 1 of <?php echo ceil(count($data['players']) / $atts['players_per_page']); ?></span>
            <button class="next-page"><i class="fas fa-chevron-right"></i></button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Data Source -->
    <div class="data-source">
        <small>Data provided by NFL API • Last updated: <?php echo date('M j, Y g:i a'); ?></small>
    </div>
</div>