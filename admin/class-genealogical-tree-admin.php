<?php

namespace Genealogical_Tree\Genealogical_Tree_Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */
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
    use  \Genealogical_Tree\Includes\Traits\Genealogical_Tree_Data ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
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
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style(
            $this->plugin_name . '-select2-css',
            plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-admin.css',
            array(),
            $this->version,
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
            $this->plugin_name . '-select2-js',
            plugin_dir_url( __FILE__ ) . 'js/select2.min.js',
            array( 'jquery', 'wp-color-picker' ),
            $this->version,
            false
        );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-admin.js',
            array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
            $this->version,
            false
        );
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        if ( !did_action( 'wp_enqueue_media' ) ) {
            wp_enqueue_media();
        }
        $term_args = array(
            'taxonomy'   => 'gt-family-group',
            'hide_empty' => false,
            'fields'     => 'all',
            'count'      => true,
        );
        
        if ( !current_user_can( 'gt_manager' ) && !current_user_can( 'editor' ) && !current_user_can( 'administrator' ) ) {
            $term_args['meta_key'] = 'created_by';
            $term_args['meta_value'] = get_current_user_id();
            $term_args['meta_compare'] = '==';
        }
        
        $term_query = new \WP_Term_Query( $term_args );
        $ids = array();
        if ( $term_query->terms ) {
            foreach ( $term_query->terms as $key => $value ) {
                array_push( $ids, $value->term_id );
            }
        }
        $gtObj = array(
            'gt_family_group' => $ids,
        );
        wp_localize_script( $this->plugin_name, 'gtObj', $gtObj );
    }
    
    /**
     * Register post type and taxonomy.
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
            'not_found'          => __( 'No members found.', 'genealogical-tree' ),
            'not_found_in_trash' => __( 'No members found in Trash.', 'genealogical-tree' ),
        );
        $supports = array( 'title', 'author', 'custom-fields' );
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
        $supports = array( 'title', 'custom-fields' );
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
        if ( defined( 'GENEALOGICAL_DEV' ) && GENEALOGICAL_DEV ) {
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
        $supports = array( 'title', 'author' );
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
    
    public function add_rewrite_rule_init()
    {
        add_rewrite_rule( 'gt-member/([A-Za-z0-9\\-\\_]+)/tab/([A-Za-z0-9\\-\\_]+)', 'index.php?gt-member=$matches[1]&tab=$matches[2]', 'top' );
    }
    
    public function query_vars_tab( $query_vars )
    {
        $query_vars[] = 'tab';
        return $query_vars;
    }
    
    /**
     * Register theavaScript for the admin area.
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
            'dashicons-groups',
            40
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Family Group', 'genealogical-tree' ),
            __( 'Family Group', 'genealogical-tree' ),
            'manage_categories',
            'edit-tags.php?taxonomy=gt-family-group&post_type=gt-member'
        );
        add_submenu_page(
            'genealogical-tree',
            __( 'Version Upgrade', 'genealogical-tree' ),
            __( 'Version Upgrade', 'genealogical-tree' ),
            'manage_categories',
            'fix-upgrade',
            array( $this, 'fix_ver_upgrade' )
        );
    }
    
    /**
     * Removes some menus by page.
     */
    public function admin_menu__remove_items()
    {
        
        if ( current_user_can( 'subscriber' ) ) {
            remove_menu_page( 'edit.php' );
            remove_menu_page( 'edit-comments.php' );
            remove_menu_page( 'upload.php' );
            remove_menu_page( 'tools.php' );
        }
    
    }
    
    /**
     * View for How It Work page.
     *
     * @since    1.0.0
     */
    public function gt_admin_as_gt_member( $user_id )
    {
        $user = get_user_by( 'id', $user_id );
        
        if ( $user && in_array( 'administrator', $user->roles ) ) {
            $user->add_cap( 'gt_member' );
            $user->add_cap( 'gt_manager' );
        }
        
        if ( $user && in_array( 'gt_manager', $user->roles ) ) {
            $user->add_cap( 'gt_member' );
        }
    }
    
    /**
     * Update user role on user_register
     */
    public function user_register_as_gt_member( $user_id )
    {
        $user = new \WP_User( $user_id );
        
        if ( isset( $_POST['role'] ) && $_POST['role'] == 'gt_member' ) {
            $user->remove_role( 'subscriber' );
            $user->add_role( 'gt_member' );
        }
        
        
        if ( isset( $_POST['role'] ) && $_POST['role'] == 'gt_manager' ) {
            $user->remove_role( 'subscriber' );
            $user->add_role( 'gt_manager' );
        }
    
    }
    
    /**
     * 
     */
    public function taxonomy_filter( $args, $taxonomies )
    {
        global  $pagenow ;
        if ( 'edit-tags.php' !== $pagenow || !in_array( 'gt-family-group', $taxonomies, true ) || current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            return $args;
        }
        $args['orderby'] = 'term_id';
        $args['order'] = 'desc';
        $args['meta_key'] = 'created_by';
        $args['meta_value'] = get_current_user_id();
        return $args;
    }
    
    /**
     * Member columns.
     *
     * @since    1.0.0
     */
    public function member_columns( $columns )
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
     * Member sortable columns.
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
     * Member posts born column.
     *
     * @since    1.0.0
     */
    public function member_posts_born_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'ID':
                echo  $post_id ;
                break;
            case 'born':
                $event = get_post_meta( $post_id, 'event', true );
                $date = '';
                if ( isset( $event['birt'] ) ) {
                    if ( isset( $event['birt'][0] ) ) {
                        $date = $event['birt'][0]['date'];
                    }
                }
                echo  $date ;
                break;
            case 'parent':
                $famc = get_post_meta( $post_id, 'famc' );
                foreach ( $famc as $key => $value ) {
                    $father_id = get_post_meta( $value, 'father', true );
                    if ( $father_id && get_post( $father_id ) ) {
                        echo  '<div><b>' . __( 'Father', 'genealogical-tree' ) . ' : </b><a href="' . get_edit_post_link( $father_id ) . '">' . get_the_title( $father_id ) . '</a></div>' ;
                    }
                    $mother_id = get_post_meta( $value, 'mother', true );
                    if ( $mother_id && get_post( $mother_id ) ) {
                        echo  '<div><b>' . __( 'Mother', 'genealogical-tree' ) . ' : </b><a href="' . get_edit_post_link( $mother_id ) . '">' . get_the_title( $mother_id ) . '</a></div>' ;
                    }
                }
                break;
            case 'spouses':
                $fams = get_post_meta( $post_id, 'fams' );
                if ( !empty($fams) ) {
                    foreach ( $fams as $key => $value ) {
                        $father_id = get_post_meta( $value, 'father', true );
                        $mother_id = get_post_meta( $value, 'mother', true );
                        $spouse_id = ( $father_id == $post_id ? $mother_id : $father_id );
                        
                        if ( $spouse_id && get_post( $spouse_id ) ) {
                            if ( $key > 0 ) {
                                echo  ', ' ;
                            }
                            echo  '<a href="' . get_edit_post_link( $spouse_id ) . '">' . get_the_title( $spouse_id ) . '</a>' ;
                        }
                    
                    }
                }
                break;
        }
    }
    
    public function set_custom_edit_gt_tree_columns( $columns )
    {
        $columns['shortcode'] = __( 'Shortcode', 'genealogical-tree' );
        return $columns;
    }
    
    public function custom_gt_tree_column( $column, $post_id )
    {
        switch ( $column ) {
            case 'shortcode':
                echo  '<input id="myInput" type="text" readonly value="[tree id=' . $post_id . ']">' ;
                break;
        }
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_member_info( $post_type )
    {
        add_meta_box(
            'genealogical-tree-member-meta-box',
            __( 'Member info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_member_info' ),
            'gt-member',
            'normal',
            'high'
        );
        add_meta_box(
            'genealogical-tree-member-additional-meta-box',
            __( 'Additional Member info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_member_additional_info' ),
            'gt-member',
            'normal',
            'high'
        );
        add_meta_box(
            'mishadiv',
            'Member Gallery',
            array( $this, 'render_misha_print_box' ),
            'gt-member',
            'side',
            'high'
        );
    }
    
    /**
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_info( $post )
    {
        $full_name = get_post_meta( $post->ID, 'full_name', true );
        $given_name = get_post_meta( $post->ID, 'given_name', true );
        $surname = get_post_meta( $post->ID, 'surname', true );
        $event = get_post_meta( $post->ID, 'event', true );
        $phone = ( get_post_meta( $post->ID, 'phone', true ) ? get_post_meta( $post->ID, 'phone', true ) : array( '' ) );
        $email = ( get_post_meta( $post->ID, 'email', true ) ? get_post_meta( $post->ID, 'email', true ) : array( '' ) );
        $address = ( get_post_meta( $post->ID, 'address', true ) ? get_post_meta( $post->ID, 'address', true ) : array( '' ) );
        $useable_members = $this->get_useable_members( $post );
        $males = $useable_members['males'];
        $females = $useable_members['females'];
        $unknowns = $useable_members['unknowns'];
        $sex = get_post_meta( $post->ID, 'sex', true );
        if ( !$event ) {
            $event = array();
        }
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-member-info.php';
    }
    
    /**
     * Register the
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_additional_info( $post )
    {
        $additional_info = get_post_meta( $post->ID, 'additional_info', true );
        wp_editor( htmlspecialchars_decode( $additional_info ), 'mettaabox_ID', $settings = array(
            'textarea_name' => 'additional_info',
        ) );
    }
    
    /*
     * Meta Box HTML
     */
    public function render_misha_print_box( $post )
    {
        $value = get_post_meta( $post->ID, 'some_custom_gallery', true );
        $html = '<div><ul class="misha_gallery_mtb">';
        /* array with image IDs for hidden field */
        $hidden = array();
        if ( $images = get_posts( array(
            'post_type'      => 'attachment',
            'orderby'        => 'post__in',
            'order'          => 'ASC',
            'post__in'       => explode( ',', $value ),
            'numberposts'    => -1,
            'post_mime_type' => 'image',
        ) ) ) {
            foreach ( $images as $image ) {
                $hidden[] = $image->ID;
                $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
                $html .= '<li data-id="' . $image->ID . '">
	            <span style="background-image:url(' . $image_src[0] . ')">
	            <img src="' . $image_src[0] . '">
	            </span>
	            <a href="#" class="misha_gallery_remove">×</a>
	            </li>';
            }
        }
        $html .= '</ul><div style="clear:both"></div></div>';
        $html .= '<input type="hidden" name="some_custom_gallery" value="' . join( ',', $hidden ) . '" /><a href="#" class="button misha_upload_gallery_button">Add Images</a>';
        echo  $html ;
    }
    
    /**
     * update meta boxes member info.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_member_info( $post_id )
    {
        // return if nonce field exist.
        if ( !isset( $_POST['_nonce_update_member_info_nonce'] ) ) {
            return $post_id;
        }
        $nonce = $_POST['_nonce_update_member_info_nonce'];
        // return if verify not success.
        if ( !wp_verify_nonce( $nonce, 'update_member_info_nonce' ) ) {
            return $post_id;
        }
        // stop autosave
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        // return if not desire post type, and user don't have permission to update.
        
        if ( 'gt-member' == $_POST['post_type'] ) {
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
        if ( isset( $_POST['tax_input']['gt-family-group'][1] ) && $_POST['tax_input']['gt-family-group'][1] ) {
            $family_group = true;
        }
        
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
        
        if ( $family_group ) {
            update_option( 'family_group_validation', false );
        }
        $full_name = ( isset( $_POST['gt']['full_name'] ) ? sanitize_text_field( $_POST['gt']['full_name'] ) : null );
        $given_name = ( isset( $_POST['gt']['given_name'] ) ? sanitize_text_field( $_POST['gt']['given_name'] ) : null );
        $surname = ( isset( $_POST['gt']['surname'] ) ? sanitize_text_field( $_POST['gt']['surname'] ) : null );
        $mother = ( isset( $_POST['gt']['mother'] ) ? sanitize_text_field( $_POST['gt']['mother'] ) : null );
        $father = ( isset( $_POST['gt']['father'] ) ? sanitize_text_field( $_POST['gt']['father'] ) : null );
        $sex = ( isset( $_POST['gt']['sex'] ) ? sanitize_text_field( $_POST['gt']['sex'] ) : null );
        update_post_meta( $post_id, 'full_name', $this->repear_full_name( $full_name ) );
        update_post_meta( $post_id, 'given_name', $given_name );
        update_post_meta( $post_id, 'surname', $surname );
        update_post_meta( $post_id, 'mother', $mother );
        update_post_meta( $post_id, 'father', $father );
        update_post_meta( $post_id, 'sex', $sex );
        $event = ( isset( $_POST['gt']['event'] ) ? $_POST['gt']['event'] : array() );
        $phone = ( isset( $_POST['gt']['phone'] ) ? $_POST['gt']['phone'] : array() );
        $email = ( isset( $_POST['gt']['email'] ) ? $_POST['gt']['email'] : array() );
        $address = ( isset( $_POST['gt']['address'] ) ? $_POST['gt']['address'] : array() );
        $xcv = 0;
        
        if ( $event ) {
            foreach ( $event as $key1 => $value1 ) {
                
                if ( is_int( $key1 ) ) {
                    foreach ( $value1 as $key2 => $value2 ) {
                        
                        if ( $value2['type'] ) {
                            $event[$value2['type']][$xcv] = array(
                                'type'  => sanitize_text_field( $value2['type'] ),
                                'ref'   => sanitize_text_field( $value2['ref'] ),
                                'date'  => sanitize_text_field( $value2['date'] ),
                                'place' => sanitize_text_field( $value2['place'] ),
                            );
                            $xcv++;
                        }
                    
                    }
                    unset( $event[$key1] );
                }
            
            }
            if ( $event['birt'] ) {
                foreach ( $event['birt'] as $key => $birt ) {
                    
                    if ( $birt['date'] || $birt['place'] ) {
                    } else {
                        unset( $event['birt'][$key] );
                    }
                
                }
            }
            if ( $event['deat'] ) {
                foreach ( $event['deat'] as $key => $deat ) {
                    
                    if ( $deat['date'] || $deat['place'] ) {
                    } else {
                        unset( $event['deat'][$key] );
                    }
                
                }
            }
            if ( !$event['birt'] ) {
                unset( $event['birt'] );
            }
            if ( !$event['deat'] ) {
                unset( $event['deat'] );
            }
        }
        
        if ( $phone ) {
            foreach ( $phone as $key => $ph ) {
                $phone[$key] = sanitize_text_field( $ph );
            }
        }
        if ( $email ) {
            foreach ( $email as $key => $em ) {
                
                if ( sanitize_email( $em ) ) {
                    $email[$key] = sanitize_email( $em );
                } else {
                    unset( $email[$key] );
                }
            
            }
        }
        if ( $address ) {
            foreach ( $address as $key => $addr ) {
                $address[$key] = sanitize_text_field( $addr );
            }
        }
        update_post_meta( $post_id, 'event', $event );
        update_post_meta( $post_id, 'phone', $phone );
        update_post_meta( $post_id, 'email', $email );
        update_post_meta( $post_id, 'address', $address );
        
        if ( isset( $_POST['additional_info'] ) ) {
            $additional_info = wp_kses_post( $_POST['additional_info'] );
            update_post_meta( $post_id, 'additional_info', $additional_info );
        }
        
        
        if ( isset( $_POST['some_custom_gallery'] ) ) {
            $some_custom_gallery = $_POST['some_custom_gallery'];
            update_post_meta( $post_id, 'some_custom_gallery', $some_custom_gallery );
        }
        
        $famc_old = get_post_meta( $post_id, 'famc' );
        $fams_old = get_post_meta( $post_id, 'fams' );
        $indis = array();
        array_push( $indis, $post_id );
        $parents = ( isset( $_POST['gt']['parents'] ) ? $_POST['gt']['parents'] : array() );
        delete_post_meta( $post_id, 'famc' );
        foreach ( $parents as $key => $parent ) {
            $mother = $parent['mother'];
            $father = $parent['father'];
            if ( $mother || $father ) {
                $family_id = $this->findOrCreateFamily( $mother, $father, array( $post_id ) );
            }
            array_push( $indis, $mother );
            array_push( $indis, $father );
        }
        $spouses = ( isset( $_POST['gt']['spouses'] ) ? $_POST['gt']['spouses'] : array() );
        delete_post_meta( $post_id, 'fams' );
        foreach ( $spouses as $key => $spouse ) {
            $sex = ( get_post_meta( $post_id, 'sex', true ) ? get_post_meta( $post_id, 'sex', true ) : null );
            $spouse_id = $spouse['id'];
            $chills = ( isset( $spouse['chills'] ) ? $spouse['chills'] : array() );
            
            if ( $spouse_id || !empty($chills) ) {
                $motherOrFather = $this->isFatherOrMother( $post_id, $spouse_id );
                $mother = $motherOrFather['mother'];
                $father = $motherOrFather['father'];
                if ( $mother || $father ) {
                    $family_id = $this->findOrCreateFamily( $mother, $father, $chills );
                }
                array_push( $indis, $mother );
                array_push( $indis, $father );
            }
        
        }
        $famc = get_post_meta( $post_id, 'famc' );
        $fams = get_post_meta( $post_id, 'fams' );
        $missing_famc = array_diff( $famc_old, $famc );
        $missing_fams = array_diff( $fams_old, $fams );
        foreach ( $missing_famc as $key => $fam ) {
            delete_post_meta( $fam, 'chills', $post_id );
            $this->checkAndDeleteFamily( $fam, $indis );
        }
        foreach ( $missing_fams as $key => $fam ) {
            delete_post_meta( $fam, 'father', $post_id );
            delete_post_meta( $fam, 'mother', $post_id );
            $this->checkAndDeleteFamily( $fam, $indis );
        }
    }
    
    /**
     * Member columns.
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_tree_settings( $post_type )
    {
        add_meta_box(
            'genealogical-tree-tree-meta-box',
            __( 'Tree Settings', 'genealogical-tree' ),
            array( $this, 'render_meta_box_tree_settings' ),
            'gt-tree',
            'normal',
            'high'
        );
    }
    
    /**
     * Member columns.
     *
     * @since    1.0.0
     */
    public function render_meta_box_tree_settings( $post )
    {
        $data = $this->tree_default_meta();
        $bd_style = array(
            'dotted' => __( 'Dotted', 'genealogical-tree' ),
            'dashed' => __( 'Dashed', 'genealogical-tree' ),
            'solid'  => __( 'Solid', 'genealogical-tree' ),
            'double' => __( 'Double', 'genealogical-tree' ),
            'groove' => __( 'Groove', 'genealogical-tree' ),
            'ridge'  => __( 'Ridge', 'genealogical-tree' ),
            'inset'  => __( 'Inset', 'genealogical-tree' ),
            'outset' => __( 'Outset', 'genealogical-tree' ),
            'none'   => __( 'None', 'genealogical-tree' ),
            'hidden' => __( 'Hidden', 'genealogical-tree' ),
        );
        $data_saved = get_post_meta( $post->ID, 'tree', true );
        foreach ( $data as $key => $value ) {
            
            if ( is_array( $value ) ) {
                foreach ( $value as $key2 => $value2 ) {
                    
                    if ( is_array( $value2 ) ) {
                        foreach ( $value2 as $key3 => $value3 ) {
                            
                            if ( is_array( $value3 ) ) {
                                foreach ( $value3 as $key4 => $value4 ) {
                                    $data[$key][$key2][$key3][$key4] = ( isset( $data_saved[$key][$key2][$key3][$key4] ) && $data_saved[$key][$key2][$key3][$key4] ? $data_saved[$key][$key2][$key3][$key4] : $value4 );
                                }
                            } else {
                                $data[$key][$key2][$key3] = ( isset( $data_saved[$key][$key2][$key3] ) && $data_saved[$key][$key2][$key3] ? $data_saved[$key][$key2][$key3] : $value3 );
                            }
                        
                        }
                    } else {
                        $data[$key][$key2] = ( isset( $data_saved[$key][$key2] ) && $data_saved[$key][$key2] ? $data_saved[$key][$key2] : $value2 );
                    }
                
                }
            } else {
                $data[$key] = ( isset( $data_saved[$key] ) && $data_saved[$key] ? $data_saved[$key] : $value );
            }
        
        }
        require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-google-fonts.php';
        if ( !isset( $premium ) || !$premium ) {
            require_once plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-settings.php';
        }
    }
    
    /**
     * update meta boxes member info.
     *
     * @since    1.0.0
     */
    public function update_meta_boxes_tree_settings( $post_id )
    {
        if ( !isset( $_POST['_nonce_update_tree_settings_nonce'] ) ) {
            return $post_id;
        }
        $nonce = $_POST['_nonce_update_tree_settings_nonce'];
        if ( !wp_verify_nonce( $nonce, 'update_tree_settings_nonce' ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        
        if ( 'gt-tree' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
        
        update_post_meta( $post_id, 'tree', $_POST['tree'] );
    }
    
    /**
     * 
     */
    public function post_class_filter( $classes, $class, $post_id )
    {
        if ( !is_admin() ) {
            return $classes;
        }
        $screen = get_current_screen();
        if ( 'gt-member' != $screen->post_type && 'edit' != $screen->base ) {
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
     * Register theavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function get_useable_members( $post )
    {
        $males = array();
        $females = array();
        $unknowns = array();
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'author'         => get_current_user_id(),
            'post__not_in'   => array( $post->ID ),
            'order'          => 'ASC',
            'orderby'        => 'title',
            'meta_query'     => array( array(
            'key'     => 'merged_to',
            'compare' => 'NOT EXISTS',
        ) ),
        );
        if ( current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' ) ) {
            unset( $args['author'] );
        }
        $query = new \WP_Query( $args );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $member ) {
                if ( get_post_meta( $member->ID, 'sex', true ) === 'M' ) {
                    array_push( $males, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) === 'F' ) {
                    array_push( $females, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) !== 'F' && get_post_meta( $member->ID, 'sex', true ) !== 'M' ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        // member by merging.
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
                if ( get_post_meta( $member->ID, 'sex', true ) === 'M' ) {
                    array_push( $males, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) === 'F' ) {
                    array_push( $females, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) !== 'F' && get_post_meta( $member->ID, 'sex', true ) !== 'M' ) {
                    array_push( $unknowns, $member->ID );
                }
            }
        }
        // member by allowed group.
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
                if ( get_post_meta( $member->ID, 'sex', true ) === 'M' ) {
                    array_push( $males, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) === 'F' ) {
                    array_push( $females, $member->ID );
                }
                if ( get_post_meta( $member->ID, 'sex', true ) !== 'F' && get_post_meta( $member->ID, 'sex', true ) !== 'M' ) {
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
     * 
     */
    public function select_member_html(
        $females = array(),
        $males = array(),
        $unknowns = array(),
        $key = 0,
        $name = '',
        $value = '',
        $multiple = false
    )
    {
        ?>
			<option value=""><?php 
        _e( 'Select', 'genealogical-tree' );
        ?> <?php 
        echo  $name ;
        ?></option>
			<optgroup label="<?php 
        _e( 'Female', 'genealogical-tree' );
        ?>">
				<?php 
        foreach ( $females as $key => $female ) {
            ?>
				<option <?php 
            if ( $value == $female ) {
                echo  'selected' ;
            }
            ?> value="<?php 
            echo  $female ;
            ?>">
					<?php 
            echo  get_post_meta( $female, 'full_name', true ) ;
            ?>	
					<?php 
            echo  '[' . $female . ']' ;
            ?>	
				</option>
				<?php 
        }
        ?>
			</optgroup>
			<optgroup label="<?php 
        _e( 'Male', 'genealogical-tree' );
        ?>">
				<?php 
        foreach ( $males as $key => $male ) {
            ?>
				<option <?php 
            if ( $value == $male ) {
                echo  'selected' ;
            }
            ?> value="<?php 
            echo  $male ;
            ?>">
					<?php 
            echo  get_post_meta( $male, 'full_name', true ) ;
            ?>	
					<?php 
            echo  '[' . $male . ']' ;
            ?>	
				</option>
				<?php 
        }
        ?>
			</optgroup>
			<optgroup label="<?php 
        _e( 'Unknown', 'genealogical-tree' );
        ?>">
				<?php 
        foreach ( $unknowns as $key => $unknown ) {
            ?>
				<option <?php 
            if ( $value == $unknown ) {
                echo  'selected' ;
            }
            ?> value="<?php 
            echo  $unknown ;
            ?>">
					<?php 
            echo  get_post_meta( $unknown, 'full_name', true ) ;
            ?>	
					<?php 
            echo  '[' . $unknown . ']' ;
            ?>	
				</option>
				<?php 
        }
        ?>
			</optgroup>
		<?php 
    }
    
    /**
     * repear full name.
     *
     * @since    1.0.0
     */
    public function repear_full_name( $name )
    {
        return wp_strip_all_tags( trim( str_replace( array( '/', '\\' ), array( ' ', '' ), $name ) ) );
    }
    
    /**
     * Attach Detached Family
     *
     * @since    2.1.1
     */
    public function checkAndDeleteFamily( $fam, $indis )
    {
        
        if ( get_post_meta( $fam, 'father', true ) && get_post_meta( $fam, 'mother', true ) || get_post_meta( $fam, 'mother', true ) && get_post_meta( $fam, 'chills', true ) || get_post_meta( $fam, 'father', true ) && get_post_meta( $fam, 'chills', true ) ) {
        } else {
            $indis = array_unique( $indis );
            foreach ( $indis as $key => $ind ) {
                delete_post_meta( $ind, 'fams', $fam );
                delete_post_meta( $ind, 'famc', $fam );
            }
            wp_delete_post( $fam );
        }
    
    }
    
    /**
     * Attach Detached Family
     *
     * @since    2.1.1
     */
    public function findOrCreateFamily( $mother, $father, $chills )
    {
        
        if ( $mother || $father ) {
            if ( $mother && $father ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'mother',
                    'value'   => $mother,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'father',
                    'value'   => $father,
                    'compare' => '=',
                ),
                ),
                ) );
            }
            if ( !$mother && $father ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'father',
                    'value'   => $father,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'mother',
                    'compare' => 'NOT EXISTS',
                ),
                ),
                ) );
            }
            if ( $mother && !$father ) {
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-family',
                    'posts_per_page' => 1,
                    'meta_query'     => array(
                    'relation' => 'AND',
                    array(
                    'key'     => 'mother',
                    'value'   => $mother,
                    'compare' => '=',
                ),
                    array(
                    'key'     => 'father',
                    'compare' => 'NOT EXISTS',
                ),
                ),
                ) );
            }
            
            if ( isset( $query ) && $query->posts ) {
                $family_id = current( $query->posts )->ID;
            } else {
                if ( $mother && $father ) {
                    $post_title = get_the_title( $father ) . ' and ' . get_the_title( $mother );
                }
                if ( !$mother && $father ) {
                    $post_title = get_the_title( $father );
                }
                if ( $mother && !$father ) {
                    $post_title = get_the_title( $mother );
                }
                $family_id = wp_insert_post( array(
                    'post_title'   => $post_title,
                    'post_content' => '',
                    'post_status'  => 'publish',
                    'post_author'  => get_current_user_id(),
                    'post_type'    => 'gt-family',
                ) );
            }
            
            if ( $father ) {
                if ( !in_array( $father, get_post_meta( $family_id, 'father' ) ) ) {
                    add_post_meta( $family_id, 'father', $father );
                }
            }
            if ( $mother ) {
                if ( !in_array( $mother, get_post_meta( $family_id, 'mother' ) ) ) {
                    add_post_meta( $family_id, 'mother', $mother );
                }
            }
            if ( is_array( $chills ) && !empty($chills) ) {
                foreach ( $chills as $key => $chill ) {
                    if ( !in_array( $chill, get_post_meta( $family_id, 'chills' ) ) ) {
                        add_post_meta( $family_id, 'chills', $chill );
                    }
                    if ( !in_array( $family_id, get_post_meta( $chill, 'famc' ) ) ) {
                        add_post_meta( $chill, 'famc', $family_id );
                    }
                }
            }
            if ( $father ) {
                if ( !in_array( $family_id, get_post_meta( $father, 'fams' ) ) ) {
                    add_post_meta( $father, 'fams', $family_id );
                }
            }
            if ( $mother ) {
                if ( !in_array( $family_id, get_post_meta( $mother, 'fams' ) ) ) {
                    add_post_meta( $mother, 'fams', $family_id );
                }
            }
            return $family_id;
        }
    
    }
    
    /**
     * get family group name
     *
     * @since    1.0.0
     */
    public function generate_family_group_name_by_filename( $family_group_name )
    {
        
        if ( $family_group_name ) {
            $family_group_name = sanitize_text_field( $family_group_name );
            $term = term_exists( $family_group_name, 'gt-family-group' );
            $suggestions = array();
            
            if ( 0 !== $term && null !== $term ) {
                $terms = get_terms( 'gt-family-group', array(
                    'hide_empty' => false,
                ) );
                $terms_slug = array();
                if ( $terms ) {
                    foreach ( $terms as $key => $term ) {
                        array_push( $terms_slug, $term->slug );
                    }
                }
                $count = 0;
                $names_left = 1000;
                while ( $names_left > 0 ) {
                    $count++;
                    
                    if ( !in_array( sanitize_title( $family_group_name ) . '-' . $count, $terms_slug ) ) {
                        $suggestions[] = $family_group_name . ' ' . $count;
                        $names_left--;
                    }
                
                }
            } else {
                return $family_group_name;
            }
            
            return $suggestions[0];
        }
    
    }
    
    /**
     * 
     */
    public function get_aditionals_events()
    {
        return array(
            'name'            => array(
            'type'  => 'name',
            'title' => __( 'Name', 'genealogical-tree' ),
        ),
            'buri'            => array(
            'type'  => 'buri',
            'title' => __( 'Burial', 'genealogical-tree' ),
        ),
            'adop'            => array(
            'type'  => 'adop',
            'title' => __( 'Adoption', 'genealogical-tree' ),
        ),
            'enga'            => array(
            'type'  => 'enga',
            'title' => __( 'Engagement', 'genealogical-tree' ),
        ),
            'marr'            => array(
            'type'  => 'marr',
            'title' => __( 'Marriage', 'genealogical-tree' ),
        ),
            'div'             => array(
            'type'  => 'div',
            'title' => __( 'Divorce', 'genealogical-tree' ),
        ),
            'address_(other)' => array(
            'type'  => 'address_(other)',
            'title' => __( 'Address (Other)', 'genealogical-tree' ),
        ),
            'bapm'            => array(
            'type'  => 'bapm',
            'title' => __( 'Baptism', 'genealogical-tree' ),
        ),
            'chr'             => array(
            'type'  => 'chr',
            'title' => __( 'Christening', 'genealogical-tree' ),
        ),
            'arms'            => array(
            'type'  => 'arms',
            'title' => __( 'arms', 'genealogical-tree' ),
        ),
            'barm'            => array(
            'type'  => 'barm',
            'title' => __( 'BAR_MITZVAH', 'genealogical-tree' ),
        ),
            'bles'            => array(
            'type'  => 'bles',
            'title' => __( 'BLESSING', 'genealogical-tree' ),
        ),
            'cens'            => array(
            'type'  => 'cens',
            'title' => __( 'CENSUS', 'genealogical-tree' ),
        ),
            'crem'            => array(
            'type'  => 'crem',
            'title' => __( 'CREMATION', 'genealogical-tree' ),
        ),
            'emig'            => array(
            'type'  => 'emig',
            'title' => __( 'EMIGRATION', 'genealogical-tree' ),
        ),
            'grad'            => array(
            'type'  => 'grad',
            'title' => __( 'GRADUATION', 'genealogical-tree' ),
        ),
            'immi'            => array(
            'type'  => 'immi',
            'title' => __( 'IMMIGRATION', 'genealogical-tree' ),
        ),
            'natu'            => array(
            'type'  => 'natu',
            'title' => __( 'NATURALIZATION', 'genealogical-tree' ),
        ),
            'reti'            => array(
            'type'  => 'reti',
            'title' => __( 'RETIREMENT', 'genealogical-tree' ),
        ),
            'prob'            => array(
            'type'  => 'prob',
            'title' => __( 'PROBATE', 'genealogical-tree' ),
        ),
            'will'            => array(
            'type'  => 'will',
            'title' => __( 'WILL', 'genealogical-tree' ),
        ),
            'occupation_1'    => array(
            'type'  => 'occupation_1',
            'title' => __( 'Occupation', 'genealogical-tree' ),
        ),
        );
    }
    
    /**
     * 
     */
    public function gt_update_db_check()
    {
        
        if ( !get_site_option( '_gt_version_fixed' ) ) {
            $this->fix_ver_upgrade_ajax();
            add_site_option( '_gt_version_fixed', time() );
        }
    
    }
    
    /**
     * Register the .
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_member_info_dev( $post_type )
    {
        add_meta_box(
            'genealogical-tree-member-meta-box-dev',
            __( 'Member info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_member_info_dev' ),
            'gt-member',
            'normal',
            'high'
        );
    }
    
    /**
     * Register the .
     *
     * @since    1.0.0
     */
    public function add_meta_boxes_family_info_dev( $post_type )
    {
        add_meta_box(
            'genealogical-tree-family-meta-box-dev',
            __( 'Family info', 'genealogical-tree' ),
            array( $this, 'render_meta_box_family_info_dev' ),
            'gt-family',
            'normal',
            'high'
        );
    }
    
    /**
     * Register the
     *
     * @since    1.0.0
     */
    public function render_meta_box_family_info_dev( $post )
    {
        echo  "<pre>" ;
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['chills'] = get_post_meta( $post->ID, 'chills' );
        print_r( $get_post_meta );
        echo  "<pre>" ;
    }
    
    /**
     * Register the
     *
     * @since    1.0.0
     */
    public function render_meta_box_member_info_dev( $post )
    {
        print_r( '<pre>' );
        $get_post_meta = get_post_meta( $post->ID );
        $get_post_meta['event'] = get_post_meta( $post->ID, 'event' );
        print_r( $get_post_meta );
        print_r( '</pre>' );
    }
    
    /**
     * fix ver upgrade
     *
     * @since    1.0.0
     */
    public function fix_ver_upgrade()
    {
        ?>
		<div class="wrap">
			<h1>  <?php 
        _e( 'Upgrade Fix', 'genealogical-tree' );
        ?> </h1>
			<p>
				<b><?php 
        _e( 'If you are upgradeed from older version of 2.1.2. You may need to click upgrade database button.', 'genealogical-tree' );
        ?> </b>
			</p>
			<p><?php 
        _e( 'Please click Upgrade Fix button if your family tree not working properly.', 'genealogical-tree' );
        ?> </p>
			<p>
				<button class="button fix_ver_upgrade" type="button"> <?php 
        _e( 'Upgrade Fix', 'genealogical-tree' );
        ?> </button>
			</p>
		</div>
		<?php 
    }
    
    /**
     * fix ver upgrade
     *
     * @since    1.0.0
     */
    public function fix_ver_upgrade_ajax()
    {
        $args = array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'meta_query'     => array( array(
            'key'     => 'fams',
            'compare' => 'NOT EXISTS',
        ), array(
            'key'     => 'famc',
            'compare' => 'NOT EXISTS',
        ) ),
        );
        $query = new \WP_Query( $args );
        $members = $query->posts;
        foreach ( $members as $key => $value ) {
            $mother = get_post_meta( $value->ID, 'mother', true );
            $father = get_post_meta( $value->ID, 'father', true );
            $this->findOrCreateFamily( $mother, $father, array( $value->ID ) );
        }
        foreach ( $members as $key => $value ) {
            $spouses = get_post_meta( $value->ID, 'spouses', true );
            foreach ( $spouses as $key => $spouse ) {
                $spouse_id = $spouse['id'];
                
                if ( $spouse_id ) {
                    $motherOrFather = $this->isFatherOrMother( $value->ID, $spouse_id );
                    $mother = $motherOrFather['mother'];
                    $father = $motherOrFather['father'];
                    if ( $mother || $father ) {
                        $family_id = $this->findOrCreateFamily( $mother, $father, array() );
                    }
                }
            
            }
        }
    }
    
    public function isFatherOrMother(
        $post_id,
        $spouse_id,
        $mother = null,
        $father = null
    )
    {
        $sex = ( get_post_meta( $post_id, 'sex', true ) ? get_post_meta( $post_id, 'sex', true ) : null );
        
        if ( $sex ) {
            
            if ( $sex === 'M' ) {
                $father = $post_id;
                $mother = $spouse_id;
            }
            
            
            if ( $sex === 'F' ) {
                $mother = $post_id;
                $father = $spouse_id;
            }
        
        } else {
            $sex = ( get_post_meta( $spouse_id, 'sex', true ) ? get_post_meta( $spouse_id, 'sex', true ) : null );
            
            if ( $sex ) {
                
                if ( $sex === 'M' ) {
                    $mother = $post_id;
                    $father = $spouse_id;
                }
                
                
                if ( $sex === 'F' ) {
                    $father = $post_id;
                    $mother = $spouse_id;
                }
            
            } else {
                $father = $post_id;
                $mother = $spouse_id;
            }
        
        }
        
        return array(
            'mother' => $mother,
            'father' => $father,
        );
    }
    
    /**
     * 
     */
    public function before_delete_post( $post_id )
    {
        $args = array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'meta_query'     => array(
            'relation' => 'OR',
            array(
            'key'     => 'father',
            'compare' => '=',
            'value'   => $post_id,
        ),
            array(
            'key'     => 'father',
            'compare' => '=',
            'value'   => $post_id,
        ),
            array(
            'key'     => 'chills',
            'compare' => 'IN',
            'value'   => $post_id,
        ),
        ),
        );
        $query = new \WP_Query( $args );
        $families = $query->posts;
        if ( $families ) {
            foreach ( $families as $key => $value ) {
                delete_post_meta( $value->ID, 'father', $post_id );
                delete_post_meta( $value->ID, 'mother', $post_id );
                delete_post_meta( $value->ID, 'chills', $post_id );
            }
        }
    }
    
    /**
     * 
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
        
        return $allcaps;
    }
    
    /**
     * 
     */
    public function bp_family_tree_tab()
    {
        global  $bp ;
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
    
    /**
     * 
     */
    public function bp_family_tree_screen()
    {
        bp_core_load_template( 'buddypress/members/single/plugins' );
    }
    
    /**
     * 
     */
    public function bp_family_tree_title()
    {
        echo  'Family Tree' ;
    }
    
    /**
     * 
     */
    public function bp_family_tree_content()
    {
        echo  'Content' ;
    }

}