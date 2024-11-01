<?php

/**
 * @wordpress-plugin
 * Plugin Name:       WalkyTalky
 * Description:       Chat live in realtime with your website visitors with WalkyTalky. Requires a WalkyTalky account.
 * Version:           1.1.0
 * Author:            WalkyTalky
 * Author URI:        https://www.walkytalky.io
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       walkytalky
 * Domain Path:       /languages
 */


class WalkyTalky {

    public function __construct() {

    }

    public function run() {
        require_once(plugin_dir_path(__FILE__).'includes/walkytalky-config.php');
        require_once(plugin_dir_path(__FILE__).'includes/walkytalky-migrations.php');
        require_once(plugin_dir_path(__FILE__).'admin/walkytalky-admin.php');
        require_once(plugin_dir_path(__FILE__).'public/walkytalky-public.php');

        $config = new WalkyTalkyConfig();

        $migrations = new WalkyTalkyMigrations($config);
        $migrations->run();

        $admin = new WalkyTalkyAdmin($config);
        $admin->run();

        $public = new WalkyTalkyPublic($config);
        $public->run();

    }

}

function runWalkyTalky() {
    $plugin = new WalkyTalky();
    $plugin->run();
}
runWalkyTalky();
