<?php
class WalkyTalkyPublic {

    public function __construct($config) {
        $this->config = $config;

    }

    public function run() {
        add_action('wp_footer', function() {

            $siteId = get_option($this->config->optionsPrefix.'site_id');
            if(!$siteId) {
                return;
            }

            wp_enqueue_script( 'walkytalky-script','https://widget.walkytalky.io/load');
            wp_add_inline_script( 'walkytalky-script', ' window.chatSettings = ' . json_encode( array(
                    'siteId' => intval($siteId)
                )), 'before' );

        });


    }

}
