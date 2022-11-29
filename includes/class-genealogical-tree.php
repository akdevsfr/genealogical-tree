<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link    https://wordpress.org/plugins/genealogical-tree
 * @since   1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */
namespace Zqe;

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
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since  1.0.0
     * @access  protected
     * @var   Genealogical_Tree_Loader  $loader  Maintains and registers all hooks for the plugin.
     */
    protected  $loader ;
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     Genealogical_Tree_Helper  $helper  Maintains and registers all hooks for the plugin.
     */
    public  $helper ;
    /**
     * The unique identifier of this plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     string  $name  The string used to uniquely identify this plugin.
     */
    public  $name ;
    /**
     * The current version of the plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     string  $version  The current version of the plugin.
     */
    public  $version ;
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        
        if ( defined( 'GENEALOGICAL_TREE_VERSION' ) ) {
            $this->version = GENEALOGICAL_TREE_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        
        $this->name = 'genealogical-tree';
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_api_hooks();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since  1.0.0
     * @access  private
     */
    private function load_dependencies()
    {
        $this->loader = new \Zqe\Genealogical_Tree_Loader();
        $this->helper = new \Zqe\Genealogical_Tree_Helper();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Genealogical_Tree_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since  1.0.0
     * @access  private
     */
    private function set_locale()
    {
        $plugin_i18n = new \Zqe\Genealogical_Tree_i18n();
        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access  private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new \Zqe\Genealogical_Tree_Admin( $this );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $plugin_admin, 'init_post_type_and_taxonomy' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
        // Meta Box.
        $this->loader->add_action(
            'add_meta_boxes',
            $plugin_admin,
            'add_meta_boxes_gt_member',
            10,
            2
        );
        $this->loader->add_action(
            'add_meta_boxes',
            $plugin_admin,
            'add_meta_boxes_gt_family',
            10,
            2
        );
        $this->loader->add_action(
            'add_meta_boxes',
            $plugin_admin,
            'add_meta_boxes_gt_tree',
            10,
            2
        );
        // Meta Update.
        $this->loader->add_action( 'post_updated', $plugin_admin, 'update_meta_boxes_gt_member' );
        $this->loader->add_action( 'post_updated', $plugin_admin, 'update_meta_boxes_gt_tree' );
        // Member columns.
        $this->loader->add_filter( 'manage-gt-member_posts_columns', $plugin_admin, 'member_posts_columns' );
        $this->loader->add_action(
            'manage_gt-member_posts_custom_column',
            $plugin_admin,
            'member_posts_custom_column',
            10,
            2
        );
        $this->loader->add_filter( 'manage_edit-gt-member_sortable_columns', $plugin_admin, 'member_sortable_columns' );
        // Tree columns.
        $this->loader->add_filter( 'manage_gt-tree_posts_columns', $plugin_admin, 'tree_posts_columns' );
        $this->loader->add_action(
            'manage_gt-tree_posts_custom_column',
            $plugin_admin,
            'tree_posts_custom_column',
            10,
            2
        );
        // Member page tab.
        $this->loader->add_action( 'init', $plugin_admin, 'init_add_rewrite_rule_gt_member_tab' );
        $this->loader->add_filter(
            'query_vars',
            $plugin_admin,
            'query_vars_gt_member_tab',
            10,
            1
        );
        // Update user role.
        $this->loader->add_action( 'user_register', $plugin_admin, 'user_register_action' );
        $this->loader->add_action( 'before_delete_post', $plugin_admin, 'before_delete_post' );
        $this->loader->add_filter(
            'post_class',
            $plugin_admin,
            'post_class_filter',
            10,
            3
        );
        // Buddy Press Support.
        $this->loader->add_filter(
            'user_has_cap',
            $plugin_admin,
            'bp_manage_capabilities',
            1,
            4
        );
        $this->loader->add_action( 'bp_setup_nav', $plugin_admin, 'bp_family_tree_tab' );
        $this->loader->add_action( 'bp_template_title', $plugin_admin, 'bp_family_tree_title' );
        $this->loader->add_action( 'bp_template_content', $plugin_admin, 'bp_family_tree_content' );
        $this->loader->add_action( 'admin_post_process_export_post', $plugin_admin, 'process_export_post' );
        $this->loader->add_action( 'admin_post_process_import_post', $plugin_admin, 'process_import_post' );
        $plugin_upgrade = new \Zqe\Genealogical_Tree_Upgrade();
        // ver_upgrade.
        $this->loader->add_action( 'wp_ajax_fix_ver_upgrade_ajax', $plugin_upgrade, 'ver_upgrade' );
        $this->loader->add_action( 'wp_ajax_nopriv_fix_ver_upgrade_ajax', $plugin_upgrade, 'ver_upgrade' );
        if ( !get_site_option( '_gt_version_fixed_through_notice' ) ) {
            $this->loader->add_action( 'admin_notices', $plugin_upgrade, 'admin_notice__info' );
        }
        $plugin_admin_family = new \Zqe\Inc\Genealogical_Tree_Admin_Family_Group( $this );
        $this->loader->add_filter(
            'parent_file',
            $plugin_admin_family,
            'set_family_group_current_menu',
            100
        );
        $this->loader->add_action( 'wp_ajax_generate_default_tree', $plugin_admin_family, 'generate_default_tree' );
        $this->loader->add_action( 'wp_ajax_nopriv_generate_default_tree', $plugin_admin_family, 'generate_default_tree' );
        $this->loader->add_action( 'admin_notices', $plugin_admin_family, 'family_group_validation_notice_handler' );
        $this->loader->add_action( 'create_gt-family-group', $plugin_admin_family, 'create_family_group' );
        $this->loader->add_action(
            'edited_gt-family-group',
            $plugin_admin_family,
            'update_family_group',
            10,
            2
        );
        $this->loader->add_action( 'admin_init', $plugin_admin_family, 'add_family_group_meta' );
        // Filter terms query args to filter gt-family-group by user.
        $this->loader->add_filter(
            'get_terms_args',
            $plugin_admin_family,
            'gt_family_group_filter',
            10,
            2
        );
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access  private
     */
    private function define_api_hooks()
    {
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since  1.0.0
     * @access  private
     */
    private function define_public_hooks()
    {
        $plugin_public = new \Zqe\Genealogical_Tree_Public( $this );
        $this->loader->add_action( 'init', $plugin_public, 'process_registration_post' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_filter( 'the_content', $plugin_public, 'data_in_single_page' );
        $this->loader->add_action( 'pre_get_posts', $plugin_public, 'pre_get_posts' );
        $this->loader->add_action( 'login_form_middle', $plugin_public, 'add_lost_password_link' );
        add_shortcode( 'tree', array( $plugin_public, 'tree_shortcode' ) );
        add_shortcode( 'gt-tree', array( $plugin_public, 'gt_tree_shortcode' ) );
        add_shortcode( 'gt-tree-list', array( $plugin_public, 'gt_tree_list_shortcode' ) );
        add_shortcode( 'gt-member', array( $plugin_public, 'gt_member_shortcode' ) );
        add_shortcode( 'gt-members', array( $plugin_public, 'gt_members_shortcode' ) );
        add_shortcode( 'gt-user-registration', array( $plugin_public, 'gt_user_registration_shortcode' ) );
        add_shortcode( 'gt-user-login', array( $plugin_public, 'gt_user_login_shortcode' ) );
    }
    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since  1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since   1.0.0
     * @return  string  The name of the plugin.
     */
    public function get_name()
    {
        return $this->name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since   1.0.0
     * @return  Genealogical_Tree_Loader  Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since   1.0.0
     * @return  Genealogical_Tree_Helper  Orchestrates the hooks of the plugin.
     */
    public function get_helper()
    {
        return $this->helper;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since   1.0.0
     * @return  string  The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}