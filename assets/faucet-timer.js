(function($) {
    let sites = [];
    
    $(document).ready(function() {
        loadSites();
        bindEvents();
        setInterval(updateTimers, 1000);
    });
    
    function bindEvents() {
        $('#add-faucet-form').on('submit', function(e) {
            e.preventDefault();
            addSite();
        });
        
        $('#sort-by-status').on('click', function() {
            sortSites('status');
        });
        
        $('#refresh-sites').on('click', function() {
            loadSites();
        });
    }
    
    function addSite() {
        const siteName = $('#site-name').val().trim();
        const siteUrl = $('#site-url').val().trim();
        const timerMinutes = $('#timer-minutes').val();
        
        if (!siteName || !siteUrl || !timerMinutes) {
            alert('Please fill in all fields.');
            return;
        }
        
        $.ajax({
            url: faucet_timer_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'save_faucet_sites',
                nonce: faucet_timer_ajax.nonce,
                site_name: siteName,
                site_url: siteUrl,
                timer_minutes: timerMinutes
            },
            success: function(response) {
                if (response.success) {
                    alert('Site added successfully!');
                    $('#add-faucet-form')[0].reset();
                    loadSites();
                } else {
                    alert('Failed to add site.');
                }
            }
        });
    }
    
    function loadSites() {
        $.ajax({
            url: faucet_timer_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_faucet_sites',
                nonce: faucet_timer_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    sites = response.data;
                    renderSites();
                    updateStats();
                }
            }
        });
    }
    
    function renderSites() {
        const $sitesList = $('#sites-list');
        
        if (sites.length === 0) {
            $sitesList.html('<div>No sites added yet.</div>');
            return;
        }
        
        let html = '';
        sites.forEach(function(site) {
            const status = getSiteStatus(site);
            const timeRemaining = getTimeRemaining(site);
            
            html += `
                <div class="site-item ${status}" data-site-id="${site.id}">
                    <div class="site-info">
                        <div class="site-name">${site.site_name}</div>
                        <div class="site-url">${site.site_url}</div>
                    </div>
                    <div class="timer-info">
                        <div>${status === 'ready' ? 'Ready!' : timeRemaining}</div>
                    </div>
                    <div class="site-actions">
                        <a href="${site.site_url}" target="_blank" class="btn-visit" 
                           onclick="markAsVisited(${site.id})">
                            Visit & Start Timer
                        </a>
                        <button class="btn-delete" onclick="deleteSite(${site.id})">
                            Delete
                        </button>
                    </div>
                </div>
            `;
        });
        
        $sitesList.html(html);
    }
    
    function getSiteStatus(site) {
        if (!site.last_visited_utc) return 'ready';
        
        const now = new Date().getTime(); // Current time in UTC milliseconds
        const timeDiff = now - site.last_visited_utc;
        const timerDuration = site.timer_minutes * 60 * 1000;
        
        return timeDiff >= timerDuration ? 'ready' : 'waiting';
    }
    
    function getTimeRemaining(site) {
        if (!site.last_visited_utc) return '00:00:00';
        
        const now = new Date().getTime(); // Current time in UTC milliseconds
        const timeDiff = now - site.last_visited_utc;
        const timerDuration = site.timer_minutes * 60 * 1000;
        const remaining = timerDuration - timeDiff;
        
        if (remaining <= 0) return '00:00:00';
        
        const hours = Math.floor(remaining / (1000 * 60 * 60));
        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    function updateTimers() {
        sites.forEach(function(site) {
            const status = getSiteStatus(site);
            const timeRemaining = getTimeRemaining(site);
            const $siteItem = $(`.site-item[data-site-id="${site.id}"]`);
            
            // Update status classes
            $siteItem.removeClass('ready waiting').addClass(status);
            
            // Update timer text
            const $timerInfo = $siteItem.find('.timer-info div');
            $timerInfo.text(status === 'ready' ? 'Ready!' : timeRemaining);
        });
        updateStats();
    }
    
    function updateStats() {
        const totalSites = sites.length;
        const readySites = sites.filter(site => getSiteStatus(site) === 'ready').length;
        const waitingSites = totalSites - readySites;
        
        $('#total-sites').text(totalSites);
        $('#ready-sites').text(readySites);
        $('#waiting-sites').text(waitingSites);
    }
    
    function sortSites(sortBy) {
        if (sortBy === 'status') {
            sites.sort((a, b) => {
                const statusA = getSiteStatus(a);
                const statusB = getSiteStatus(b);
                if (statusA === 'ready' && statusB !== 'ready') return -1;
                if (statusA !== 'ready' && statusB === 'ready') return 1;
                return a.site_name.localeCompare(b.site_name);
            });
        } else {
            sites.sort((a, b) => a.site_name.localeCompare(b.site_name));
        }
        renderSites();
    }
    
    window.markAsVisited = function(siteId) {
        $.ajax({
            url: faucet_timer_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'update_visit_time',
                nonce: faucet_timer_ajax.nonce,
                site_id: siteId
            },
            success: function(response) {
                if (response.success) {
                    // Reload sites to get the correct server timestamp
                    loadSites();
                }
            }
        });
    };
    
    window.deleteSite = function(siteId) {
        if (!confirm('Are you sure?')) return;
        
        $.ajax({
            url: faucet_timer_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_faucet_site',
                nonce: faucet_timer_ajax.nonce,
                site_id: siteId
            },
            success: function(response) {
                if (response.success) {
                    loadSites();
                }
            }
        });
    };
    
})(jQuery);
