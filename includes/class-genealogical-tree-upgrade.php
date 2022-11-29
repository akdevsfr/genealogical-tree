<?php
/**
 * The upgradeing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

namespace Zqe;

/**
 * The upgradeing functionality of the plugin.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Upgrade {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function admin_notice__info() {
		?>
		<div class="notice notice-warning">
			<p><?php esc_html_e( 'Genealogical Tree plugin got upgrade database stracture, Please click <a id="Upgrade-Genealogical-Tree-Database" class="button" href=""><span class=""></span>Upgrade Genealogical Tree Database</a> this link. Otherwise you may face issues.', 'genealogical-tree' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function ver_upgrade() {
		$this->fix_ver_upgrade_ajax();
		$this->fix218();
		if ( isset( $_POST['_gt_version_fixed_through_notice'] ) ) {
			add_site_option( '_gt_version_fixed_through_notice', time() );
		}
	}
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function fix_ver_upgrade_ajax() {

		$args = array(
			'post_type'      => 'gt-member',
			'posts_per_page' => -1,
			'meta_query'     => array(
				array(
					'key'     => 'fams',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'famc',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$query = new \WP_Query( $args );

		$members = $query->posts;

		foreach ( $members as $key => $value ) {
			$wife = get_post_meta( $value->ID, 'wife', true );
			$husb = get_post_meta( $value->ID, 'husb', true );
			$this->find_or_create_family( $wife, $husb, array( $value->ID ) );
		}

		foreach ( $members as $key => $value ) {
			$spouses = get_post_meta( $value->ID, 'spouses', true );
			foreach ( $spouses as $key => $spouse ) {
				$spouse_id = $spouse['id'];
				if ( $spouse_id ) {
					$wife_or_husb = $this->is_wife_or_husband( $value->ID, $spouse_id );
					$wife         = $wife_or_husb['wife'];
					$husb         = $wife_or_husb['husb'];
					if ( $wife || $husb ) {
						$family_id = $this->find_or_create_family( $wife, $husb, array() );
					}
				}
			}
		}
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function fix218() {
		$query = new \WP_Query(
			array(
				'post_type'      => 'gt-member',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'v218f',
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		$members = $query->posts;

		if ( ! empty( $members ) ) {
			foreach ( $members as $member ) {
				$back = array();

				add_post_meta( $member->ID, 'v218f', 'yes' );

				$names = get_post_meta( $member->ID, 'names' ) ? get_post_meta( $member->ID, 'names' ) : array(
					array(
						'name' => get_post_meta( $member->ID, 'full_name', true ) ? get_post_meta( $member->ID, 'full_name', true ) : '',
						'npfx' => '',
						'givn' => get_post_meta( $member->ID, 'given_name', true ) ? get_post_meta( $member->ID, 'given_name', true ) : '',
						'nick' => '',
						'spfx' => '',
						'surn' => get_post_meta( $member->ID, 'surname', true ) ? get_post_meta( $member->ID, 'surname', true ) : '',
						'nsfx' => '',
					),
				);

				$back['names'] = $names;

				delete_post_meta( $member->ID, 'names' );

				foreach ( $names as $key => $name ) {
					add_post_meta( $member->ID, 'names', $name );
				}

				$fams = get_post_meta( $member->ID, 'fams' ) ? get_post_meta( $member->ID, 'fams' ) : array();

				$back['fams'] = $fams;

				delete_post_meta( $member->ID, 'fams' );

				foreach ( $fams as $key => $value ) {
					if ( ! is_array( $value ) ) {
						add_post_meta( $member->ID, 'fams', array( 'fams' => $value ) );
					}
				}

				$famc = get_post_meta( $member->ID, 'famc' ) ? get_post_meta( $member->ID, 'famc' ) : array();

				$back['famc'] = $famc;

				delete_post_meta( $member->ID, 'famc' );

				foreach ( $famc as $key => $value ) {
					if ( ! is_array( $value ) ) {
						add_post_meta(
							$member->ID,
							'famc',
							array(
								'famc' => $value,
								'pedi' => '',
							)
						);
					}
				}

				$events = get_post_meta( $member->ID, 'event', true ) ? get_post_meta( $member->ID, 'event', true ) : array();

				if ( ! empty( $events ) ) {
					$even = array();
					foreach ( $events as $key => $event ) {
						foreach ( $event as $key => $ev ) {
							$even[ $key ]['tag']  = $ev['type'];
							$even[ $key ]['even'] = '';
							$even[ $key ]['type'] = $ev['type'];
							$even[ $key ]['date'] = $ev['date'];
							$even[ $key ]['plac'] = $ev['place'];
						}
					}

					foreach ( $even as $key => $e ) {
						add_post_meta( $member->ID, 'even', $e );
					}
				}

				$note = get_post_meta( $member->ID, 'note', true ) ? get_post_meta( $member->ID, 'note', true ) : array();

				$back['note'] = $note;

				delete_post_meta( $member->ID, 'note' );

				foreach ( $note as $key => $value ) {
					add_post_meta( $member->ID, 'note', $value );
				}

				$phone = get_post_meta( $member->ID, 'phone', true ) ? get_post_meta( $member->ID, 'phone', true ) : array();

				$back['phone'] = $phone;

				delete_post_meta( $member->ID, 'phone' );

				foreach ( $phone as $key => $value ) {
					if ( ! is_array( $value ) ) {
						add_post_meta( $member->ID, 'phone', $value );
					}
				}

				$email = get_post_meta( $member->ID, 'email', true ) ? get_post_meta( $member->ID, 'email', true ) : array();

				$back['email'] = $email;

				delete_post_meta( $member->ID, 'email' );

				foreach ( $email as $key => $value ) {
					if ( ! is_array( $value ) ) {
						add_post_meta( $member->ID, 'email', $value );
					}
				}

				$address = get_post_meta( $member->ID, 'address', true ) ? get_post_meta( $member->ID, 'address', true ) : array();

				$back['address'] = $address;

				delete_post_meta( $member->ID, 'address' );

				foreach ( $address as $key => $value ) {
					if ( ! is_array( $value ) ) {
						add_post_meta( $member->ID, 'address', $value );
					}
				}
				update_post_meta( $member->ID, 'back_v218f', $back );
			}
		}

		$query = new \WP_Query(
			array(
				'post_type'      => 'gt-family',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'     => 'v218f',
						'compare' => 'NOT EXISTS',
					),
				),
			)
		);

		$families = $query->posts;
		if ( ! empty( $families ) ) {
			foreach ( $families as $key => $family ) {

				$father = get_post_meta( $family->ID, 'father', true );
				$mother = get_post_meta( $family->ID, 'mother', true );
				$chills = get_post_meta( $family->ID, 'chills' );
				$chills = array_unique( $chills );
				$slgs   = get_post_meta( $family->ID, 'slgs' ) ? get_post_meta( $family->ID, 'slgs' ) : array();
				$events = get_post_meta( $family->ID, 'event', true ) ? get_post_meta( $family->ID, 'event', true ) : array();

				add_post_meta( $family->ID, 'v218f', 'yes' );
				add_post_meta( $family->ID, 'husb', $father );
				add_post_meta( $family->ID, 'wife', $mother );

				foreach ( $chills as $key => $chil ) {
					add_post_meta( $family->ID, 'chil', $chil );
				}

				foreach ( $slgs as $key => $slg ) {
					$slg['plac'] = $slg['place'];
					add_post_meta( $family->ID, 'slgs', $slg );
				}

				if ( $events ) {
					$even = array();
					foreach ( $events as $key => $event ) {
						foreach ( $event as $key => $ev ) {
							$even[ $key ]['tag']  = $ev['type'];
							$even[ $key ]['even'] = '';
							$even[ $key ]['type'] = $ev['type'];
							$even[ $key ]['date'] = $ev['date'];
							$even[ $key ]['plac'] = isset( $ev['place'] ) ? $ev['place'] : '';
						}
					}
					foreach ( $even as $key => $e ) {
						add_post_meta( $family->ID, 'even', $e );
					}
				}
			}
		}
	}
}
