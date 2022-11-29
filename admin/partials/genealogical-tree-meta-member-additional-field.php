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

$additional_fields = $args['additional_fields'];
$admin             = $args['admin'];
?>
<table class="gta-table" style="max-width: 500px;">
	<tr>
		<td>
			<strong style="width:150px; display: block;">
				<?php esc_html_e( 'Field Name', 'genealogical-tree' ); ?>
			</strong>
		</td>
		<td width="100%">
			<strong>
				<?php esc_html_e( 'Field Value', 'genealogical-tree' ); ?>
			</strong>
		</td>
	</tr>
	<?php
	$z = 0;
	foreach ( $additional_fields as $key => $additional_field ) {
		?>
	<tr>
		<td colspan="3" style="padding:0px;">
			<div class="repetead-field">
				<?php $admin->clone_delete( $key ); ?>
				<table class="gta-table">
					<tr>
						<td>
							<div style="width:148px; display: block;">
								<input type="text" name="additional_fields[<?php echo esc_attr( $z ); ?>][name]" value="<?php echo esc_attr( $additional_fields[ $z ]['name'] ); ?>">
							</div>
						</td>
						<td width="100%">
							<input type="text" name="additional_fields[<?php echo esc_attr( $z ); ?>][value]" value="<?php echo esc_attr( $additional_fields[ $z ]['value'] ); ?>">
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
		<?php
		$z++;
	}
	?>
</table>
