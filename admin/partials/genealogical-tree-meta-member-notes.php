<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://wordpress.org/plugins/genealogical-tree
 * @since 1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/admin
 */

$notes = $args['notes'];
$admin = $args['admin'];
?>
<table id="table-member-notes" style="max-width:500px;" class="gta-table">
	<?php foreach ( $notes as $key => $note ) { ?>
	<tr>
		<td colspan="3" style="padding:0px;">
			<div class="repetead-field">
				<?php $admin->clone_delete( $key ); ?>
				<table class="gta-table">
					<tr >
						<td>
							<?php echo '<label style="width:125px;display:block;"> <strong>#' . esc_html( ( $key + 1 ) ) . ' </strong> </label>'; ?>
						</td>
						<td width="100%">
							<?php echo '<textarea style="width:100%;" name="gt[note][' . esc_attr( $key ) . '][note]">' . esc_html( $note['note'] ) . '</textarea>'; ?>
							<br>
							<input type="checkbox" name="gt[note][<?php echo esc_attr( $key ); ?>][isRef]" <?php esc_attr( checked( isset( $note['isRef'] ) ? $note['isRef'] : '', 'on' ) ); ?> >
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<?php } ?>
</table>
