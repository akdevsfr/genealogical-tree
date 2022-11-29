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

trait Genealogical_Tree_Style_1 {

	/**
	 * Function for `display_tree_style1`.
	 *
	 * @param  mixed $tree tree.
	 * @param  mixed $setting setting.
	 * @param  mixed $gen gen.
	 *
	 * @return mixed
	 *
	 * @since    1.0.0
	 */
	public function display_tree_style1( $tree, $setting, $gen ) {
		ob_start();
		?>
		<div class="gt-style-1">
			<?php if ( isset( $setting->ancestor ) && 'on' === $setting->ancestor ) { ?>
				<ul class="has-ancestor">
					<li class="parent alter-tree">
						<ul class="parents">
							<?php $this->tree_style_alter( $tree, $setting, ( $gen + 1 ) ); ?>
						</ul>
					</li>
					<li class="child root">
					<?php } ?>
						<ul class="childs">
							<?php $this->tree_style1( $tree, $setting, $gen ); ?>
						</ul>
					<?php if ( isset( $setting->ancestor ) && 'on' === $setting->ancestor ) { ?>
					</li>
				</ul>
			<?php } ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Function for `tree_style1`.
	 *
	 * @param  mixed $tree tree.
	 * @param  mixed $setting setting.
	 * @param  mixed $gen gen.
	 * @param  mixed $checker checker.
	 *
	 * @return mixed
	 *
	 * @since    1.0.0
	 */
	public function tree_style1( $tree, $setting, $gen = 0, $checker = array() ) {

		$gen++;

		if ( $gen > 5 ) {
			if ( gt_fs()->is_not_paying() && ! gt_fs()->is_trial() ) {
				return;
			}
		}

		if ( '-1' !== (string) $setting->generation_number ) {
			if ( $gen > $setting->generation_number ) {
				return;
			}
		}

		$families = $this->get_families_by_root( $tree, $setting );

		if ( $setting->collapsible_family_root && $setting->collapsible_family_onload && count( $families ) > 0 ) {
			$collapsible_family_root      = 'display:none;';
			$collapsible_family_root_sign = '+';
		} else {
			$collapsible_family_root      = '';
			$collapsible_family_root_sign = '-';
		}
		if ( $setting->collapsible_family_spouse && $setting->collapsible_family_onload && count( $families ) > 0 ) {
			$collapsible_family_spouse      = 'display:none;';
			$collapsible_family_spouse_sign = '+';
		} else {
			$collapsible_family_spouse      = '';
			$collapsible_family_spouse_sign = '-';
		}

		$sex = get_post_meta( $tree, 'sex', true );

		$sex_alt = '';

		if ( 'M' === $sex ) {
			$sex_alt = 'F';
		}
		if ( 'F' === $sex ) {
			$sex_alt = 'M';
		}
		?>
		<li class="child root">
			<?php $this->ind_style( $tree, $setting, $gen, $families, 'root', $collapsible_family_root_sign ); ?>
			<?php if ( $families ) { ?>
				<ul class="families" style="<?php echo esc_attr( $collapsible_family_root ); ?>">
					<?php foreach ( $families as $key => $family ) { ?>
						<?php if ( 'M' === $sex || ( 'F' === $sex && 'on' !== $setting->female_tree ) ) { ?>
							<?php if ( $family->spouse ) { ?>
								<?php array_push( $checker, $family->spouse ); ?>
								<li class="family spouse">
									<?php $this->ind_style( $family->spouse, $setting, null, $family->chil, 'spouse', $collapsible_family_spouse_sign ); ?>
									<?php if ( $family->chil ) { ?>
										<?php $this->tree_style1__childs( $family->chil, $setting, $gen, $checker, $collapsible_family_spouse ); ?>
									<?php } ?>
								</li>
							<?php } else { ?>
								<?php if ( $family->chil ) { ?>
									<li class="family">
										<?php $this->ind_style_unknown( $setting, $sex_alt ); ?>
										<?php $this->tree_style1__childs( $family->chil, $setting, $gen, $checker ); ?>
									</li>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</ul>
			<?php } ?>
		</li>
		<?php
	}

	/**
	 * Function for `tree_style1__childs`.
	 *
	 * @param  mixed $chills chills.
	 * @param  mixed $setting setting.
	 * @param  mixed $gen gen.
	 * @param  mixed $checker checker.
	 * @param  mixed $collapsible collapsible.
	 *
	 * @return void
	 *
	 * @since    1.0.0
	 */
	public function tree_style1__childs( $chills, $setting, $gen = 0, $checker = array(), $collapsible = null ) {
		?>
		<ul class="childs" style="<?php echo esc_attr( $collapsible ); ?>">
			<?php foreach ( $chills as $key => $chill ) { ?>
				<?php if ( ! in_array( $chill, $checker, true ) ) { ?>
					<?php array_push( $checker, $chill ); ?>
					<?php $this->tree_style1( $chill, $setting, $gen, $checker ); ?>
				<?php } ?>
			<?php } ?>
		</ul>
		<?php
	}

}
