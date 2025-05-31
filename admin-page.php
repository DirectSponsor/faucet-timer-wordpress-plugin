<div class="wrap">
    <h1>Faucet Timer Settings</h1>
    
    <div class="card">
        <h2>Usage Instructions</h2>
        <p>To display the Faucet Timer on any page or post, use the shortcode:</p>
        <code>[faucet_timer]</code>
        
        <h3>Features:</h3>
        <ul>
            <li>Each logged-in user gets their own personal faucet list</li>
            <li>Automatic countdown timers for each site</li>
            <li>Visual indicators for ready/waiting sites</li>
            <li>Data is saved per user in the database</li>
            <li>Responsive design works on mobile and desktop</li>
        </ul>
        
        <h3>User Instructions:</h3>
        <ol>
            <li>Users must be logged in to use the faucet timer</li>
            <li>Add sites with name, URL, and timer duration (in minutes)</li>
            <li>Click "Visit & Start Timer" to visit a site and start the countdown</li>
            <li>Sites will show as "Ready" when the timer expires</li>
            <li>Use the sorting options to organize sites by status or name</li>
        </ol>
    </div>
    
    <div class="card">
        <h2>Database Statistics</h2>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . 'faucet_timer_sites';
        
        $total_sites = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $total_users = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM $table_name");
        
        echo "<p>Total Sites Added: <strong>$total_sites</strong></p>";
        echo "<p>Users with Sites: <strong>$total_users</strong></p>";
        ?>
    </div>
</div>