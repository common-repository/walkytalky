<?php

class WalkyTalkyAdmin {
    private $config;
    private $api;

    public function __construct($config) {
        require_once("walkytalky-admin-api.php");
        $this->config = $config;
        $this->api = new WalkyTalkyAdminApi($config);
    }

    public function run() {
        $this->createAdminLink();
        $this->api->buildRestApi();

    }


    private function createAdminLink() {
        add_action( 'admin_menu', function () {
            add_menu_page(
                'WalkyTalky',
                'WalkyTalky',
                'manage_options',
                $this->config->adminPageSlug,
                [$this,'renderAdminSettingsPage'],
                $this->config->adminPluginIcon
            );
        });


        // Link to settings page from plugins screen
        add_filter( 'plugin_action_links_walkytalky/walkytalky.php', function ($links) {
            $myLinks = array(
                '<a href="'.menu_page_url( $this->config->adminPageSlug ).'">Settings</a>',
            );
            return array_merge($myLinks , $links);
        });

    }


    function renderAdminSettingsPage() {
        $pluginSettings = get_option($this->config->adminPageSlug);
        if(isset($pluginSettings['access_token'])) {
            unset($pluginSettings['access_token']);
        }
        ?>
        <script type="text/javascript">
            const walkyTalkyWpApiUrl = "<?php echo get_rest_url(null, $this->config->adminPageSlug.'/v1/ajax'); ?>";
        </script>
        <div id="walkytalky-wp-app"></div>
        <?php

        wp_enqueue_script(
            $this->config->adminPageSlug.'vendors',
            plugin_dir_url( __FILE__ ). 'walkytalky-wp/dist/js/chunk-vendors.js',
            array(),
            $this->config->devMode?time():$this->config->version,
            true
        );
        wp_enqueue_script(
            $this->config->adminPageSlug,
            plugin_dir_url( __FILE__ ). 'walkytalky-wp/dist/js/app.js',
            array(),
            $this->config->devMode?time():$this->config->version,
            true
        );
        wp_localize_script( $this->config->adminPageSlug, 'wpApiSettings', array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' )
        ) );
        wp_enqueue_style(
            $this->config->adminPageSlug.'vendorsstyle',
            plugin_dir_url( __FILE__ ). 'walkytalky-wp/dist/css/chunk-vendors.css',
            array(),
            $this->config->devMode?time():$this->config->version
        );
        wp_enqueue_style(
            $this->config->adminPageSlug.'appstyle',
            plugin_dir_url( __FILE__ ). 'walkytalky-wp/dist/css/app.css',
            array(),
            $this->config->devMode?time():$this->config->version
        );
        wp_enqueue_style(
            $this->config->adminPageSlug.'icons',
            "https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css",
            array(),
            $this->config->devMode?time():$this->config->version
        );
    }
}
