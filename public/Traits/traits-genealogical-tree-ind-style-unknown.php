<?php
/**
 * Indi handeler.
 *
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */

namespace Zqe\Traits;

trait Genealogical_Tree_Ind_Style_Unknown {

	/**
	 * It displays the individual's name, image, and other information
	 *
	 * @param  mixed $setting The settings for the tree.
	 * @param  mixed $sex male or female.
	 *
	 * @return void
	 *
	 * @since    2.2.0
	 */
	public function ind_style_unknown( $setting, $sex = '' ) {
		?>
		<div class="ind <?php echo esc_attr( $setting->box->layout ); ?> <?php echo esc_attr( $sex ); ?>">
			<div class="ind-cont">
				<?php if ( isset( $setting->thumb->show ) && 'on' === $setting->thumb->show ) { ?>
					<?php $image_url = GENEALOGICAL_TREE_DIR_URL . 'public/img/ava-' . $sex . '.jpg'; ?>
					<div class="image">
						<div class="image-cont">
							<img src="<?php echo esc_attr( $image_url ); ?>">
						</div>
					</div>
				<?php } ?>
				<div class="info">
					<div class="name">
						<?php esc_html_e( 'Unknown', 'genealogical-tree' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
