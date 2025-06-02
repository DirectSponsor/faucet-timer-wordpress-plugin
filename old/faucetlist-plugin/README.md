# Faucet Timer Plugin for ClickForCharity

A WordPress plugin that allows users to track their cryptocurrency faucets and PTC (Pay-To-Click) sites with countdown timers. Perfect for managing multiple faucet sites and knowing exactly when you can claim your next rewards.

## Features

- ✅ **Personal Faucet Management**: Each user manages their own list of faucet sites
- ✅ **Countdown Timers**: Visual countdown showing time remaining until next claim
- ✅ **Site Tracking**: Store site names, URLs, and custom timer intervals
- ✅ **One-Click Visits**: Click "Visit Site" to open faucet and start countdown
- ✅ **User-Friendly Interface**: Clean, responsive design that works on all devices
- ✅ **Admin Panel**: Easy configuration through WordPress admin
- ✅ **Database Storage**: Secure storage of user data in WordPress database
- ✅ **AJAX Powered**: Smooth user experience without page reloads

## Installation

### Method 1: WordPress Admin Upload (Recommended)

1. **Download the Plugin**
   - Use the file `faucetlist-plugin-fixed.zip`

2. **Upload to WordPress**
   - Log into your WordPress admin panel
   - Go to **Plugins** → **Add New**
   - Click **Upload Plugin**
   - Choose the `faucetlist-plugin-fixed.zip` file
   - Click **Install Now**

3. **Activate the Plugin**
   - After installation, click **Activate Plugin**
   - You should see "Faucet Timer for ClickForCharity" in your plugins list

### Method 2: Manual Installation

1. **Extract the Plugin**
   - Unzip `faucetlist-plugin-fixed.zip`
   - Upload the `faucetlist-plugin` folder to `/wp-content/plugins/`

2. **Activate**
   - Go to **Plugins** in WordPress admin
   - Find "Faucet Timer for ClickForCharity" and click **Activate**

## Setup and Usage

### Adding the Faucet Timer to a Page

1. **Create or Edit a Page**
   - Go to **Pages** → **Add New** (or edit existing page)
   - This could be a page like "My Faucet Dashboard" or "Crypto Timers"

2. **Add the Shortcode**
   - In the page content, add the shortcode:
   ```
   [faucet_timer]
   ```

3. **Publish the Page**
   - Save/publish the page
   - The faucet timer interface will appear on this page

### Using the Faucet Timer

1. **Login Required**
   - Users must be logged in to use the faucet timer
   - Non-logged-in users will see a login prompt

2. **Adding Faucet Sites**
   - Click "Add New Site" button
   - Fill in:
     - **Site Name**: (e.g., "FreeBitcoin", "Cointiply")
     - **Site URL**: Full URL of the faucet site
     - **Timer (minutes)**: How long to wait between claims
   - Click "Add Site"

3. **Managing Your Faucets**
   - **Visit Site**: Opens faucet in new tab and starts countdown
   - **Delete**: Remove a faucet from your list
   - **Timer Display**: Shows remaining time until next claim

### Admin Configuration

- Go to **Settings** → **Faucet Timer** in WordPress admin
- Configure global plugin settings (if needed)

## Technical Details

### Database
The plugin creates a table `wp_faucet_timer_sites` with:
- User-specific faucet site storage
- Site names, URLs, and timer intervals
- Last visited timestamps
- Automatic cleanup on deactivation

### Security Features
- AJAX nonce verification
- User authentication checks
- SQL injection prevention
- XSS protection through WordPress sanitization

### Files Structure
```
faucetlist-plugin/
├── faucet-timer.php          # Main plugin file
├── admin-page.php            # Admin settings page
├── assets/
│   ├── faucet-timer.css      # Styling
│   └── faucet-timer.js       # JavaScript functionality
└── templates/
    ├── faucet-timer-display.php   # Main timer interface
    └── admin-page.php             # Admin template
```

## Customization

### CSS Styling
The plugin includes responsive CSS. You can customize the appearance by:
1. Editing `assets/faucet-timer.css`
2. Adding custom CSS to your theme
3. Using WordPress Customizer

### Timer Intervals
Users can set custom timer intervals for each faucet site:
- Common intervals: 5 minutes, 15 minutes, 1 hour, 24 hours
- Supports any custom minute value

## Troubleshooting

### Plugin Won't Activate
- Check that all files are properly uploaded
- Ensure correct file permissions
- Check PHP error logs

### Timers Not Working
- Verify JavaScript is enabled in browser
- Check browser console for errors
- Ensure jQuery is loaded

### Data Not Saving
- Check database permissions
- Verify AJAX requests are working
- Check WordPress debug logs

## Support

For support and updates:
- Website: https://clickforcharity.net
- Check WordPress admin for plugin updates

## Version History

### Version 1.0.0
- Initial release
- Basic faucet timer functionality
- User management system
- AJAX-powered interface
- Responsive design

## License

GPL v2 or later

---

**Created for ClickForCharity.net** - Making cryptocurrency faucet management easier and more efficient!

