<?php
/**
 * Plugin Name: Tahesab Connector
 * Description: اتصال وردپرس و ووکامرس به API حسابداری ته حساب
 * Version: 1.0.0
 * Author: ITistan
 * Requires at least: 5.0
 * Requires PHP: 7.0
 * Text Domain: tahesab-connector
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 7.0
 */


if (!defined('ABSPATH')) {
    exit;
}

// بررسی فعال بودن WooCommerce
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    function tahesab_woocommerce_notice() {
        ?>
        <div class="error">
            <p><?php _e('افزونه Tahesab Connector برای عملکرد صحیح به WooCommerce نیاز دارد.', 'tahesab-connector'); ?></p>
        </div>
        <?php
    }
    add_action('admin_notices', 'tahesab_woocommerce_notice');
    return;
}

require_once plugin_dir_path(__FILE__) . 'includes/activations.php';
require_once plugin_dir_path(__FILE__) . 'includes/deactivations.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';

register_activation_hook(__FILE__, 'tahesab_activate');
register_deactivation_hook(__FILE__, 'tahesab_deactivate');

// اضافه کردن اکشن‌ها
add_action('admin_post_save_tahesab_settings', 'tahesab_save_settings');
add_action('wp_ajax_update_tahesab_inventory', 'tahesab_update_inventory');

function tahesab_save_settings() {
    require_once plugin_dir_path(__FILE__) . 'app/controllers/SettingsController.php';
    $controller = new SettingsController();
    $controller->save();
}

function tahesab_update_inventory() {
    require_once plugin_dir_path(__FILE__) . 'app/controllers/InventoryController.php';
    $controller = new InventoryController();
    $controller->update();
    wp_die();
}