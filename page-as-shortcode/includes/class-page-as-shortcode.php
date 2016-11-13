<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://hayatbiralem.com
 * @since      1.0.0
 *
 * @package    Page_As_Shortcode
 * @subpackage Page_As_Shortcode/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Page_As_Shortcode
 * @subpackage Page_As_Shortcode/includes
 * @author     Ömür Yanıkoğlu <hayatbiralem@gmail.com>
 */
class Page_As_Shortcode {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Page_As_Shortcode_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'page-as-shortcode';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Page_As_Shortcode_Loader. Orchestrates the hooks of the plugin.
	 * - Page_As_Shortcode_i18n. Defines internationalization functionality.
	 * - Page_As_Shortcode_Admin. Defines all hooks for the admin area.
	 * - Page_As_Shortcode_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-page-as-shortcode-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-page-as-shortcode-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-page-as-shortcode-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-page-as-shortcode-public.php';

		$this->loader = new Page_As_Shortcode_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Page_As_Shortcode_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Page_As_Shortcode_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Page_As_Shortcode_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Page_As_Shortcode_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
        $this->init_shortcodes();
		// $this->loader->run();
	}

	/**
	 * Init shortcodes.
	 *
	 * @since    1.0.0
	 */
	public function init_shortcodes() {
        if(!is_admin()) {
            add_shortcode('page', array($this, 'do_page_content'));
        }
	}

	/**
	 * Do page content.
	 *
	 * @since    1.0.0
	 */
	public function do_page_content( $atts, $content = null, $tag = '' ) {

	    // Get atts
        $atts = shortcode_atts( array(
            'id' => null,
            'slug' => null,
            'delete' => null
        ), $atts, 'page_as_shortcode' );

        // Get page by id or slug
        if(!empty($atts['id'])){
            $post = get_post(trim($atts['id']));
        } else if(!empty($atts['slug'])){
            $posts = new WP_Query( array( 'posts_per_page' => 1, 'pagename' => trim($atts['slug']) ) );
            if($posts) {
                $post = $posts->posts[0];
            }
        }

        // Override content if page exists
        if(isset($post)){
            $content = $post->post_content;
            // delete
            if(!empty($atts['delete'])){
                $atts['delete'] = explode(',', $atts['delete']);
                $to_delete = array();
                foreach($atts['delete'] as $delete){
                    $to_delete[$delete] = '';
                }
                $unwanted_shortcodes_regex = $this->get_unwanted_shortcodes_regex($to_delete);
            }
            $content = preg_replace($unwanted_shortcodes_regex, '', $content);
        }

        // Force wpautop in shortcode?
        if ( isset( $atts['wpautop'] ) && trim( $atts['wpautop'] ) == 'true') {
            $content = wpautop( $content );
        }

        // Return content
        return do_shortcode( $content );
    }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_unwanted_shortcodes_regex($delete_shortcode_tags) {
        // The  $shortcode_tags global variable contains all registered shortcodes.
        global $shortcode_tags;

        // Store the shortcode_tags global in a temporary variable.
        $temp_shortcode_tags = $shortcode_tags;

        // Add only one specific shortcode name to the $shortcode_tags global.
        //
        // Replace 'related_posts_by_tax' with the shortcode you want to get the regex for.
        // Don't include the brackets from a shortcode.
        $shortcode_tags = $delete_shortcode_tags;

        // Create the regex for your shortcode.
        $regex = '/' . get_shortcode_regex() . '/s';

        // Restore the $shortcode_tags global.
        $shortcode_tags = $temp_shortcode_tags;

        // Return the regex.
        return $regex;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Page_As_Shortcode_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
