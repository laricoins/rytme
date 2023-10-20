<?php
defined('ABSPATH') || die('');
/**
 * Class RytMeConfiguration
 */
class RytMeConfiguration {
    /**
     * Languages names
     *
     * @var array
     */
    /**
     * RytMeConfiguration constructor.
     *
     * @param array $languages_names Languages names
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'registerMenuPage'));
        add_action('admin_head', array($this, 'adminHead'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }
    /**
     * Function custom menu icon
     *
     * @return void
     */
    public function adminHead() {
        echo '<style>
    #toplevel_page_rytme .dashicons-before img {
            width: 25px;
            padding-top: 7px;
        }
  </style>';
    }
    /**
     * Register menu page
     *
     * @return void
     */
    public function registerMenuPage() {
        add_menu_page('Ryt Me', 'Ryt Me', 'manage_options', 'rytme', array($this, 'renderSettings'), RYTME_PLUGIN_URL . 'assets/images/logo.svg');
        add_submenu_page('Ryt Me', 'Ryt Me Settings', 'Settings', 'administrator', 'manage_options-settings', array($this, 'renderSettings'));
    }
    /**
     *  Render settings
     *
     * @return void
     */
    public function renderSettings() {
        // check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        $errors = [];
        if (isset($_POST['rytme_options'])) {
            if (empty($_POST['main_settings']) || !wp_verify_nonce(sanitize_text_field( wp_unslash ($_POST['main_settings'])), 'rytme-settings')) {
                echo '<div class="rytme_saved_wrap"><span class="material-icons"> done </span> ' . esc_html__('rytme settings saved!', 'rytme') . '</div>';
                die();
            }
            $login = sanitize_text_field($_POST['rytme_options']['login']);
            $pwsd = sanitize_text_field($_POST['rytme_options']['pwsd']);
            $fp = sanitize_text_field($_POST['rytme_options']['fp']);
            $rytme_options = array('login' => $login, 'pwsd' => $pwsd, 'fp' => $fp,);
            update_option('rytme_options', $rytme_options);
            echo '<div class="updated notice notice-success is-dismissible"><p>' . esc_html__('Settings saved!', 'rytme') . '</p> <button type="button" class="notice-dismiss">
      <span class="screen-reader-text">Dismiss this notice.</span>
      </button></div>';
        }
        require_once (RYTME_PLUGIN_PATH . 'src/admin/views' . DIRECTORY_SEPARATOR . 'view.php');
    }
   
    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueueScripts() {
        global $current_screen;
        if (!empty($current_screen) && $current_screen->id === 'toplevel_page_rytme') {
            wp_register_script('vue-js1', RYTME_PLUGIN_URL . 'assets/js/vue.js',  [], '', true);
            wp_register_script('bootstrap-vue', RYTME_PLUGIN_URL . 'assets/js/bootstrap-vue.js', [], '', true);
            // your app code
            wp_register_script('my-app', RYTME_PLUGIN_URL . 'assets/js/my-app.js', [], mt_rand(9999, 999999), true);
            wp_enqueue_script('vue-js1');
		  wp_enqueue_script('vue-json-excel', RYTME_PLUGIN_URL . 'assets/js/vue-json-excel.umd.min.js', [], '', true);
		  wp_enqueue_script('bootstrap-vue-icons', RYTME_PLUGIN_URL . 'assets/js/bootstrap-vue-icons.min.js', [], '', true);
			
			
            wp_enqueue_script('bootstrap-vue');
			    wp_localize_script('my-app', 'appm', ['ajax_url' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('api-nonce'), ]);
            wp_enqueue_script('my-app');
            wp_enqueue_style("admin_css", RYTME_PLUGIN_URL . "css/admin.css");
            wp_enqueue_style("font_awesome_css", RYTME_PLUGIN_URL . "css/font-awesome.css");
            wp_enqueue_style("bootstrap.min-css", RYTME_PLUGIN_URL . "assets/css/bootstrap.min.css");
            wp_enqueue_style("bootstrap-vue-css",RYTME_PLUGIN_URL . "assets/css/bootstrap-vue.css");
         //   wp_enqueue_script('rytme-admin', RYTME_PLUGIN_URL . "assets/js/admin.js", array('jquery'), mt_rand(9999, 999999), true);
          //  wp_localize_script('rytme-admin', 'api_object', ['ajax_url' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('api-nonce'), ]);
        }
    }
}
new RytMeConfiguration();
