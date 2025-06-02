<div id="faucet-timer-container" class="faucet-timer-wrapper">
    <div class="faucet-timer-header">
        <h3>My Faucet Timer</h3>
        <p>Track your cryptocurrency faucets and PTC sites to maximize earnings!</p>
    </div>

    <details class="add-site-section">
        <summary class="add-site-summary">
            Add New Site <span class="add-site-arrow" aria-hidden="true">&#x25BC;</span>
        </summary>
        <form id="add-faucet-form" class="faucet-form" style="margin-top:16px;">
            <div class="form-row">
                <input type="text" id="site-name" placeholder="Site Name (e.g., FreeBitcoin)" required>
                <input type="url" id="site-url" placeholder="Site URL" required>
                <input type="number" id="timer-minutes" placeholder="Timer (minutes)" min="1" max="1440" required>
                <button type="submit">Add Site</button>
            </div>
        </form>
    </details>

    <div class="sites-section">
        <div class="sites-header">
            <h4>Your Faucet Sites</h4>
            <div class="controls">
                <button id="sort-by-status" class="btn-secondary">Sort by Status</button>
                <button id="sort-by-name" class="btn-secondary">Sort by Name</button>
                <button id="refresh-sites" class="btn-secondary">Refresh</button>
            </div>
        </div>
        
        <div id="sites-list" class="sites-list">
            <div class="loading">Loading your sites...</div>
        </div>
    </div>

    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number" id="total-sites">0</span>
                <span class="stat-label">Total Sites</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="ready-sites">0</span>
                <span class="stat-label">Ready Now</span>
            </div>
            <div class="stat-item">
                <span class="stat-number" id="waiting-sites">0</span>
                <span class="stat-label">Still Waiting</span>
            </div>
        </div>
    </div>
</div>
