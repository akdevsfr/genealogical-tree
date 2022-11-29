<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin/inc
 */
namespace Zqe\Inc;

use function  Symfony\Component\VarDumper\Dumper\esc ;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin/inc
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Admin_Family_Group
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      \Zqe\Genealogical_Tree    $plugin    The ID of this plugin.
     */
    private  $plugin ;
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
     * @param      string $plugin       The name of this plugin.
     */
    public function __construct( $plugin )
    {
        $this->plugin = $plugin;
    }
    
    /**
     * Family group validation notice handler.
     *
     * @since    1.0.0
     */
    public function family_group_validation_notice_handler()
    {
        $errors = get_option( 'family_group_validation' );
        if ( $errors ) {
            echo  '<div class="error"><p>' . esc_html( $errors ) . '</p></div>' ;
        }
        update_option( 'family_group_validation', false );
    }
    
    /**
     * Save extra taxonomy fields callback function.
     *
     * @param  mixed $term_id term_id.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function update_family_group( $term_id )
    {
    }
    
    /**
     * Create family group free.
     *
     * @param  mixed $term_id term_id.
     *
     * @since    1.0.0
     */
    public function create_family_group( $term_id )
    {
        
        if ( gt_fs()->is_not_paying() ) {
            $terms = get_terms( array(
                'taxonomy'   => 'gt-family-group',
                'hide_empty' => false,
            ) );
            
            if ( count( $terms ) > 1 ) {
                wp_delete_term( $term_id, 'gt-family-group' );
                echo  '<a href="' . esc_attr( gt_fs()->get_upgrade_url() ) . '">' . esc_html__( 'Upgrade Now!', 'genealogical-tree' ) . '</a> to create more family group. If you are on trial you will able to create multiple family group after trial.' ;
                die;
            }
        
        }
        
        $this->generate_default_page( $term_id );
    }
    
    /**
     * Generate page for family.
     *
     * @param  mixed $family_group_id family_group_id.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function generate_default_page( $family_group_id )
    {
        $family_group_obj = get_term( $family_group_id );
        $family_group_name = $family_group_obj->name;
        $my_post = array(
            'post_title'   => wp_strip_all_tags( 'Family Tree - ' . $family_group_name ),
            'post_content' => '',
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
            'post_type'    => 'gt-tree',
        );
        $tree_page = wp_insert_post( $my_post );
        
        if ( $tree_page ) {
            update_post_meta( $tree_page, 'tree', array(
                'family' => $family_group_id,
            ) );
            update_term_meta( $family_group_id, 'tree_page', $tree_page );
            update_term_meta( $family_group_id, 'created_by', get_current_user_id() );
        }
        
        return $tree_page;
    }
    
    /**
     * Generate page for family on ajax request.
     *
     * @since    1.0.0
     */
    public function generate_default_tree()
    {
        
        if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'gt_ajax_nonce' ) ) {
            $family_group_id = ( isset( $_POST['family_id'] ) ? sanitize_text_field( wp_unslash( $_POST['family_id'] ) ) : array() );
            $tree_page = get_term_meta( $family_group_id, 'tree_page', true );
            if ( $family_group_id && !$tree_page ) {
                return $this->generate_default_page( $family_group_id );
            }
        }
    
    }
    
    /**
     * Set the submenu as active/current while anywhere in your Custom Post Type ( member ).
     *
     * @param string $parent_file The parent file.
     *
     * @return string
     *
     * @since    1.0.0
     */
    public function set_family_group_current_menu( $parent_file )
    {
        global  $submenu_file, $current_screen, $pagenow ;
        if ( 'gt-member' === $current_screen->post_type ) {
            
            if ( 'edit-tags.php' === $pagenow || 'term.php' === $pagenow ) {
                $submenu_file = 'edit-tags.php?taxonomy=gt-family-group&post_type=' . $current_screen->post_type;
                $parent_file = 'genealogical-tree';
            }
        
        }
        return $parent_file;
    }
    
    /**
     * Function for `gt_family_group_filter`.
     *
     * @param  mixed $args args.
     * @param  mixed $taxonomies taxonomies.
     * @return mixed
     */
    public function gt_family_group_filter( $args, $taxonomies )
    {
        
        if ( in_array( 'gt-family-group', $taxonomies, true ) && !(current_user_can( 'gt_manager' ) || current_user_can( 'editor' ) || current_user_can( 'administrator' )) ) {
            $args['meta_key'] = 'created_by';
            $args['meta_value'] = get_current_user_id();
        }
        
        return $args;
    }
    
    /**
     * Function for `add_family_group_meta`
     *
     * @return void
     */
    public function add_family_group_meta()
    {
        $fields = array(
            array(
            'label'    => esc_html__( 'Default Tree', 'genealogical-tree' ),
            'id'       => 'term-default-tree-wrap',
            'desc'     => '',
            'type'     => 'callback',
            'callback' => array( $this, 'default_tree' ),
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Possible Roots', 'genealogical-tree' ),
            'desc'     => '',
            'id'       => 'term-possible-roots-wrap',
            'type'     => 'callback',
            'callback' => array( $this, 'possible_roots' ),
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Members Page', 'genealogical-tree' ),
            'desc'     => '',
            'id'       => 'term-members-page-wrap',
            'type'     => 'callback',
            'callback' => array( $this, 'members_page' ),
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Merge Request', 'genealogical-tree' ),
            'desc'     => esc_html__( 'Allow user to request to merge members of this family group.', 'genealogical-tree' ),
            'id'       => 'term-merge-request-wrap',
            'type'     => 'checkbox',
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Use Request', 'genealogical-tree' ),
            'desc'     => esc_html__( 'Allow user to request to use members of this family group.', 'genealogical-tree' ),
            'id'       => 'term-use-request-wrap',
            'type'     => 'checkbox',
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Suggestion', 'genealogical-tree' ),
            'desc'     => esc_html__( 'User can send suggestion about member.', 'genealogical-tree' ),
            'id'       => 'term-suggestion-wrap',
            'type'     => 'checkbox',
            'editOnly' => true,
        ),
            array(
            'label'    => esc_html__( 'Allow Use By', 'genealogical-tree' ),
            'desc'     => esc_html__( 'This will allow selected user to use members of this family group on there family tree. It will not allow selected user to update the members of this family group.', 'genealogical-tree' ),
            'id'       => 'term-allow-use-by-wrap',
            'type'     => 'callback',
            'callback' => array( $this, 'allow_use_by' ),
            'editOnly' => true,
        )
        );
        new \Zqe\Wp_Term_Meta( 'gt-family-group', 'gt-member', $fields );
    }
    
    /**
     * Function for ` allow_use_by`
     *
     * @param  mixed $field field.
     *
     * @return mixed
     */
    public function allow_use_by( $field )
    {
        $term = ( isset( $field['term'] ) && $field['term'] ? (object) $field['term'] : null );
        
        if ( $term ) {
            $allow_update_by = ( get_term_meta( $term->term_id, 'allow_update_by', true ) ? get_term_meta( $term->term_id, 'allow_update_by', true ) : array() );
            $created_by = get_term_meta( $term->term_id, 'created_by', true );
            $users = get_users( array(
                'role__in'     => array( 'gt_member' ),
                'role__not_in' => array( 'administrator', 'editor', 'gt_manager' ),
            ) );
            ob_start();
            ?>
			<?php 
            wp_nonce_field( 'allow_update_by', 'allow_update_by_nonce' );
            ?>
			<select name="allow_update_by[]" multiple>
				<option value> <?php 
            esc_html_e( 'Select User', 'genealogical-tree' );
            ?> </option>
				<?php 
            foreach ( $users as $key => $user ) {
                
                if ( $user->ID !== $created_by ) {
                    
                    if ( user_can( $user->ID, 'gt_manager' ) || user_can( $user->ID, 'editor' ) || user_can( $user->ID, 'administrator' ) || in_array( $user->ID, $allow_update_by, true ) ) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    
                    ?>
						<option value="<?php 
                    echo  esc_attr( $user->ID ) ;
                    ?>" <?php 
                    echo  esc_attr( $selected ) ;
                    ?> >
							<?php 
                    echo  esc_html( $user->user_email ) ;
                    ?>
						</option>
						<?php 
                }
            
            }
            ?>
			</select>
			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * Function for members_page.
     *
     * @param  mixed $field field.
     * @return mixed
     */
    public function members_page( $field )
    {
        $term = ( isset( $field['term'] ) && $field['term'] ? (object) $field['term'] : null );
        
        if ( $term ) {
            ob_start();
            ?>
			<a class="button" target="_blank" href="<?php 
            echo  esc_attr( get_term_link( $term, 'gt-family-group' ) ) ;
            ?>"><?php 
            esc_html_e( 'View Members Page.', 'genealogical-tree' );
            ?> </a></p>
			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * Function for `default_tree`.
     *
     * @param  mixed $field field.
     *
     * @return string
     */
    public function default_tree( $field )
    {
        $term = ( isset( $field['term'] ) && $field['term'] ? (object) $field['term'] : null );
        
        if ( $term ) {
            $tree_page = $this->get_tree_page_by_term( $term );
            $tree_pages = $this->get_tree_pages_by_term( $term );
            ob_start();
            ?>
			<?php 
            
            if ( !$tree_pages ) {
                ?>
				<button data-id="<?php 
                echo  esc_attr( $term->term_id ) ;
                ?>" class="button generate_default_tree">
					<?php 
                esc_html_e( 'Generate Default Tree', 'genealogical-tree' );
                ?>
				</button>
			<?php 
            }
            
            ?>

			<?php 
            
            if ( $tree_pages ) {
                ?>
				<select name="tree_page">
					<option value> <?php 
                esc_html_e( 'Select Default Tree', 'genealogical-tree' );
                ?> </option>
					<?php 
                foreach ( $tree_pages as $key => $page ) {
                    $tree = get_post_meta( $page->ID, 'tree', true );
                    
                    if ( isset( $tree['family'] ) && $tree['family'] === $term->term_id ) {
                        ?>
							<option <?php 
                        selected( $page->ID, $tree_page );
                        ?> value="<?php 
                        echo  esc_attr( $page->ID ) ;
                        ?>">
								<?php 
                        echo  esc_html( $page->post_title ) ;
                        ?>
							</option>
							<?php 
                    }
                
                }
                ?>
				</select>
			<?php 
            }
            
            ?>

			<?php 
            
            if ( $tree_page ) {
                ?>
				<a target="_blank" class="button" href="<?php 
                echo  ( esc_attr( get_the_permalink( $tree_page ) ) ? esc_attr( get_the_permalink( $tree_page ) ) : '' ) ;
                ?>">
					<?php 
                esc_html_e( 'View Tree.', 'genealogical-tree' );
                ?>
				</a>
			<?php 
            }
            
            ?>

			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * Function for `get_tree_page_by_term`
     *
     * @param  array $field field.
     *
     * @return void
     */
    public function possible_roots( $field )
    {
        $term = ( isset( $field['term'] ) && $field['term'] ? (object) $field['term'] : null );
        
        if ( $term ) {
            $tree_page = $this->get_tree_page_by_term( $term );
            
            if ( $tree_page ) {
                echo  do_shortcode( '[gt-tree-list family=' . $term->term_id . ']' ) ;
            } else {
                echo  esc_html__( 'To view possibles first generate / set default tree', 'genealogical-tree' ) ;
            }
        
        }
    
    }
    
    /**
     * Function for `get_tree_page_by_term`
     *
     * @param  mixed $term term.
     *
     * @return array
     */
    public function get_tree_page_by_term( $term )
    {
        $tree_page = get_term_meta( $term->term_id, 'tree_page', true );
        if ( !get_post( $tree_page ) ) {
            $tree_page = false;
        }
        return $tree_page;
    }
    
    /**
     * Function for `get_tree_pages_by_term`
     *
     * @param  mixed $term term.
     *
     * @return array
     */
    public function get_tree_pages_by_term( $term )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-tree',
            'posts_per_page' => 1,
            'meta_query'     => array(
            'key'     => 'family',
            'value'   => $term->term_id,
            'compare' => '=',
        ),
        ) );
        $tree_pages = array();
        if ( !empty($query->posts) && is_array( $query->posts ) ) {
            foreach ( $query->posts as $key => $page ) {
                $tree = get_post_meta( $page->ID, 'tree', true );
                if ( $tree['family'] === $term->term_id ) {
                    $tree_pages[] = $page;
                }
            }
        }
        return $tree_pages;
    }

}