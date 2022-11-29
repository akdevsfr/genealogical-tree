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

namespace Zqe\Traits;

trait Genealogical_Tree_Style_Alter {

	/**
	 * Get display members for shortcode
	 *
	 * @param  mixed $tree tree.
	 * @param  mixed $setting setting.
	 * @param  mixed $gen gen.
	 *
	 * @return void
	 *
	 * @since    1.0.0
	 */
	public function tree_style_alter( $tree, $setting, $gen = 0 ) {

		$gen--;

		if ( 0 === $gen ) {
			$gen--;
		}

		if ( $gen > 2 ) {
			if ( gt_fs()->is_not_paying() && ! gt_fs()->is_trial() ) {
				return;
			}
		}

		if ( '-1' !== (string) $setting->generation_number_ancestor ) {
			if ( $gen > $setting->generation_number_ancestor ) {
				return;
			}
		}

		$famc = get_post_meta( $tree, 'famc' ) ? get_post_meta( $tree, 'famc' ) : array();

		foreach ( $famc as $key => $fam ) {
			$famc[ $fam['famc'] ] = $fam;
			unset( $famc[ $key ] );
		}

		$father   = ! empty( $famc ) ? get_post_meta( current( $famc )['famc'], 'husb', true ) : null;
		$mother   = ! empty( $famc ) ? get_post_meta( current( $famc )['famc'], 'wife', true ) : null;
		$families = array( 1 );

		if ( $father ) {
			?>
			<li class="parent father">
				<ul class="parents">
				<?php $this->tree_style_alter( $father, $setting, $gen, $families ); ?>
				</ul>
				<?php $this->ind_style( $father, $setting, $gen, $families, 'alter' ); ?>
			</li>
			<?php
		}
		if ( $mother ) {
			?>
			<li class="parent mother">
				<ul class="parents">
				<?php $this->tree_style_alter( $mother, $setting, $gen, $families ); ?>
				</ul>
				<?php $this->ind_style( $mother, $setting, $gen, $families, 'alter' ); ?>
			</li>
			<?php
		}
	}

}
