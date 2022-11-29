<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */
namespace Zqe;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   public
     * @var      Genealogical_Tree    $plugin    The ID of this plugin.
     */
    public  $plugin ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string $plugin       The name of this plugin.
     */
    public function __construct( $plugin )
    {
        $this->plugin = $plugin;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin->name . '-select2-css',
            plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
            array(),
            $this->plugin->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-admin.css',
            array( 'wp-color-picker' ),
            $this->plugin->version,
            'all'
        );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin->name . '-select2-js',
            plugin_dir_url( __FILE__ ) . 'js/select2.min.js',
            array( 'jquery', 'wp-color-picker' ),
            $this->plugin->version,
            false
        );
        wp_enqueue_script(
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-admin.js',
            array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
            $this->plugin->version,
            false
        );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        if ( !did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        wp_localize_script( $this->plugin->name, 'gt_ajax_var', array(
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'gt_ajax_nonce' ),
        ) );
    }
    
    /**
     * It registers the custom post types and taxonomies.
     *
     * @since    1.0.0
     */
    public function init_post_type_and_taxonomy()
    {
        $labels = array(
            'name'               => _x( 'Members', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Member', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Members', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Member', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'member', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Member', 'genealogical-tree' ),
            'new_item'           => __( 'New Member', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Member', 'genealogical-tree' ),
            'view_item'          => __( 'View Member', 'genealogical-tree' ),
            'all_items'          => __( 'Members', 'genealogical-tree' ),
            'search_items'       => __( 'Search Members', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Members:', 'genealogical-tree' ),
            'featured_image'     => __( 'Member Image', 'genealogical-tree' ),
            'set_featured_image' => __( 'Set Member Image', 'genealogical-tree' ),
            'not_found'          => __( 'No members found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No members found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title', 'author', 'revisions' );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            array_push( $supports, 'custom-fields' );
        }
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'genealogical-tree',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'gt-member',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        register_post_type( 'gt-member', $args );
        $labels = array(
            'name'               => _x( 'Families', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Family', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Families', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Family', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'family', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Family', 'genealogical-tree' ),
            'new_item'           => __( 'New Family', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Family', 'genealogical-tree' ),
            'view_item'          => __( 'View Family', 'genealogical-tree' ),
            'all_items'          => __( 'Families', 'genealogical-tree' ),
            'search_items'       => __( 'Search Families', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Families:', 'genealogical-tree' ),
            'not_found'          => __( 'No families found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No families found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title', 'author', 'revisions' );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            array_push( $supports, 'custom-fields' );
        }
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'gt-family',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            $args['show_in_menu'] = 'genealogical-tree';
        }
        register_post_type( 'gt-family', $args );
        $labels = array(
            'name'               => _x( 'Trees', 'post type general name', 'genealogical-tree' ),
            'singular_name'      => _x( 'Tree', 'post type singular name', 'genealogical-tree' ),
            'menu_name'          => _x( 'Trees', 'admin menu', 'genealogical-tree' ),
            'name_admin_bar'     => _x( 'Tree', 'add new on admin bar', 'genealogical-tree' ),
            'add_new'            => _x( 'Add New', 'tree', 'genealogical-tree' ),
            'add_new_item'       => __( 'Add New Tree', 'genealogical-tree' ),
            'new_item'           => __( 'New Tree', 'genealogical-tree' ),
            'edit_item'          => __( 'Edit Tree', 'genealogical-tree' ),
            'view_item'          => __( 'View Tree', 'genealogical-tree' ),
            'all_items'          => __( 'Trees', 'genealogical-tree' ),
            'search_items'       => __( 'Search Trees', 'genealogical-tree' ),
            'parent_item_colon'  => __( 'Parent Trees:', 'genealogical-tree' ),
            'not_found'          => __( 'No trees found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No trees found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title', 'author', 'revisions' );
        $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'genealogical-tree' ),
            'public'             => true,
            'show_in_rest'       => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => 'genealogical-tree',
            'query_var'          => true,
            'rewrite'            => array(
            'slug' => 'gt-tree',
        ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => $supports,
            'map_meta_cap'       => true,
        );
        register_post_type( 'gt-tree', $args );
        $labels = array(
            'name'                       => _x( 'Family Groups', 'genealogical-tree', 'genealogical-tree' ),
            'singular_name'              => _x( 'Family Group', 'taxonomy singular name', 'genealogical-tree' ),
            'search_items'               => __( 'Search Family Groups', 'genealogical-tree' ),
            'popular_items'              => __( 'Popular Family Groups', 'genealogical-tree' ),
            'all_items'                  => __( 'All Family Groups', 'genealogical-tree' ),
            'parent_item'                => __( 'Parent Family Group', 'genealogical-tree' ),
            'parent_item_colon'          => __( 'Parent Family Group', 'genealogical-tree' ),
            'edit_item'                  => __( 'Edit Family Group', 'genealogical-tree' ),
            'update_item'                => __( 'Update Family Group', 'genealogical-tree' ),
            'add_new_item'               => __( 'Add New Group', 'genealogical-tree' ),
            'new_item_name'              => __( 'New Group Name', 'genealogical-tree' ),
            'separate_items_with_commas' => __( 'Separate family group with commas', 'genealogical-tree' ),
            'add_or_remove_items'        => __( 'Add or remove family group', 'genealogical-tree' ),
            'choose_from_most_used'      => __( 'Choose from the most used family group', 'genealogical-tree' ),
            'not_found'                  => __( 'No family group found.', 'genealogical-tree' ),
            'menu_name'                  => __( 'Family Groups', 'genealogical-tree' ),
        );
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_in_rest'          => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array(
            'slug' => 'gt-family-group',
        ),
        );
        register_taxonomy( 'gt-family-group', array( 'gt-member', 'gt-family' ), $args );
    }
    
    /**
     * It adds a menu item to the admin menu
     *
     * @since    1.0.0
     */
    public function admin_menu()
    {
        add_menu_page(
            __( 'Genealogical Tree', 'genealogical-tree' ),
            __( 'Genealogical Tree', 'genealogical-tree' ),
            'manage_categories',
            'genealogical-tree',
            function () {
        },
            plugin_dir_url( __FILE__ ) . 'img/menu-icon.png',
            4
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Genealogical Tree', 'genealogical-tree' ),
            __( 'Genealogical Tree', 'genealogical-tree' ),
            'manage_categories',
            'genealogical-tree',
            function () {
            require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-admin-dashboard.php';
        },
            0
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Family Group', 'genealogical-tree' ),
            __( 'Family Group', 'genealogical-tree' ),
            'manage_categories',
            'edit-tags.php?taxonomy=gt-family-group&post_type=gt-member',
            null,
            1
        );
    }
    
    /**
     * It adds meta boxes to the gt-member post type
     *
     * Long Description.
     *
     * @param string $post_type The post type slug.
     * @param object $post  The post object..
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_member( $post_type, $post )
    {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        add_meta_box(
            'genealogical-tree-meta-box-member-info',
            __( 'Member Info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_member_info' ),
            'gt-member',
            'normal',
            'high'
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-member-debug',
                __( 'Member Debug', 'genealogical-tree' ),
                array( $this, 'render_meta_box_member_debug' ),
                'gt-member',
                'normal',
                'high'
            );
        }
    }
    
    /**
     * It adds a meta box to the Family post type
     *
     * Long Description.
     *
     * @param string $post_type The post type of the current post.
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_family( $post_type, $post )
    {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-family-debug',
                __( 'Family info', 'genealogical-tree' ),
                array( $this, 'render_meta_box_family_debug' ),
                'gt-family',
                'normal',
                'high'
            );
        }
    }
    
    /**
     * It adds a meta box to the tree post type
     *
     * Long Description.
     *
     * @param string $post_type The post type of the current post.
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_gt_tree( $post_type, $post )
    {
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
        add_meta_box(
            'genealogical-tree-meta-box-tree-settings',
            __( 'Tree Settings', 'genealogical-tree' ),
            array( $this, 'render_meta_box_tree_settings' ),
            'gt-tree',
            'normal',
            'high'
        );
        if ( defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            add_meta_box(
                'genealogical-tree-meta-box-tree-debug',
                __( 'Family info', 'genealogical-tree' ),
                array( $this, 'render_meta_box_tree_debug' ),
                'gt-tree',
                'normal',
                'high'
            );
        }
    }
    
    /**
     * It renders the meta box for the member info.
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_info( $post )
    {
        $name = ( get_post_meta( $post->ID, 'full_name', true ) ? get_post_meta( $post->ID, 'full_name', true ) : '' );
        $givn = ( get_post_meta( $post->ID, 'givn', true ) ? get_post_meta( $post->ID, 'givn', true ) : '' );
        $surn = ( get_post_meta( $post->ID, 'surn', true ) ? get_post_meta( $post->ID, 'surn', true ) : '' );
        $names = ( get_post_meta( $post->ID, 'names' ) ? get_post_meta( $post->ID, 'names' ) : array( array(
            'name' => $name,
            'npfx' => '',
            'givn' => $givn,
            'nick' => '',
            'spfx' => '',
            'surn' => $surn,
            'nsfx' => '',
        ) ) );
        $sex = get_post_meta( $post->ID, 'sex', true );
        $event = ( get_post_meta( $post->ID, 'even' ) ? get_post_meta( $post->ID, 'even' ) : array() );
        $birt = array();
        $deat = array();
        $fams = ( get_post_meta( $post->ID, 'fams' ) ? get_post_meta( $post->ID, 'fams' ) : array( array(
            'fams' => '',
        ) ) );
        $famc = ( get_post_meta( $post->ID, 'famc' ) ? get_post_meta( $post->ID, 'famc' ) : array( array(
            'famc' => '',
            'pedi' => '',
        ) ) );
        $slgc = ( get_post_meta( $post->ID, 'slgc' ) ? get_post_meta( $post->ID, 'slgc' ) : array( array(
            'famc' => '',
            'date' => '',
            'plac' => '',
        ) ) );
        foreach ( $names as $key => &$name ) {
            if ( $name && !is_array( $name ) ) {
                $name = array(
                    'name' => $name,
                    'npfx' => '',
                    'givn' => '',
                    'nick' => '',
                    'spfx' => '',
                    'surn' => '',
                    'nsfx' => '',
                );
            }
        }
        foreach ( $names as $key => $value ) {
            if ( !isset( $value['name'] ) ) {
                unset( $names[$key] );
            }
        }
        foreach ( $event as $key => $value ) {
            $event[$key]['tag'] = strtoupper( $value['tag'] );
        }
        foreach ( $event as $key => $value ) {
            
            if ( 'BIRT' === (string) $value['tag'] ) {
                $birt[] = $value;
                unset( $event[$key] );
            }
            
            
            if ( 'DEAT' === (string) $value['tag'] ) {
                $deat[] = $value;
                unset( $event[$key] );
            }
        
        }
        if ( empty($birt) ) {
            $birt = array( array(
                'tag'  => 'BIRT',
                'even' => '',
                'type' => 'BIRT',
                'date' => '',
                'plac' => '',
            ) );
        }
        if ( empty($deat) ) {
            $deat = array( array(
                'tag'  => 'DEAT',
                'even' => '',
                'type' => 'DEAT',
                'date' => '',
                'plac' => '',
            ) );
        }
        if ( empty($event) ) {
            $event[0] = array(
                'tag'  => '',
                'even' => '',
                'type' => '',
                'date' => '',
                'plac' => '',
            );
        }
        // Fix fams.
        $is_duplicate_fams = array();
        foreach ( $fams as $key => $value ) {
            if ( !is_array( $value ) ) {
                unset( $fams[$key] );
            }
            
            if ( is_array( $value ) ) {
                if ( in_array( $value['fams'], $is_duplicate_fams, true ) ) {
                    unset( $fams[$key] );
                }
                array_push( $is_duplicate_fams, $value['fams'] );
            }
        
        }
        foreach ( $fams as $key => $fam ) {
            if ( isset( $fam['fams'] ) && $fam['fams'] && is_array( $fam['fams'] ) ) {
                $fam['fams'] = $fam['fams']['fams'];
            }
            $husb = (int) get_post_meta( $fam['fams'], 'husb', true );
            $wife = (int) get_post_meta( $fam['fams'], 'wife', true );
            $fams[$key]['spouse'] = ( $husb === (int) $post->ID ? $wife : $husb );
            $fams[$key]['chil'] = ( get_post_meta( $fam['fams'], 'chil' ) ? get_post_meta( $fam['fams'], 'chil' ) : array() );
            $fams[$key]['event'] = ( get_post_meta( $fam['fams'], 'even' ) ? get_post_meta( $fam['fams'], 'even' ) : array( array(
                'tag'  => '',
                'even' => '',
                'type' => '',
                'date' => '',
                'plac' => '',
            ) ) );
        }
        // Fix famc.
        $is_duplicate_famc = array();
        foreach ( $famc as $key => $value ) {
            if ( !is_array( $value ) ) {
                unset( $famc[$key] );
            }
            
            if ( is_array( $value ) ) {
                if ( in_array( $value['famc'], $is_duplicate_famc, true ) ) {
                    unset( $famc[$key] );
                }
                array_push( $is_duplicate_famc, $value['famc'] );
            }
        
        }
        foreach ( $famc as $key => $fam ) {
            if ( isset( $fam['famc'] ) && $fam['famc'] && is_array( $fam['famc'] ) ) {
                $fam['famc'] = $fam['famc']['famc'];
            }
            $famc[$key]['husb'] = get_post_meta( $fam['famc'], 'husb', true );
            $famc[$key]['wife'] = get_post_meta( $fam['famc'], 'wife', true );
            $famc[$key]['chil'] = ( get_post_meta( $fam['famc'], 'chil' ) ? get_post_meta( $fam['famc'], 'chil' ) : array() );
            foreach ( $slgc as $key_slgc => $value ) {
                
                if ( (int) $fam['famc'] === (int) $value['famc'] ) {
                    $famc[$key]['slgc'] = current( $slgc );
                } else {
                    $famc[$key]['slgc'] = array(
                        'famc' => '',
                        'date' => '',
                        'plac' => '',
                    );
                }
            
            }
        }
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-member-info.php';
    }
    
    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_debug( $post )
    {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['names'] = get_post_meta( $post->ID, 'names' );
        $get_post_meta['even'] = get_post_meta( $post->ID, 'even' );
        $get_post_meta['attr'] = get_post_meta( $post->ID, 'attr' );
        $get_post_meta['famc'] = get_post_meta( $post->ID, 'famc' );
        $get_post_meta['fams'] = get_post_meta( $post->ID, 'fams' );
        $get_post_meta['email'] = get_post_meta( $post->ID, 'email' );
        $get_post_meta['phone'] = get_post_meta( $post->ID, 'phone' );
        $get_post_meta['address'] = get_post_meta( $post->ID, 'address' );
        $get_post_meta['additional_fields'] = get_post_meta( $post->ID, 'additional_fields' );
        $get_post_meta['slgc'] = get_post_meta( $post->ID, 'slgc' );
        $get_post_meta['note'] = get_post_meta( $post->ID, 'note' );
        echo  '<pre>' ;
        
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        
        echo  '</pre>' ;
    }
    
    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_family_debug( $post )
    {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['chil'] = get_post_meta( $post->ID, 'chil' );
        $get_post_meta['even'] = get_post_meta( $post->ID, 'even' );
        $get_post_meta['slgs'] = get_post_meta( $post->ID, 'slgs' );
        echo  '<pre>' ;
        
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        
        echo  '</pre>' ;
    }
    
    /**
     * It prints out the post meta for the current post
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_tree_debug( $post )
    {
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['tree'] = get_post_meta( $post->ID, 'tree' );
        echo  '<pre>' ;
        
        if ( defined( 'WP_DEBUG' ) && true === WP_DEBUG && defined( 'GENEALOGICAL_TREE_DEBUG' ) && true === \GENEALOGICAL_TREE_DEBUG ) {
            print_r( $get_post_meta );
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
        }
        
        echo  '</pre>' ;
    }
    
    /**
     * It renders the meta box for the tree settings
     *
     * @param object $post  The post object.
     *
     * @since    1.0.0
     */
    public function render_meta_box_tree_settings( $post )
    {
        $border_style = $this->plugin->helper->border_style();
        $data = ( get_post_meta( $post->ID, 'tree', true ) ? get_post_meta( $post->ID, 'tree', true ) : array() );
        $base = $this->plugin->helper->tree_default_meta();
        $is_default = ( empty($data) ? true : false );
        $data = $this->plugin->helper->tree_merge( $base, $data, $is_default );
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-google-fonts.php';
        $premium = false;
        if ( !isset( $premium ) || !$premium ) {
            require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-settings.php';
        }
    }
    
    /**
     * It updates the meta boxes for the custom post type gt-member
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return int the post id.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_gt_member( $post_id )
    {
        // Return if nonce field not exist.
        if ( !isset( $_POST['_nonce_update_member_info_nonce'] ) ) {
            return $post_id;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['_nonce_update_member_info_nonce'] ) );
        // Return if verify not success.
        if ( !wp_verify_nonce( $nonce, 'update_member_info_nonce' ) ) {
            return $post_id;
        }
        // stop autosave.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // Return if not desire post type, and user don't have permission to update.
        
        if ( isset( $_POST['post_type'] ) && 'gt-member' === sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
        
        $family_group = get_the_terms( $post_id, 'gt-family-group' );
        if ( is_wp_error( $family_group ) ) {
            return;
        }
        if ( isset( $_POST['tax_input'] ) && isset( $_POST['tax_input']['gt-family-group'] ) && isset( $_POST['tax_input']['gt-family-group'][1] ) && sanitize_text_field( wp_unslash( $_POST['tax_input']['gt-family-group'][1] ) ) ) {
            $family_group = true;
        }
        /*
        Checking if the family group is empty. If it is empty, it will set the error message and update the
        option.
        */
        
        if ( !$family_group ) {
            $errors = 'Whoops... you forgot to select family group.';
            update_option( 'family_group_validation', $errors );
            if ( get_post( $post_id )->post_status !== 'draft' ) {
                wp_update_post( array(
                    'ID'          => $post_id,
                    'post_status' => 'draft',
                ) );
            }
        }
        
        /* Checking if the family group is set. If it is, it will update the family group validation to false. */
        if ( $family_group ) {
            update_option( 'family_group_validation', false );
        }
        /* Sanitizing the data that is being passed in. */
        $names = ( isset( $_POST['gt']['names'] ) ? map_deep( wp_unslash( $_POST['gt']['names'] ), 'sanitize_text_field' ) : array( array(
            'name' => '',
            'npfx' => '',
            'givn' => '',
            'nick' => '',
            'spfx' => '',
            'surn' => '',
            'nsfx' => '',
        ) ) );
        foreach ( $names as $key => $name ) {
            $names[$key]['name'] = $this->plugin->helper->repear_full_name( sanitize_text_field( $name['name'] ) );
            $names[$key]['npfx'] = sanitize_text_field( $name['npfx'] );
            $names[$key]['givn'] = sanitize_text_field( $name['givn'] );
            $names[$key]['nick'] = sanitize_text_field( $name['nick'] );
            $names[$key]['spfx'] = sanitize_text_field( $name['spfx'] );
            $names[$key]['surn'] = sanitize_text_field( $name['surn'] );
            $names[$key]['nsfx'] = sanitize_text_field( $name['nsfx'] );
        }
        /* Deleting the post meta for the post id and then adding the post meta for the post id. */
        delete_post_meta( $post_id, 'names' );
        if ( isset( $names ) && is_array( $names ) && !empty($names) ) {
            foreach ( $names as $key => $value ) {
                add_post_meta( $post_id, 'names', $value );
            }
        }
        /*
        Checking if the sex field is set and if it is, it is sanitizing the text and then deleting the post
        meta. If the sex field is set, it is adding the post meta.
        */
        $sex = ( isset( $_POST['gt']['sex'] ) ? sanitize_text_field( wp_unslash( $_POST['gt']['sex'] ) ) : null );
        delete_post_meta( $post_id, 'sex' );
        if ( isset( $sex ) && $sex ) {
            add_post_meta( $post_id, 'sex', $sex );
        }
        /* Sanitizing the data and saving it to the database. */
        $attr = ( isset( $_POST['gt']['attr'] ) ? map_deep( wp_unslash( $_POST['gt']['attr'] ), 'sanitize_text_field' ) : array() );
        delete_post_meta( $post_id, 'attr' );
        if ( isset( $attr ) && is_array( $attr ) && !empty($attr) ) {
            foreach ( $attr as $key => $value ) {
                add_post_meta( $post_id, 'attr', $value );
            }
        }
        /* Saving the data from the form to the database. */
        $even = ( isset( $_POST['gt']['even'] ) ? map_deep( wp_unslash( $_POST['gt']['even'] ), 'sanitize_text_field' ) : array() );
        $birt = $even['BIRT'];
        $deat = $even['DEAT'];
        unset( $even['BIRT'] );
        unset( $even['DEAT'] );
        delete_post_meta( $post_id, 'even' );
        if ( isset( $even ) && is_array( $even ) && !empty($even) ) {
            foreach ( $even as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        if ( isset( $birt ) && is_array( $birt ) && !empty($birt) ) {
            foreach ( $birt as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        if ( isset( $deat ) && is_array( $deat ) && !empty($deat) ) {
            foreach ( $deat as $key => $value ) {
                add_post_meta( $post_id, 'even', $value );
            }
        }
        /* Checking if the note is set in the POST array. If it is, it is sanitizing the text field. */
        $note = ( isset( $_POST['gt']['note'] ) ? map_deep( wp_unslash( $_POST['gt']['note'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'note' );
        if ( isset( $note ) && is_array( $note ) && !empty($note) ) {
            foreach ( $note as $key => $value ) {
                add_post_meta( $post_id, 'note', $value );
            }
        }
        /*
        Checking if the phone number is set in the  array. If it is, it is sanitizing the phone
        number.
        */
        $phone = ( isset( $_POST['gt']['phone'] ) ? map_deep( wp_unslash( $_POST['gt']['phone'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta for the phone number and then adding it back in. */
        delete_post_meta( $post_id, 'phone' );
        if ( isset( $phone ) && is_array( $phone ) && !empty($phone) ) {
            foreach ( $phone as $key => $value ) {
                add_post_meta( $post_id, 'phone', $value );
            }
        }
        /*
        Checking if the email is set in the ['gt']['email'] array. If it is, it is sanitizing the
        text field.
        */
        $email = ( isset( $_POST['gt']['email'] ) ? map_deep( wp_unslash( $_POST['gt']['email'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'email' );
        if ( isset( $email ) && is_array( $email ) && !empty($email) ) {
            foreach ( $email as $key => $value ) {
                add_post_meta( $post_id, 'email', $value );
            }
        }
        /* Checking if the address is set in the POST array. If it is, it is sanitizing the address. */
        $address = ( isset( $_POST['gt']['address'] ) ? map_deep( wp_unslash( $_POST['gt']['address'] ), 'sanitize_text_field' ) : array() );
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'address' );
        if ( isset( $address ) && is_array( $address ) && !empty($address) ) {
            foreach ( $address as $key => $value ) {
                add_post_meta( $post_id, 'address', $value );
            }
        }
        /*
        Checking if the additional_info field is set and if it is, it is sanitizing the input and saving
        it to the post meta.
        */
        
        if ( isset( $_POST['additional_info'] ) ) {
            $additional_info = wp_kses_post( wp_unslash( $_POST['additional_info'] ) );
            update_post_meta( $post_id, 'additional_info', $additional_info );
        }
        
        /* Sanitizing the data that is being passed to the database. */
        
        if ( isset( $_POST['some_custom_gallery'] ) ) {
            $some_custom_gallery = map_deep( wp_unslash( $_POST['some_custom_gallery'] ), 'sanitize_text_field' );
            update_post_meta( $post_id, 'some_custom_gallery', $some_custom_gallery );
        }
        
        /* Sanitizing the additional fields. */
        $additional_fields = ( isset( $_POST['additional_fields'] ) ? map_deep( wp_unslash( $_POST['additional_fields'] ), 'sanitize_text_field' ) : array() );
        if ( $additional_fields ) {
            foreach ( $additional_fields as $key => $field ) {
                $additional_fields[$key]['name'] = sanitize_text_field( $field['name'] );
                $additional_fields[$key]['value'] = sanitize_text_field( $field['value'] );
            }
        }
        /* Deleting the post meta and then adding it back in. */
        delete_post_meta( $post_id, 'additional_fields' );
        foreach ( $additional_fields as $key => $field ) {
            add_post_meta( $post_id, 'additional_fields', $field );
        }
        // family.
        $indis = array();
        array_push( $indis, $post_id );
        // FAMC.
        $famc_old_array = array();
        $famc_new_array = array();
        $famc_old = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
        foreach ( $famc_old as $key => $value ) {
            if ( isset( $value['famc'] ) && $value['famc'] ) {
                array_push( $famc_old_array, $value['famc'] );
            }
        }
        delete_post_meta( $post_id, 'famc' );
        $parents = ( isset( $_POST['gt']['family']['parents'] ) ? map_deep( wp_unslash( $_POST['gt']['family']['parents'] ), 'sanitize_text_field' ) : array() );
        foreach ( $parents as $key => $parent ) {
            $wife = $parent['wife'];
            $husb = $parent['husb'];
            
            if ( $wife || $husb ) {
                $family_id = $this->find_or_create_family( $wife, $husb, array( $post_id ) );
                array_push( $famc_new_array, $family_id );
                $famc = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
                foreach ( $famc as $key => $value ) {
                    
                    if ( isset( $value['famc'] ) && $value['famc'] ) {
                        $famc[] = (int) $value['famc'];
                        unset( $famc[$key] );
                    }
                
                }
                if ( !in_array( (int) $family_id, $famc, true ) ) {
                    add_post_meta( $post_id, 'famc', array(
                        'famc' => $family_id,
                        'pedi' => $parent['pedi'],
                    ) );
                }
                if ( $wife ) {
                    array_push( $indis, $wife );
                }
                if ( $husb ) {
                    array_push( $indis, $husb );
                }
            }
        
        }
        // FAMS.
        $fams_new_array = array();
        $fams_old_array = array();
        $fams_old = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
        foreach ( $fams_old as $key => $value ) {
            if ( isset( $value['fams'] ) && $value['fams'] ) {
                array_push( $fams_old_array, $value['fams'] );
            }
        }
        delete_post_meta( $post_id, 'fams' );
        $spouses = ( isset( $_POST['gt']['family']['spouses'] ) ? map_deep( wp_unslash( $_POST['gt']['family']['spouses'] ), 'sanitize_text_field' ) : array() );
        foreach ( $spouses as $key => $spouse ) {
            $order = ( isset( $spouse['order'] ) ? $spouse['order'] : 0 );
            $chil = ( isset( $spouse['chil'] ) ? array_filter( array_unique( $spouse['chil'] ) ) : array() );
            
            if ( $spouse['id'] || !empty($chil) ) {
                $wife_or_husb = $this->is_wife_or_husband( $post_id, $spouse['id'] );
                $wife = $wife_or_husb['wife'];
                $husb = $wife_or_husb['husb'];
                
                if ( $wife || $husb ) {
                    $family_id = $this->find_or_create_family(
                        $wife,
                        $husb,
                        $chil,
                        $order
                    );
                    array_push( $fams_new_array, $family_id );
                    $even = $spouse['even'];
                    delete_post_meta( $family_id, 'even' );
                    if ( isset( $even ) && is_array( $even ) && !empty($even) ) {
                        foreach ( $even as $key => $value ) {
                            add_post_meta( $family_id, 'even', $value );
                        }
                    }
                    if ( $wife ) {
                        array_push( $indis, $wife );
                    }
                    if ( $husb ) {
                        array_push( $indis, $husb );
                    }
                }
            
            }
        
        }
        /* Deleting the old family relationships that are no longer valid. */
        $missing_famc = array_diff( $famc_old_array, $famc_new_array );
        $missing_fams = array_diff( $fams_old_array, $fams_new_array );
        /* Deleting the family from the child's record. */
        foreach ( $missing_famc as $key => $fam ) {
            delete_post_meta( $fam, 'chil', $post_id );
            $this->check_and_delete_family( $fam, $indis );
        }
        /* Deleting the family from the database. */
        foreach ( $missing_fams as $key => $fam ) {
            delete_post_meta( $fam, 'husb', $post_id );
            delete_post_meta( $fam, 'wife', $post_id );
            $this->check_and_delete_family( $fam, $indis );
        }
        /* Checking if the post meta 'slgc' exists and if it does, it deletes it. */
        $slgc_new = array();
        $slgc = ( isset( $_POST['gt']['slgc'] ) ? map_deep( wp_unslash( $_POST['gt']['slgc'] ), 'sanitize_text_field' ) : array() );
        delete_post_meta( $post_id, 'slgc' );
        /* Adding the new slgc data to the post meta. */
        $slgc_new['famc'] = $famc_new_array[$slgc['slgc_check']];
        $slgc_new['date'] = $slgc[$slgc['slgc_check']]['date'];
        $slgc_new['plac'] = $slgc[$slgc['slgc_check']]['plac'];
        add_post_meta( $post_id, 'slgc', $slgc_new );
    }
    
    /**
     * If the nonce is valid, and the user has permission to edit the post, then update the post meta
     *
     * @param int $post_id The ID of the post being saved.
     *
     * @return int the post id.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_gt_tree( $post_id )
    {
        if ( !isset( $_POST['_nonce_update_tree_settings_nonce'] ) ) {
            return $post_id;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['_nonce_update_tree_settings_nonce'] ) );
        if ( !wp_verify_nonce( $nonce, 'update_tree_settings_nonce' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        if ( isset( $_POST['post_type'] ) && 'gt-tree' === sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        if ( isset( $_POST['tree'] ) && !empty($_POST['tree']) ) {
            update_post_meta( $post_id, 'tree', map_deep( wp_unslash( $_POST['tree'] ), 'sanitize_text_field' ) );
        }
    }
    
    /**
     * It adds the columns to the member post type.
     *
     * @param array $columns An array of column names.
     *
     * @return array The columns for the member post type.
     *
     * @since    1.0.0
     */
    public function member_posts_columns( $columns )
    {
        $columns['ID'] = __( 'ID', 'genealogical-tree' );
        $columns['born'] = __( 'Born', 'genealogical-tree' );
        $columns['title'] = __( 'Name', 'genealogical-tree' );
        $columns['parent'] = __( 'Parents', 'genealogical-tree' );
        $columns['spouses'] = __( 'Spouses', 'genealogical-tree' );
        $columns['author'] = __( 'Author', 'genealogical-tree' );
        return $columns;
    }
    
    /**
     * It adds a column to the admin table for each post type
     *
     * @param string $column The name of the column.
     * @param int    $post_id The ID of the post.
     *
     * @since    1.0.0
     */
    public function member_posts_custom_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'ID':
                echo  esc_html( $post_id ) ;
                break;
            case 'born':
                $even = ( get_post_meta( $post_id, 'even' ) ? get_post_meta( $post_id, 'even' ) : array() );
                $birt = array();
                foreach ( $even as $key => $value ) {
                    if ( 'BIRT' === $value['tag'] ) {
                        $birt[] = $value;
                    }
                }
                if ( !empty($birt) ) {
                    if ( isset( $birt[0] ) && $birt[0]['date'] ) {
                        echo  esc_html( $birt[0]['date'] ) ;
                    }
                }
                break;
            case 'parent':
                $famc = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array() );
                foreach ( $famc as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset( $famc[$key] );
                    }
                }
                foreach ( $famc as $key => $value ) {
                    $husb_id = get_post_meta( $value['famc'], 'husb', true );
                    if ( $husb_id && get_post( $husb_id ) ) {
                        echo  '
						<div>
							<b>' . esc_html( __( 'Father', 'genealogical-tree' ) ) . ' : </b>
							<a href="' . esc_url( get_edit_post_link( $husb_id ) ) . '">
								' . esc_html( get_the_title( $husb_id ) ) . '
							</a>
						</div>
						' ;
                    }
                    $wife_id = get_post_meta( $value['famc'], 'wife', true );
                    if ( $wife_id && get_post( $wife_id ) ) {
                        echo  '
						<div>
							<b>' . esc_html( __( 'Mother', 'genealogical-tree' ) ) . ' : </b>
							<a href="' . esc_url( get_edit_post_link( $wife_id ) ) . '">
								' . esc_html( get_the_title( $wife_id ) ) . '
							</a>
						</div>
						' ;
                    }
                }
                break;
            case 'spouses':
                $fams = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
                foreach ( $fams as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset( $fams[$key] );
                    }
                }
                if ( !empty($fams) ) {
                    foreach ( $fams as $key => $value ) {
                        $husb_id = get_post_meta( $value['fams'], 'husb', true );
                        $wife_id = get_post_meta( $value['fams'], 'wife', true );
                        $spouse_id = ( $husb_id === $post_id ? $wife_id : $husb_id );
                        
                        if ( $spouse_id && get_post( $spouse_id ) ) {
                            if ( $key > 0 ) {
                                echo  ', ' ;
                            }
                            echo  '
							<a href="' . esc_url( get_edit_post_link( $spouse_id ) ) . '">
								' . esc_html( get_the_title( $spouse_id ) ) . '
							</a>
							' ;
                        }
                    
                    }
                }
                break;
        }
    }
    
    /**
     * This function adds the ability to sort the columns in the admin area
     *
     * @param array $columns The array of columns to be sorted.
     *
     * @since    1.0.0
     */
    public function member_sortable_columns( $columns )
    {
        $columns['ID'] = 'ID';
        $columns['born'] = 'born';
        $columns['title'] = 'title';
        $columns['taxonomy-gt-family-group'] = 'gt-family-group';
        return $columns;
    }
    
    /**
     * It adds a new column to the list of posts
     *
     * @param array $columns The names of the columns.
     *
     * @return array The shortcode for the post.
     *
     * @since    1.0.0
     */
    public function tree_posts_columns( $columns )
    {
        $columns['shortcode'] = __( 'Shortcode', 'genealogical-tree' );
        return $columns;
    }
    
    /**
     * It adds a column to the admin list of trees, and in that column it displays the shortcode for that
     * tree
     *
     * @param array $column The name of the column.
     * @param int   $post_id The ID of the post.
     *
     * @since    1.0.0
     */
    public function tree_posts_custom_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'shortcode':
                echo  sprintf( '<input type="text" readonly value="[tree id=%1$s]">', esc_attr( $post_id ) ) ;
                break;
        }
    }
    
    /**
     * It adds a rewrite rule to WordPress that allows us to use the URL
     * `/gt-member/{member}/tab/{tab-slug}` to access the tab `{tab-slug}` on the profile of the user
     * with the member `{member}`
     *
     * @since    1.0.0
     */
    public function init_add_rewrite_rule_gt_member_tab()
    {
        add_rewrite_rule( 'gt-member/( [A-Za-z0-9\\-\\_]+ )/tab/( [A-Za-z0-9\\-\\_]+ )', 'index.php?gt-member=$matches[1]&tab=$matches[2]', 'top' );
    }
    
    /**
     * It adds the query variable `tab` to the list of query variables that WordPress will recognize
     *
     * @param array $query_vars The query variables that will be used to determine which tab is being displayed.
     *
     * @return array The query_vars array.
     *
     * @since    1.0.0
     */
    public function query_vars_gt_member_tab( $query_vars )
    {
        $query_vars[] = 'tab';
        return $query_vars;
    }
    
    /**
     * It adds the gt_member role to the user if they are an administrator or gt_manager
     *
     * @param int $user_id The ID of the user being registered.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function user_register_action( $user_id )
    {
        $user = get_user_by( 'id', $user_id );
        // On user registration.
        
        if ( $user && in_array( 'administrator', $user->roles, true ) ) {
            $user->add_role( 'gt_member' );
            $user->add_role( 'gt_manager' );
        }
        
        if ( $user && in_array( 'gt_manager', $user->roles, true ) ) {
            $user->add_role( 'gt_member' );
        }
        if ( !isset( $_POST['gt_login_form_nonce'] ) ) {
            return;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['gt_login_form_nonce'] ) );
        if ( !wp_verify_nonce( $nonce, 'gt_login_form_action' ) ) {
            return;
        }
        // User registration through gt registerantion form.
        
        if ( isset( $_POST['role'] ) ) {
            
            if ( 'gt_manager' === sanitize_text_field( wp_unslash( $_POST['role'] ) ) ) {
                $user->add_role( 'gt_manager' );
                $user->add_role( 'gt_member' );
            }
            
            if ( 'gt_member' === sanitize_text_field( wp_unslash( $_POST['role'] ) ) ) {
                $user->add_role( 'gt_member' );
            }
        }
    
    }
    
    /**
     * A callback function for the import form.
     *
     * @since    1.0.0
     */
    public function process_import_post()
    {
        require_once 'genealogical-tree-handel-import.php';
    }
    
    /**
     * A callback function for the export ged.
     *
     * @since    1.0.0
     */
    public function process_export_post()
    {
        require_once 'genealogical-tree-handel-export.php';
    }
    
    /**
     * View for How It Work page.
     *
     * @since    1.0.0
     */
    public function settings()
    {
    }
    
    /**
     * It takes a wife, husband, and children, and creates a family if one doesn't exist.
     *
     * @param  int   $wife  The ID of the wife.
     * @param  int   $husb  The ID of the husband.
     * @param  array $chil  An array of child IDs.
     * @param  int   $order This is the order of the family. If you have a person who has been married multiple times, this is the order of the marriage.
     *
     * @return int         The ID of the created / exist family.
     *
     * @since    2.1.1
     */
    public function find_or_create_family(
        $wife,
        $husb,
        $chil,
        $order = 0
    )
    {
        
        if ( $wife || $husb ) {
            if ( $wife && $husb ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'wife',
                    'value'   => $wife,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'husb',
                    'value'   => $husb,
                    'compare' => '=',
                ),
                ),
                ) );
            }
            if ( !$wife && $husb ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'husb',
                    'value'   => $husb,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'wife',
                    'compare' => 'NOT EXISTS',
                ),
                ),
                ) );
            }
            if ( $wife && !$husb ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'wife',
                    'value'   => $wife,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'husb',
                    'compare' => 'NOT EXISTS',
                ),
                ),
                ) );
            }
            /*
            Checking if the family exists and if it does, it will return the family ID. If it doesn't exist, it
            will create a new family and return the family ID.
            */
            
            if ( isset( $query ) && $query->posts && !empty($query->posts) ) {
                $family_id = current( $query->posts )->ID;
            } else {
                if ( $wife && $husb ) {
                    $post_title = get_the_title( $husb ) . ' and ' . get_the_title( $wife );
                }
                if ( !$wife && $husb ) {
                    $post_title = get_the_title( $husb );
                }
                if ( $wife && !$husb ) {
                    $post_title = get_the_title( $wife );
                }
                $family_id = wp_insert_post( array(
                    'post_title'   => $post_title,
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                    'post_type'    => 'gt-family',
                ) );
            }
            
            
            if ( $husb ) {
                // Manage family.
                /* Checking to see if the husband is already in the family. If not, it adds him. */
                if ( !in_array( (string) $husb, get_post_meta( $family_id, 'husb' ), true ) ) {
                    add_post_meta( $family_id, 'husb', $husb );
                }
                /* Get families. */
                $fams = ( get_post_meta( $husb, 'fams' ) ? get_post_meta( $husb, 'fams' ) : array() );
                /* Prepare for checking. */
                foreach ( $fams as $value ) {
                    if ( isset( $value['fams'] ) && $value['fams'] ) {
                        $fams[] = (int) $value['fams'];
                    }
                }
                /*
                Checking to see if the family_ID is in the array of families of husband. If it is not, it adds it to the
                families.
                */
                if ( !in_array( (int) $family_id, $fams, true ) ) {
                    add_post_meta( $husb, 'fams', array(
                        'fams'  => $family_id,
                        'order' => $order,
                    ) );
                }
            }
            
            
            if ( $wife ) {
                // Manage family.
                /* Checking if the wife is already in the family. If not, it adds the wife to the family. */
                if ( !in_array( (string) $wife, get_post_meta( $family_id, 'wife' ), true ) ) {
                    add_post_meta( $family_id, 'wife', $wife );
                }
                /* Get families. */
                $fams = ( get_post_meta( $wife, 'fams' ) ? get_post_meta( $wife, 'fams' ) : array() );
                /* Prepare for checking. */
                foreach ( $fams as $value ) {
                    if ( isset( $value['fams'] ) && $value['fams'] ) {
                        $fams[] = (int) $value['fams'];
                    }
                }
                /*
                Checking to see if the family_ID is in the array of families of wife. If it is not, it adds it to the
                families.
                */
                if ( !in_array( (int) $family_id, $fams, true ) ) {
                    add_post_meta( $wife, 'fams', array(
                        'fams'  => $family_id,
                        'order' => $order,
                    ) );
                }
            }
            
            if ( is_array( $chil ) && !empty($chil) ) {
                foreach ( $chil as $key => $ch ) {
                    // Manage family.
                    /* Checking if the child is already in the family of parents. If not, it adds the child to the family. */
                    $current_chil = ( get_post_meta( $family_id, 'chil' ) ? get_post_meta( $family_id, 'chil' ) : array() );
                    if ( !in_array( (string) $ch, $current_chil, true ) ) {
                        add_post_meta( $family_id, 'chil', $ch );
                    }
                    /* Get parent families. */
                    $famc = ( get_post_meta( $ch, 'famc' ) ? get_post_meta( $ch, 'famc' ) : array() );
                    /* Prepare for checking. */
                    foreach ( $famc as $value ) {
                        if ( isset( $value['famc'] ) && $value['famc'] ) {
                            $famc[] = (int) $value['famc'];
                        }
                    }
                    /*
                    Checking to see if the family_ID is in the array of parents families. If it is not, it adds it to the
                    families.
                    */
                    if ( !in_array( (int) $family_id, $famc, true ) ) {
                        add_post_meta( $ch, 'famc', array(
                            'famc' => $family_id,
                            'pedi' => '',
                        ) );
                    }
                }
            }
            return $family_id;
        }
    
    }
    
    /**
     * If a family has a husband, wife, and/or children, then don't delete it.  Otherwise, delete it
     *
     * @param int   $family_id  The ID of the family you want to check.
     * @param array $member_ids An array of member IDs that are related to the family.
     *
     * @return mixed.
     *
     * @since    2.1.1
     */
    public function check_and_delete_family( $family_id, $member_ids )
    {
        $husb = get_post_meta( $family_id, 'husb', true );
        $wife = get_post_meta( $family_id, 'wife', true );
        $chil = get_post_meta( $family_id, 'chil', true );
        
        if ( $husb && $wife || $wife && $chil || $husb && $chil ) {
            return;
        } else {
            $member_ids = array_unique( $member_ids );
            foreach ( $member_ids as $member_id ) {
                delete_post_meta( $member_id, 'fams', $family_id );
                delete_post_meta( $member_id, 'famc', $family_id );
            }
            wp_delete_post( $family_id );
        }
    
    }
    
    /**
     * If the current screen is the edit screen for the gt-member post type, then add the merged_with or
     * merged_to class to the post row
     *
     * @param array  $classes An array of post classes.
     * @param string $class The class name.
     * @param int    $post_id The ID of the post.
     *
     * @return array An array of post classes.
     *
     * @since    1.0.0
     */
    public function post_class_filter( $classes, $class, $post_id )
    {
        if ( !is_admin() ) {
            return $classes;
        }
        $screen = get_current_screen();
        if ( 'gt-member' !== $screen->post_type && 'edit' !== $screen->base ) {
            return $classes;
        }
        $merged_with = ( get_post_meta( $post_id, 'merged_with' ) ? get_post_meta( $post_id, 'merged_with' ) : array() );
        if ( !empty($merged_with) ) {
            $classes[] = 'merged_with';
        }
        $merged_to = ( get_post_meta( $post_id, 'merged_to' ) ? get_post_meta( $post_id, 'merged_to' ) : array() );
        if ( !empty($merged_to) ) {
            $classes[] = 'merged_to';
        }
        return $classes;
    }
    
    /**
     * It returns an array of member IDs that the current user can use
     *
     * @param object $post The post object of the current post.
     *
     * @return array An array of arrays.
     *
     * @since    1.0.0
     */
    public function get_useable_members( $post )
    {
        $males = array();
        $females = array();
        $unknowns = array();
        /*
        Creating an array of all the members that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'author'         => get_current_user_id(),
            'post__not_in'   => array( $post->ID ),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            array(
                'key'     => 'merged_to',
                'compare' => 'NOT EXISTS',
            ),
        ),
        );
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            unset( $args['author'] );
        }
        $query = new \WP_Query( $args );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'post__not_in'   => array( $post->ID ),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
            'relation' => 'AND',
            array(
            'key'     => 'can_use',
            'value'   => get_current_user_id(),
            'compare' => 'IN',
        ),
            array(
            'key'     => 'merged_to',
            'compare' => 'NOT EXISTS',
        ),
        ),
        );
        $query = new \WP_Query( $args );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        /*
        Getting all the members that the current user can use  and that are not merged to another member
        and putting them into arrays based on their sex.
        */
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'post__not_in'   => array( $post->ID ),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array(
            'relation' => 'AND',
            array(
            'key'     => 'can_use_by_allowed_group',
            'value'   => get_current_user_id(),
            'compare' => 'IN',
        ),
            array(
            'key'     => 'merged_to',
            'compare' => 'NOT EXISTS',
        ),
        ),
        );
        $query = new \WP_Query( $args );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                $member_sex = ( (string) get_post_meta( $member->ID, 'sex', true ) ? get_post_meta( $member->ID, 'sex', true ) : '' );
                if ( 'M' === $member_sex ) {
                    array_push( $males, $member->ID );
                }
                if ( 'F' === $member_sex ) {
                    array_push( $females, $member->ID );
                }
                if ( 'F' !== $member_sex && 'M' !== $member_sex ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        return array(
            'males'    => $males,
            'females'  => $females,
            'unknowns' => $unknowns,
        );
    }
    
    /**
     * It creates a select box with the option groups of female, male and unknown.
     *
     * @param array  $females  An array of female members.
     * @param array  $males    An array of male members.
     * @param array  $unknowns An array of unknown gender members.
     * @param string $name     The name of the select box.
     * @param string $value    The value of the option.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function select_member_html(
        $females = array(),
        $males = array(),
        $unknowns = array(),
        $name = '',
        $value = ''
    )
    {
        ?>
		<option value=""><?php 
        esc_html_e( 'Select', 'genealogical-tree' );
        ?> <?php 
        echo  esc_html( $name ) ;
        ?> </option>
		<optgroup label="<?php 
        esc_html_e( 'Female', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $females as $female ) {
            ?>
				<option <?php 
            selected( $female, $value );
            ?> value="<?php 
            echo  esc_attr( $female ) ;
            ?>">
					<?php 
            echo  esc_html( $this->plugin->helper->get_full_name( $female ) ) ;
            ?>
					<?php 
            echo  esc_html( '[' . $female . ']' ) ;
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<optgroup label="<?php 
        esc_html_e( 'Male', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $males as $male ) {
            ?>
				<option <?php 
            selected( $male, $value );
            ?> value="<?php 
            echo  esc_attr( $male ) ;
            ?>">
					<?php 
            echo  esc_html( $this->plugin->helper->get_full_name( $male ) ) ;
            ?>
					<?php 
            echo  esc_html( '[' . $male . ']' ) ;
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<optgroup label="<?php 
        esc_html_e( 'Unknown', 'genealogical-tree' );
        ?>">
			<?php 
        foreach ( $unknowns as $unknown ) {
            ?>
				<option <?php 
            selected( $unknown, $value );
            ?> value="<?php 
            echo  esc_attr( $unknown ) ;
            ?>">
					<?php 
            echo  esc_html( $this->plugin->helper->get_full_name( $unknown ) ) ;
            ?>
					<?php 
            echo  esc_html( '[' . $unknown . ']' ) ;
            ?>
				</option>
			<?php 
        }
        ?>
		</optgroup>
		<?php 
    }
    
    /**
     * It takes a string, checks if it exists as a term in the taxonomy `gt-family-group`, and if it does,
     * it returns a string with a number appended to the end
     *
     * @param string $filename The name of the family group.
     *
     * @return string The first suggestion for a family group name.
     *
     * @since    1.0.0
     */
    public function generate_family_group_name( $filename )
    {
        
        if ( $filename ) {
            $filename = sanitize_text_field( $filename );
            $term = term_exists( $filename, 'gt-family-group' );
            $suggestions = array();
            
            if ( 0 !== $term && null !== $term ) {
                $terms_slug = array();
                $count = 0;
                $names_left = 1000;
                $terms = get_terms( 'gt-family-group', array(
                    'hide_empty' => false,
                ) );
                if ( $terms ) {
                    foreach ( $terms as $key => $term ) {
                        array_push( $terms_slug, $term->slug );
                    }
                }
                while ( $names_left > 0 ) {
                    $count++;
                    
                    if ( !in_array( sanitize_title( $filename ) . '-' . $count, $terms_slug, true ) ) {
                        $suggestions[] = $filename . ' ' . $count;
                        $names_left--;
                    }
                
                }
            } else {
                return $filename;
            }
            
            return $suggestions[0];
        }
    
    }
    
    /**
     * If the sex of the person is known, then the person is the husband or wife, otherwise, the spouse is
     * the husband or wife
     *
     * @param int $post_id   The ID of the person you're checking.
     * @param int $spouse_id The ID of the spouse.
     * @param int $wife      The wife's ID.
     * @param int $husb      The husband's ID.
     *
     * @return array
     *
     * @since    1.0.0
     */
    public function is_wife_or_husband(
        $post_id,
        $spouse_id,
        $wife = 0,
        $husb = 0
    )
    {
        $sex = ( get_post_meta( $post_id, 'sex', true ) ? get_post_meta( $post_id, 'sex', true ) : '' );
        
        if ( $sex ) {
            
            if ( 'M' === $sex ) {
                $husb = $post_id;
                $wife = $spouse_id;
            }
            
            
            if ( 'F' === $sex ) {
                $wife = $post_id;
                $husb = $spouse_id;
            }
        
        } else {
            $sex = ( get_post_meta( $spouse_id, 'sex', true ) ? get_post_meta( $spouse_id, 'sex', true ) : '' );
            
            if ( $sex ) {
                
                if ( 'M' === $sex ) {
                    $wife = $post_id;
                    $husb = $spouse_id;
                }
                
                
                if ( 'F' === $sex ) {
                    $husb = $post_id;
                    $wife = $spouse_id;
                }
            
            } else {
                $husb = $post_id;
                $wife = $spouse_id;
            }
        
        }
        
        return array(
            'wife' => $wife,
            'husb' => $husb,
        );
    }
    
    /**
     * It deletes all the meta data associated with a person when that person is deleted.
     *
     * @param int $post_id The ID of the post being deleted.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function before_delete_post( $post_id )
    {
        $args = array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'meta_query'     => array(
            'relation' => 'OR',
            array(
            'key'     => 'husb',
            'compare' => '=',
            'value'   => $post_id,
        ),
            array(
            'key'     => 'husb',
            'compare' => '=',
            'value'   => $post_id,
        ),
            array(
            'key'     => 'chil',
            'compare' => 'IN',
            'value'   => $post_id,
        ),
        ),
        );
        $query = new \WP_Query( $args );
        $families = $query->posts;
        if ( $families ) {
            foreach ( $families as $key => $value ) {
                delete_post_meta( $value->ID, 'husb', $post_id );
                delete_post_meta( $value->ID, 'wife', $post_id );
                delete_post_meta( $value->ID, 'chil', $post_id );
            }
        }
    }
    
    /**
     * It compares two objects by their ID property.
     *
     * @param  object $a The first object to compare.
     * @param  object $b The next object to compare.
     *
     * @return object   The object.
     *
     * @since           1.0.0
     */
    public function sort_member_posts( $a, $b )
    {
        return strcmp( $a->ID, $b->ID );
    }
    
    /**
     * Used to get add or delete button on the clone field area.
     *
     * @param int $key The key of the current element in the array.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function clone_delete( $key )
    {
        if ( 0 === (int) $key ) {
            echo  '<span class="clone">' . esc_html__( 'Add', 'genealogical-tree' ) . '</span>' ;
        }
        if ( (int) $key > 0 ) {
            echo  '<span class="delete">' . esc_html__( 'Delete', 'genealogical-tree' ) . '</span>' ;
        }
    }
    
    /**
     * Function for `bp_manage_capabilities`
     *
     * @param int    $allcaps allcaps.
     * @param int    $caps caps.
     * @param int    $args args.
     * @param object $user user.
     *
     * @since    1.0.0
     */
    public function bp_manage_capabilities(
        $allcaps,
        $caps,
        $args,
        $user
    )
    {
        global  $bp ;
        $roles = (array) $user->roles;
        
        if ( function_exists( 'groups_get_group_members' ) ) {
            $admin_mods = groups_get_group_members( array(
                'group_id' => 1,
            ) );
            $target = array(
                'administrator',
                'editor',
                'gt_manager',
                'gt_member'
            );
            $result = array_intersect( $roles, $target );
            
            if ( !empty($result) || $bp && get_user_meta( $user->ID, 'total_group_count', true ) ) {
                $allcaps['upload_files'] = true;
                $allcaps['edit_posts'] = true;
                $allcaps['edit_published_posts'] = true;
                $allcaps['publish_posts'] = true;
                $allcaps['read'] = true;
                $allcaps['level_2'] = true;
                $allcaps['level_1'] = true;
                $allcaps['level_0'] = true;
                $allcaps['delete_posts'] = true;
                $allcaps['delete_published_posts'] = true;
                $allcaps['gt_member'] = true;
                $allcaps['manage_categories'] = true;
            }
        
        }
        
        return $allcaps;
    }
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_tab()
    {
        global  $bp ;
        if ( function_exists( 'bp_core_new_nav_item' ) ) {
            bp_core_new_nav_item( array(
                'name'                => 'Family Tree',
                'slug'                => 'family-tree',
                'screen_function'     => array( $this, 'bp_family_tree_screen' ),
                'position'            => 40,
                'parent_url'          => bp_loggedin_user_domain() . '/family-tree/',
                'parent_slug'         => $bp->profile->slug,
                'default_subnav_slug' => 'family-tree',
            ) );
        }
    }
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_screen()
    {
        if ( function_exists( 'bp_core_load_template' ) ) {
            bp_core_load_template( 'buddypress/members/single/plugins' );
        }
    }
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_title()
    {
        echo  'Family Tree' ;
    }
    
    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public function bp_family_tree_content()
    {
        echo  'Content' ;
    }

}