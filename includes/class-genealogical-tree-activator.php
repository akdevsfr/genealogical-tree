<?php
/**
 * Fired during plugin activation
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

namespace Zqe;

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

		$plugin_admin = new \Zqe\Genealogical_Tree_Admin( '', '' );
		$plugin_admin->init_post_type_and_taxonomy();

		flush_rewrite_rules();

		update_option( 'genealogical_tree_active_ver', GENEALOGICAL_TREE_VERSION );

		/* Creating a new role called "GT Member" and assigning it capabilities. */
		remove_role( 'gt_member' );
		add_role(
			'gt_member',
			'GT Member',
			array(
				'upload_files'           => true,
				'edit_posts'             => true,
				'edit_published_posts'   => true,
				'publish_posts'          => true,
				'read'                   => true,
				'level_2'                => true,
				'level_1'                => true,
				'level_0'                => true,
				'delete_posts'           => true,
				'delete_published_posts' => true,
				'gt_member'              => true,
				'manage_categories'      => true,
			)
		);

		/* Creating a new role called "GT Manager". and assigning it capabilities. */
		remove_role( 'gt_manager' );

		add_role(
			'gt_manager',
			'GT Manager',
			array(
				'upload_files'           => true,
				'edit_posts'             => true,
				'edit_published_posts'   => true,
				'edit_others_posts'      => true,
				'publish_posts'          => true,
				'read'                   => true,
				'level_2'                => true,
				'level_1'                => true,
				'level_0'                => true,
				'delete_posts'           => true,
				'delete_published_posts' => true,
				'gt_manager'             => true,
				'manage_categories'      => true,
			)
		);

		/*
		Getting all users with the role of administrator or editor and adding the role of gt_member and
		gt_manager to them.
		*/
		$users = get_users(
			array(
				'role__in' => array(
					'administrator',
					'editor',
				),
			)
		);

		if ( $users ) {
			foreach ( $users as $user ) {
				$user->remove_role( 'gt_member' );
				$user->remove_role( 'gt_manager' );
				$user->add_role( 'gt_member' );
				$user->add_role( 'gt_manager' );
			}
		}
	}
}
