<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://wordpress.org/plugins/genealogical-tree
* @since      1.0.0
*
* @package    Genealogical_Tree
* @subpackage Genealogical_Tree/admin/partials
*/
?>

<?php

$meta_query = array(
     array(
        'key'       => 'created_by',
        'value'     => get_current_user_id(),
        'compare'   => '='
     )
);

if( current_user_can('gt_manager') || current_user_can('editor') || current_user_can('administrator') ) {   
	$meta_query = array();
}

$terms = get_terms( array(
	'taxonomy' => 'gt-family-group',
	'hide_empty' => false,
	'meta_query' => $meta_query
) );

if ( is_wp_error( $terms ) ){
	$terms = [];
}


$fonts = json_decode($fonts)->items;

?>

<?php wp_nonce_field( 'update_tree_settings_nonce', '_nonce_update_tree_settings_nonce' ); ?>
<style type="text/css">
	tr.pro > td > label {
		color: red;
	}
	tr.pro > td > label:after {
		/*content: ' * (Pro)'*/
	}
</style>
<table border="0" class="gt-tree">
	<tbody>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('General Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr>
			<td width="160">
				<label for="family"><?php _e('Select Family', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<select id="family" name="tree[family]">
				<?php 
				if($terms){
					foreach ($terms as $key => $term) {
						?>
						<option <?php if($data['family']==$term->term_id) {echo 'selected'; } ?> value="<?php echo $term->term_id; ?>">
							<?php echo $term->term_id; ?> - <?php echo $term->name; ?>
						</option>
						<?php 
					}
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="root"><?php _e('Select Root', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<select id="root" name="tree[root]">
					<option value="0"><?php _e('Select Root', 'genealogical-tree'); ?></option>
				<?php 
				$args = array(
					'numberposts' => -1,
					'post_type'   => 'gt-member',
					'fields' => 'ids, post_title',
					'order_by' => 'ID',
				); 
				$members = get_posts( $args ); 
				if($members){
					foreach ($members as $key => $member) {
						$term_list = wp_get_post_terms( $member->ID, 'gt-family-group', array( 'fields' => 'ids' ) );
						$term_list = implode(',', $term_list);
						?>
						<option data-famly="<?php echo $term_list; ?>" <?php if($data['root']==$member->ID) {echo 'selected'; } ?> value="<?php echo $member->ID; ?>"> 
							<?php echo $member->ID; ?> - <?php echo $member->post_title; ?>
						</option>
						<?php 
					}
				}
				?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="root_highlight"><?php _e('Highlight Root', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" id="root_highlight" name="tree[root_highlight]" <?php if($data['root_highlight']) {echo 'checked'; } ?>>
			</td>
		</tr>
		<tr>
			<td>
				<label for="style"><?php _e('Select Style', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<select id="style" name="tree[style]">
					<option value="1" <?php if($data['style']=='1') {echo 'selected'; } ?>><?php _e('Style 1', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 2', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 2-Alt', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 3', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 3-Alt', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 4', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style 5', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Style Special 1', 'genealogical-tree'); ?></option>
				</select>
			</td>
		</tr>

		<tr>
			<td></td>
			<td colspan="4">
				<small>
					<i>Style 1, Style 4 and Style 5 support separate child for separate spouse</i>
				</small>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="style"><?php _e('Select Layout', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<select name="tree[layout]">
					<option value="vr"><?php _e('Vertical', 'genealogical-tree'); ?></option>
					<option disabled><?php _e('Horizontal', 'genealogical-tree'); ?></option>
			</select>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label for="ajax"><?php _e('Enable Ajax', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>



		<tr class="pro">
			<td>
				<label for="ancestor"><?php _e('Enable Popup', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label for="ancestor"><?php _e('Hide Female Tree', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>


		<tr class="pro">
			<td>
				<label for="hide_spouse"><?php _e('Hide Spouse', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
				<span><i>For Style 2, Style 3, Style 2-Alt, Style 3-Alt</i></span>
			</td>
		</tr>


		<tr class="pro">
			<td>
				<label for="hide_un_spouse"><?php _e('Hide Unknown Spouse', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
				<span><i>For Style 2, Style 3, Style 2-Alt, Style 3-Alt</i></span>
			</td>
		</tr>

		<tr class="pro">
			<td style="vertical-align: top;">
				<label><?php _e('Collapsible Family', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="2">
				<input type="checkbox" disabled> Root
				<input type="checkbox" disabled> Spouse
				
				
			</td>
			<td colspan="2" style="vertical-align: top;">
				<input type="checkbox" disabled> Collaps Onload
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td colspan="5"><span><i>Spouse option not for: Style 3, Style 3-Alt, Style 2, Style 2-Alt</i></span></td>
		</tr>
		
		<tr class="pro">
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_color"><?php _e('Background Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" disabled id="container_background_color" value="<?php echo $data['background']['color']; ?>"><br>
				<i>HEX/RGB/RGBA</i>
			</td>
		</tr>
		<tr class="pro">
			<td valign="top" style="vertical-align:top;">
				<label for="marr_icon"><?php _e('Marriage Icon', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" disabled id="marr_icon" value="<?php echo $data['marr_icon']; ?>" name="tree[marr_icon]"><br>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Visibility Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name"><?php _e('Name', 'genealogical-tree'); ?></label>
			</td>
			<td>
				<input type="radio" id="name" name="tree[name]" value="full" <?php if($data['name']=='full') {echo 'checked'; } ?>> <?php _e('Full', 'genealogical-tree'); ?>
			</td>
			<td colspan="3">
				<input type="radio" id="name" name="tree[name]" value="first" <?php if($data['name']=='first') {echo 'checked'; } ?>> <?php _e('First', 'genealogical-tree'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="birt"><?php _e('Birth Day', 'genealogical-tree'); ?></label>
			</td>
			<td>
				<input type="radio" id="birt" name="tree[birt]" value="full" <?php if($data['birt']=='full') {echo 'checked'; } ?>> <?php _e('Full', 'genealogical-tree'); ?>
			</td>
			<td>
				<input type="radio" id="birt" name="tree[birt]" value="year" <?php if($data['birt']=='year') {echo 'checked'; } ?>> <?php _e('Year', 'genealogical-tree'); ?>
			</td>
			<td colspan="2">
				<input type="radio" id="birt" name="tree[birt]" value="none" <?php if($data['birt']=='none') {echo 'checked'; } ?>> <?php _e('None', 'genealogical-tree'); ?>
			</td>
			
		</tr>
		<tr class="pro">
			<td>
				<label for="birt_hide_alive"><?php _e('Hide Birth Day Who Alive', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>




		<tr>
			<td>
				<label for="deat"><?php _e('Died', 'genealogical-tree'); ?> </label>
			</td>
			<td>
				<input type="radio" id="deat" name="tree[deat]" value="full" <?php if($data['deat']=='full') {echo 'checked'; } ?>> <?php _e('Full', 'genealogical-tree'); ?>
			</td>
			<td>
				<input type="radio" id="deat" name="tree[deat]" value="year" <?php if($data['deat']=='year') {echo 'checked'; } ?>> <?php _e('Year', 'genealogical-tree'); ?>
			</td>
			<td colspan="2">
				<input type="radio" id="deat" name="tree[deat]" value="none" <?php if($data['deat']=='none') {echo 'checked'; } ?>> <?php _e('None', 'genealogical-tree'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<label for="gender"><?php _e('Show Gender', 'genealogical-tree'); ?> </label>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="icon" <?php if($data['gender']=='icon') {echo 'checked'; } ?>> <?php _e('Icon', 'genealogical-tree'); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="full" <?php if($data['gender']=='full') {echo 'checked'; } ?>> <?php _e('Full', 'genealogical-tree'); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="short" <?php if($data['gender']=='short') {echo 'checked'; } ?>> <?php _e('Short', 'genealogical-tree'); ?>
			</td>
			<td>
				<input type="radio" id="gender" name="tree[gender]" value="none" <?php if($data['gender']=='none') {echo 'checked'; } ?>> <?php _e('None', 'genealogical-tree'); ?>
			</td>

		</tr>
		<tr class="pro">
			<td>
				<label for="sibling_order"><?php _e('Sibling Order', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4" >
				<select disabled>
					<option <?php if($data['sibling_order']=='default') {echo 'selected'; } ?> value="default">Default</option>
					<option <?php if($data['sibling_order']=='oldest') {echo 'selected'; } ?> value="oldest">Oldest</option>
					<option <?php if($data['sibling_order']=='youngest') {echo 'selected'; } ?> value="youngest">Youngest</option>
				</select>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="generation"><?php _e('Show Generation', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" disabled>
			</td>
		</tr>


		<tr class="pro">
			<td>
				<label for="generation_number"><?php _e('Number Of Generation', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_number" disabled value="<?php echo $data['generation_number']; ?>">
			</td>
		</tr>
		<tr>
			<td>
			</td>
			<td colspan="4">
				<small><i>"-1" is for unlimited generation.</i></small>
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label for="ancestor"><?php _e('Show Ancestor', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" id="ancestor" disabled <?php if($data['ancestor']) {echo 'checked'; } ?>>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="generation_number"><?php _e('Number Of Ancestor Generation', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_number_ancestor" disabled value="<?php echo $data['generation_number_ancestor']; ?>">
			</td>
		</tr>

		<tr class="pro">
			<td>
				<label for="generation_number"><?php _e('Generation Start From', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="number" id="generation_start_from" disabled value="<?php echo $data['generation_start_from']; ?>">
			</td>
		</tr>

		<tr>
			<td>
			</td>
			<td colspan="4">
				<small><i>If Show Ancestor is Enabled. "-1" is for unlimited generation.</i></small>
			</td>
		</tr>



		<tr>
			<td>
				<label for="treelink"><?php _e('Show Tree Link', 'genealogical-tree'); ?></label>
			</td>
			<td colspan="4">
				<input type="checkbox" id="treelink" name="tree[treelink]" <?php if($data['treelink']) {echo 'checked'; } ?>>
			</td>
		</tr>


		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Container Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr>
			<td valign="top" style="vertical-align:top;">
				<label for="container_background_color"><?php _e('Background Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" id="container_background_color" value="<?php echo $data['container']['background']['color']; ?>" class="gt-color-field" name="tree[container][background][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Border Width', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][width]">
				<?php 
					$container_border_width = $data['container']['border']['width'];

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($container_border_width==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Border Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][style]">
					<?php 
					$container_border_style = $data['container']['border']['style'];
					foreach ($border_style as $key => $value) {
						?>
						<option <?php if($container_border_style==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
						<?php 
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_border_color"><?php _e('Border Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" id="container_border_color" class="gt-color-field" name="tree[container][border][color]" value="<?php echo $data['container']['border']['color']; ?>">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Border Radius', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[container][border][radius]">
				<?php 
					$container_border_radius = $data['container']['border']['radius'];

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($container_border_radius==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>

		<tr class="pro">
			<td valign="top" style="vertical-align:top;">
				<label><?php _e('Background Image', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<textarea style="width: 100%; height: 70px;" disabled><?php echo $data['container']['background']['image']; ?></textarea><br>
				<i> (css linear-gradient) </i>
			</td>
		</tr>

		<tr class="pro">
			<td valign="top" style="vertical-align:top;">
				<label><?php _e('Background Size', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" style="width: 100%;" disabled >
				
			</td>
		</tr>



		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Box Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Layout', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>
					<option value="vr" <?php if($data['box']['layout'] == 'vr') { echo "selected"; } ?>><?php _e('Vertical', 'genealogical-tree'); ?></option>
					<option value="hr" <?php if($data['box']['layout'] == 'hr') { echo "selected"; } ?>><?php _e('Horizontal', 'genealogical-tree'); ?></option>
			</select></td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Width', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text"  name="tree[box][width]" value="<?php echo $data['box']['width']; ?>"> 
				<br><small><i><?php _e('If Layout is Horizontal, Width is recommended to set auto.', 'genealogical-tree'); ?> </i></small>
			</td>
		</tr>
		<tr>
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label for="container_background_color"><?php _e('Background Color', 'genealogical-tree'); ?> </label>
			</td>
			<td><?php _e('Male', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['background']['color']['male']; ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][male]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Female', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['background']['color']['female']; ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][female]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Other', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['background']['color']['other']; ?>" type="text" id="container_background_color" class="gt-color-field" name="tree[box][background][color][other]">
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Border Width', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>

				<?php 
					$box_border_width = $data['box']['border']['width'];

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($box_border_width==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Border Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php 
					$box_border_style = $data['box']['border']['style'];
					foreach ($border_style as $key => $value) {
						?>
						<option <?php if($box_border_style==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
						<?php 
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label for="box_border_color"><?php _e('Border Color', 'genealogical-tree'); ?> </label>
			</td>
			<td><?php _e('Male', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['border']['color']['male']; ?>" type="text" id="box_border_color_male" class="gt-color-field" name="tree[box][border][color][male]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Female', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['border']['color']['female']; ?>" type="text" id="box_border_color_female" class="gt-color-field" name="tree[box][border][color][female]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Other', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['box']['border']['color']['other']; ?>" type="text" id="box_border_color_other" class="gt-color-field" name="tree[box][border][color][other]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Border Radius', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[box][border][radius]">
				<?php 
					$box_border_radius = $data['box']['border']['radius'];;

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($box_border_radius==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>

		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Line Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Line Size', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>

				<?php 
					$line_border_width = $data['line']['border']['width'];
					

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($line_border_width==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Line Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php 
					$line_border_style = $data['line']['border']['style'];
					
					foreach ($border_style as $key => $value) {
						?>
						<option <?php if($line_border_style==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
						<?php 
					} ?>
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<label for="container_background_color"><?php _e('Line Corner Radius', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[line][border][radius]">

				<?php 
					$line_border_radius = $data['line']['border']['radius'];
					

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($line_border_radius==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>

		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Line Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input disabled type="text">
			</td>
		</tr>
		<tr class="pro">
			<td colspan="5" class="higlighted">
				<h4><?php _e('Image Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Show Image', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="checkbox" name="tree[thumb][show]" <?php if($data['thumb']['show']) {echo 'checked'; } ?>>
			</td>
		</tr>
			<tr >
			<td>
				<label for="container_background_color"><?php _e('Width', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input type="text" name="tree[thumb][width]" value="<?php echo $data['thumb']['width']; ?>"> 
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Border Width', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>

				<?php 
					$thumb_border_width = $data['thumb']['border']['width'];

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($thumb_border_width==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>
		<tr class="pro">
			<td>
				<label for="container_background_color"><?php _e('Border Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select disabled>
					<?php 
					$thumb_border_style = $data['thumb']['border']['style'];
					foreach ($border_style as $key => $value) {
						?>
						<option <?php if($thumb_border_style==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo ucfirst($value); ?></option>
						<?php 
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<td rowspan="3" valign="top" style="vertical-align:top;">
				<label for="box_border_color"><?php _e('Border Color', 'genealogical-tree'); ?> </label>
			</td>
			<td><?php _e('Male', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['thumb']['border']['color']['male']; ?>" type="text" id="box_border_color_male" class="gt-color-field" name="tree[thumb][border][color][male]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Female', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['thumb']['border']['color']['female']; ?>" type="text" id="box_border_color_female" class="gt-color-field" name="tree[thumb][border][color][female]">
			</td>
		</tr>
		<tr>
			<td><?php _e('Other', 'genealogical-tree'); ?></td>
			<td colspan="3">
				<input value="<?php echo $data['thumb']['border']['color']['other']; ?>" type="text" id="box_border_color_other" class="gt-color-field" name="tree[thumb][border][color][other]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="container_background_color"><?php _e('Border Radius', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[thumb][border][radius]">

				<?php 
					$thumb_border_radius = $data['thumb']['border']['radius'];

				for ($i=0; $i < 20; $i++) { 
						?>
						<option <?php if($thumb_border_radius==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
						<?php
					} ?>
			</select></td>
		</tr>





		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Name Text Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr class="pro"> 
			<td>
				<label for="name_text_font_family"><?php _e('Font Family', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<?php 

					$font_weight = $data['name_text']['font_weight'];
					$font_style = $data['name_text']['font_style'];
				?>
				<select id="name_text_font_family" name="tree[name_text][font_family]" data-font_weight="<?php echo $font_weight; ?>" data-font_style="<?php echo $font_style; ?>">
					<option data-weight='["regular"]' data-style='["regular"]' value="none"><?php _e('Default', 'genealogical-tree'); ?></option>
					<?php
					$font_family = $data['name_text']['font_family'];

					foreach ($fonts as $key => $value) {
						?>
						<option disabled><?php echo $value->family; ?></option>
						<?php  } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_size"><?php _e('Font Size', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[name_text][font_size]">
				<?php 
				$name_text_font_size = $data['name_text']['font_size'];
				for ($i=5; $i < 25; $i++) { ?>
					<option <?php if($name_text_font_size==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_weight"><?php _e('Font Weight', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select id="name_text_font_weight" name="tree[name_text][font_weight]">
					
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_font_style"><?php _e('Font Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select id="name_text_font_style" name="tree[name_text][font_style]">
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_color"><?php _e('Font Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<input value="<?php echo $data['name_text']['color']; ?>" type="text" id="name_text_color" class="gt-color-field" name="tree[name_text][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="name_text_align"><?php _e('Text Align', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<?php 
				$name_text_align = $data['name_text']['align'];
				?>
				<select name="tree[name_text][align]">
					<option <?php if($name_text_align=='left'){ echo "selected";} ?> value="left">Left</option>
					<option <?php if($name_text_align=='center'){ echo "selected";} ?> value="center">Center</option>
					<option <?php if($name_text_align=='right'){ echo "selected";} ?> value="right">Right</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="higlighted">
				<h4><?php _e('Other Text Setting', 'genealogical-tree'); ?></h4>
			</td>
		</tr>
		<tr class="pro">
			<td>
				<label for="other_text_font_family"><?php _e('Font Family', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<?php 

					$font_weight = $data['other_text']['font_weight'];
					$font_style = $data['other_text']['font_style'];

				?>
				<select id="other_text_font_family" name="tree[other_text][font_family]" data-font_weight="<?php echo $font_weight; ?>" data-font_style="<?php echo $font_style; ?>">
					<option data-weight='["regular"]' data-style='["regular"]' value="none"><?php _e('Default', 'genealogical-tree'); ?></option>
					
					<?php
					$font_family = $data['other_text']['font_family'];


					foreach ($fonts as $key => $value) {


						?>
						<option disabled><?php echo $value->family; ?></option>
						<?php  } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_size"><?php _e('Font Size', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select name="tree[other_text][font_size]">
				<?php 
				$other_text_font_size = $data['other_text']['font_size'];
				for ($i=5; $i < 25; $i++) { ?>
					<option <?php if($other_text_font_size==$i.'px') { echo 'selected'; } ?> value="<?php echo $i; ?>px"><?php echo $i; ?>px</option>
				<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_weight"><?php _e('Font Weight', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select id="other_text_font_weight" name="tree[other_text][font_weight]">
					
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_font_style"><?php _e('Font Style', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<select id="other_text_font_style" name="tree[other_text][font_style]">
					
				</select>
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_color"><?php _e('Font Color', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<?php 
				$other_text_color = $data['other_text']['color'];
				?>
				<input value="<?php echo $other_text_color; ?>" type="text" id="other_text_color" class="gt-color-field" name="tree[other_text][color]">
			</td>
		</tr>
		<tr>
			<td>
				<label for="other_text_align"><?php _e('Text Align', 'genealogical-tree'); ?> </label>
			</td>
			<td colspan="4">
				<?php 
				$other_text_align = $data['other_text']['align'];
				?>
				<select name="tree[other_text][align]">
					<option <?php if($other_text_align=='left'){ echo "selected";} ?> value="left"><?php _e('Left', 'genealogical-tree'); ?></option>
					<option <?php if($other_text_align=='center'){ echo "selected";} ?> value="center"><?php _e('Center', 'genealogical-tree'); ?></option>
					<option <?php if($other_text_align=='right'){ echo "selected";} ?> value="right"><?php _e('Right', 'genealogical-tree'); ?></option>
				</select>
			</td>
		</tr>
	</tbody>
</table>