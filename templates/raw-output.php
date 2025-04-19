<div class="api-raw-output" data-team-id="<?php echo esc_attr($atts['team_id']); ?>">
    <h3>Raw API Data for Team ID: <?php echo esc_html($atts['team_id']); ?></h3>
    <div class="json-controls">
        <button class="copy-json">Copy JSON</button>
        <button class="toggle-json">Toggle Format</button>
    </div>
    <pre><code class="json-data"><?php 
        echo $atts['pretty_print'] 
            ? json_encode($data, JSON_PRETTY_PRINT) 
            : json_encode($data); 
    ?></code></pre>
</div>