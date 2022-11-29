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

$phone   = $args['phone'];
$email   = $args['email'];
$address = $args['address'];
$admin   = $args['admin'];
?>
<div class="gta-container">
	<table class="gta-table">
		<tr>
			<td colspan="2" style="padding:0px;">
				<h4>
					<?php esc_html_e( 'Contact Information', 'genealogical-tree' ); ?>
				</h4>
			</td>
		</tr>
		<tr>
			<td>
				<label style="width:171px;" for="phone">
					<?php esc_html_e( 'Phone', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php foreach ( $phone as $key => $phon ) { ?>
					<div class="repetead-field"> 
					<?php $admin->clone_delete( $key ); ?>
						<input type="text" id="phone" name="gt[phone][<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $phon ); ?>">
					</div>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="email">
					<?php esc_html_e( 'Email', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php foreach ( $email as $key => $emai ) { ?>
				<div class="repetead-field"> 
					<?php $admin->clone_delete( $key ); ?>
					<input type="text" id="email" name="gt[email][<?php echo esc_attr( $key ); ?>]"  value="<?php echo esc_attr( $emai ); ?>">
				</div>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="address">
					<?php esc_html_e( 'Address', 'genealogical-tree' ); ?>
				</label>
			</td>
			<td>
				<?php foreach ( $address as $key => $addr ) { ?>
				<div class="repetead-field"> 
					<?php $admin->clone_delete( $key ); ?>
					<input type="text" id="address" name="gt[address][<?php echo esc_attr( $key ); ?>]"  value="<?php echo esc_attr( $addr ); ?>">
				</div>
				<?php } ?>
			</td>
		</tr>
	</table>
</div>
