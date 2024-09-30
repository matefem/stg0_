<?php
/**
 * Plugin Name: Simple Button Plugin
 * Plugin URI:  https://example.com/
 * Description: A simple plugin that adds a button to the WordPress admin sidebar.
 * Version:     1.0
 * Author:      Your Name
 * Author URI:  https://example.com/
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit;
}

// Hook to add a menu item in the admin sidebar
add_action('admin_menu', 'simple_button_plugin_menu');

function simple_button_plugin_menu() {
    // Add a top-level menu item
    add_menu_page(
        'Simple Button',         // Page title
        'Simple Button',         // Menu title
        'manage_options',        // Capability
        'simple-button-plugin',  // Menu slug
        'simple_button_page',    // Callback function to display the page
        'dashicons-admin-generic', // Icon URL
        6                        // Position in the menu
    );
}

// Callback function to display the button page
function simple_button_page() {
    ?>
    <div class="wrap">
        <h1>Simple Button Plugin</h1>
        <p>This is a simple button plugin. It doesn't do anything right now.</p>
        <button class="button button-primary">Click Me</button>
    </div>
    <?php
}
