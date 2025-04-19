jQuery(document).ready(function($) {
    // Toggle between simple and full views
    $('.nfl-team-display').on('click', '.toggle-view', function() {
        const $container = $(this).closest('.nfl-team-display');
        const $fullRoster = $container.find('.full-roster');
        const isShowing = $fullRoster.is(':visible');
        
        $fullRoster.toggle(!isShowing);
        $(this).text(isShowing ? 'Show Full Roster' : 'Hide Full Roster');
    });

    // Player search functionality
    $('.nfl-team-display').on('input', '.player-search', function() {
        const searchTerm = $(this).val().toLowerCase();
        const position = $(this).siblings('.position-filter').val();
        
        $(this).closest('.full-roster').find('.player-card').each(function() {
            const $card = $(this);
            const name = $card.find('h3').text().toLowerCase();
            const playerPos = $card.data('position');
            
            const nameMatch = name.includes(searchTerm);
            const posMatch = position === '' || playerPos === position;
            
            $card.toggle(nameMatch && posMatch);
        });
    });

    // Position filter
    $('.nfl-team-display').on('change', '.position-filter', function() {
        $(this).siblings('.player-search').trigger('input');
    });

    // Raw data toggle
    $('.nfl-team-display').on('click', '.toggle-raw', function() {
        const $pre = $(this).siblings('pre');
        $pre.toggle();
        $(this).text($pre.is(':visible') ? 'Hide Raw Data' : 'Show Raw Data');
    });

    // Initialize default view
    $('.nfl-team-display[data-view="full"] .toggle-view').trigger('click');
});