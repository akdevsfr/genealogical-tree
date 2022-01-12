<?php

namespace Genealogical_Tree\Genealogical_Tree_Admin\Inc;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin/inc
 */
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
class Genealogical_Tree_Admin_Family
{
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
     * family group validation notice handler.
     *
     * @since    1.0.0
     */
    public function family_group_validation_notice_handler()
    {
        $errors = get_option( 'family_group_validation' );
        if ( $errors ) {
            echo  '<div class="error"><p>' . $errors . '</p></div>' ;
        }
        update_option( 'family_group_validation', false );
    }
    
    /**
     * Edit term page
     *
     * @since    1.0.0
     */
    public function family_group_edit_meta_field( $term )
    {
        $tree_page = get_term_meta( $term->term_id, 'tree_page', true );
        if ( !get_post( $tree_page ) ) {
            $tree_page = false;
        }
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
        foreach ( $query->posts as $key => $page ) {
            $tree = get_post_meta( $page->ID, 'tree', true );
            if ( $tree['family'] == $term->term_id ) {
                $tree_pages[] = $page;
            }
        }
        ?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="tree_page"><?php 
        _e( 'Default Tree', 'genealogical-tree' );
        ?></label>
			</th>
			<td>
			<?php 
        
        if ( !$tree_pages ) {
            ?>
				<button data-id="<?php 
            echo  $term->term_id ;
            ?>" class="button generate_default_tree"><?php 
            _e( 'Generate Default Tree', 'genealogical-tree' );
            ?></button> 
			<?php 
        }
        
        ?>
			<?php 
        
        if ( $tree_pages ) {
            ?>
			<select name="tree_page">
				<option value>Select Default Tree</option>
				<?php 
            foreach ( $tree_pages as $key => $page ) {
                $tree = get_post_meta( $page->ID, 'tree', true );
                
                if ( isset( $tree['family'] ) && $tree['family'] == $term->term_id ) {
                    ?>
					<option value="<?php 
                    echo  $page->ID ;
                    ?>" <?php 
                    if ( $tree_page == $page->ID ) {
                        echo  "selected" ;
                    }
                    ?>>
						<?php 
                    echo  $page->post_title ;
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
            ?>"><?php 
            _e( 'View Tree.', 'genealogical-tree' );
            ?></a> 
			<?php 
        }
        
        ?>
		</td>
		</tr>
		<tr class="form-field">
		<th scope="row" valign="top"><label for="possible_roots"><?php 
        _e( 'Possible Roots', 'genealogical-tree' );
        ?></label></th>
			<td>
				<?php 
        
        if ( $tree_page ) {
            echo  do_shortcode( '[gt-tree-list family=' . $term->term_id . ']' ) ;
        } else {
            __( 'To view possibles first generate default tree', 'genealogical-tree' );
        }
        
        ?>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top">
			<label for="members_page"><?php 
        _e( 'Members Page', 'genealogical-tree' );
        ?></label>
			</th>
			<td>
				<a class="button" target="_blank" href="<?php 
        echo  esc_attr( get_term_link( $term, 'gt-family-group' ) ) ;
        ?>">View Members Page.</a></p>
			</td>
		</tr>
		<?php 
        ?>
		<?php 
    }
    
    /**
     * Save extra taxonomy fields callback function.
     *
     * @since    1.0.0
     */
    public function update_family_group( $term_id )
    {
        
        if ( isset( $_POST['tree_page'] ) ) {
            $tree_page = sanitize_text_field( $_POST['tree_page'] );
            update_term_meta( $term_id, 'tree_page', $tree_page );
        }
    
    }
    
    /**
     * Generate page for family
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
            update_term_meta( $family_group_id, 'tree_page', $tree_page );
            update_term_meta( $family_group_id, 'created_by', get_current_user_id() );
            update_post_meta( $tree_page, 'tree', array(
                'family' => $family_group_id,
            ) );
        }
        
        return $tree_page;
    }
    
    public function generate_default_tree()
    {
        $family_group_id = sanitize_text_field( $_POST['family_id'] );
        if ( $family_group_id ) {
            return $this->generate_default_page( $family_group_id );
        }
    }
    
    public function delete_family_group( $term_id )
    {
    }
    
    /**
     * Create family group free
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
                echo  '<a href="' . gt_fs()->get_upgrade_url() . '">' . __( 'Upgrade Now!', 'genealogical-tree' ) . '</a> to create more family group' ;
                echo  '</section>' ;
                die;
            }
        
        }
        
        $this->generate_default_page( $term_id );
    }
    
    /**
     * Set the submenu as active/current while anywhere in your Custom Post Type (member)
     *
     * @since    1.0.0
     */
    public function set_family_group_current_menu( $parent_file )
    {
        global  $submenu_file, $current_screen, $pagenow ;
        if ( $current_screen->post_type == 'gt-member' ) {
            
            if ( $pagenow == 'edit-tags.php' || $pagenow == 'term.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy=gt-family-group&post_type=' . $current_screen->post_type;
                $parent_file = 'genealogical-tree';
            }
        
        }
        return $parent_file;
    }

}