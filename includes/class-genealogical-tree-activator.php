<?php
namespace Genealogical_Tree\Includes;

/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        
        $plugin_admin = new \Genealogical_Tree\Genealogical_Tree_Admin\Genealogical_Tree_Admin( '', '' );
        
       // $plugin_admin->fix_ver_upgrade_ajax();

        $plugin_admin->init_post_type_and_taxonomy();

        flush_rewrite_rules();

        update_option( 'genealogical_tree_active_ver', GENEALOGICAL_TREE_VERSION );

        remove_role( 'gt_member' );

        add_role( 'gt_member',  'GT Member',  array(
                'upload_files' => true,
                'edit_posts' => true,
                'edit_published_posts' => true,
                'publish_posts' => true,
                'read' => true,
                'level_2' => true,
                'level_1' => true,
                'level_0' => true,
                'delete_posts' => true,
                'delete_published_posts' => true,
                'gt_member' => true,
                'manage_categories' => true,
            )
        );

        remove_role( 'gt_manager' );

        add_role( 'gt_manager',  'GT Manager',  array(
                'upload_files' => true,
                'edit_posts' => true,
                'edit_published_posts' => true,
                'edit_others_posts' => true,
                'publish_posts' => true,
                'read' => true,
                'level_2' => true,
                'level_1' => true,
                'level_0' => true,
                'delete_posts' => true,
                'delete_published_posts' => true,
                'gt_manager' => true,
                'manage_categories' => true,
            )
        );
        
        $admins = get_users( array( 'role' => 'administrator' ) );

        if ( $admins ) {
            foreach ( $admins as $user ) {
                $user->remove_cap( 'gt_member');
                $user->remove_cap( 'gt_manager');
                $user->add_cap( 'gt_member');
                $user->add_cap( 'gt_manager');
            }
        }
        
    }
}