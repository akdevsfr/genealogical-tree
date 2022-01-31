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
		
<?php if (gt_fs()->is_not_paying() && !gt_fs()->is_trial()) { ?>
<style type="text/css">
	.gta-table .repetead-field {
		padding-right: 0px; 
	}
	.gta-table .repetead-field .clone,
	.gta-table .repetead-field .delete {
		display: none;
	}
</style>
<?php } ?>
<div class="gta-container">
	<?php wp_nonce_field( 'update_member_info_nonce', '_nonce_update_member_info_nonce' ); ?>
	<table class="gta-table">
		<tr>
			<td>
				<h4 style="display: inline;background: #0085ba;padding: 0px 0px;color: #fff;"><?php _e('ID', 'genealogical-tree'); ?>: <?php echo get_the_ID();; ?></h4>
			</td>
		</tr>
	</table>	
	<div class="gta-row">
		<div class="gta-col-3 coll-one">
			<table class="gta-table">
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Name', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label style="width:169px;" for="full-name"><?php _e('Full Name', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="full-name" type="text" name="gt[full_name]" value="<?php echo $full_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="given-name"><?php _e('Given Name', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="given-name" type="text" name="gt[given_name]" value="<?php echo $given_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="sur-name"><?php _e('Surname', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="surname" type="text" name="gt[surname]" value="<?php echo $surname; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Gender', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="birth-sex"><?php _e('Gender', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<select id="birth-sex" name="gt[sex]">
							<option value=""><?php _e('Select Gender', 'genealogical-tree'); ?></option>
							<option value="M" <?php echo ($sex==='M') ? 'selected' : '' ; ?>><?php _e('Male', 'genealogical-tree'); ?></option>
							<option value="F" <?php echo ($sex==='F') ? 'selected' : '' ; ?>><?php _e('Female', 'genealogical-tree'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4>
							<?php _e('Parents', 'genealogical-tree'); ?>
						</h4>
						<?php 
						$famc = get_post_meta( $post->ID, 'famc' ) ? get_post_meta( $post->ID, 'famc' ) : array( );
						$famc = array_unique( $famc );
						if( empty( $famc ) ) {
							$famc = array( '' );
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<?php 
						$z = 0; 
						foreach ($famc as $key => $fam) {
							$father = get_post_meta($fam, 'father', true);
							$mother = get_post_meta($fam, 'mother', true);
						?>
						<div class="repetead-field">
							<?php if ($z===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($z > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table class="gta-table">
								<tr>
									<td width="1%">
										<label style="width: 70px;"><?php _e('REF #', 'genealogical-tree'); ?> 
											<a class="no-copy" href="<?php echo get_edit_post_link($fam); ?>"> <?php echo $fam; ?> </a> 
										</label>
									</td>
									<td width="100%" style="padding:0px;">
										<input type="hidden"  name="gt[parents][<?php echo $z; ?>][ref]" value="<?php echo $fam; ?>">
										<table class="gta-table">
											<tr>
												<td width="1%">
													<label style="width: 95px;" for="mother"><?php _e('Mother', 'genealogical-tree'); ?></label>
												</td>
												<td width="100%">
													<select class="select2" id="mother" name="gt[parents][<?php echo $z; ?>][mother]">
													<?php 
													$this->select_member_html($females, $males, $unknowns, $z, __('Mother', 'genealogical-tree'), $mother); 
													?>
													</select>
													<a class="no-copy"  href="<?php echo get_edit_post_link($mother); ?>"> Edit <?php //echo $mother; ?></a> 
												</td>

											</tr>
											<tr>
												<td>
													<label for="father"><?php _e('Father', 'genealogical-tree'); ?></label>
												</td>
												<td>
													<select class="select2" id="mother" name="gt[parents][<?php echo $z; ?>][father]">
													<?php 
													$this->select_member_html($females, $males, $unknowns, $z, __('Father', 'genealogical-tree'), $father); 
													?>
													</select>
													<a class="no-copy"  href="<?php echo get_edit_post_link($father); ?>"> Edit <?php // echo $father; ?></a> 
												</td>

											</tr>
<!-- 											<tr>

												<td>
													<label style="width: 95px;" for="mother"><?php _e('Family Relation', 'genealogical-tree'); ?></label>
													
												</td>
												<td>
													<div>
														<select> 
															<option>Select Relation</option>
															<option value="adopted">Adopted</option>
															<option value="birth">Birth</option>
															<option value="foster">Foster</option>
															<option value="sealing">Sealing</option>
															<option value="other">Other</option>
														</select>
													</div>

													
												</td>
											</tr> -->
											<tr class="no-copy">
												<td><?php _e('Siblings', 'genealogical-tree'); ?> </td>
												<td>
													<?php 
													$chills = get_post_meta($fam, 'chills') ? get_post_meta($fam, 'chills') : array();
													foreach ($chills as $key => $chi) {
														if ( $chi != get_the_ID() ) {

															$gender = '<span class="gt-gender-emoji">⚥</span>';

									                        if (get_post_meta($chi, 'sex', true) === 'M') {
									                            $gender = '<span class="gt-gender-emoji">♂️</span>';
									                        }

									                        if (get_post_meta($chi, 'sex', true) === 'F') {
									                            $gender = '<span class="gt-gender-emoji">♀️</span>';
									                        }

															echo ' <a  href="'.get_edit_post_link($chi).'">'.$gender.' '.get_post_meta($chi, 'full_name', true).'</a>';
														}
													}
													?>
												</td>
											</tr>

										</table>
									</td>
								</tr>
							</table>
						</div>
						<?php 
						$z++;
						}
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Spouses', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<?php 
						
						$fams = get_post_meta( $post->ID, 'fams' ) ? get_post_meta( $post->ID, 'fams' ) : array( );
						
						$fams = array_unique( $fams );
						
						if( empty( $fams ) ) {
							$fams = array( '' );
						}

						$y = 0; 

						foreach ($fams as $key => $fam) {

							$father = get_post_meta($fam, 'father', true);
							$mother = get_post_meta($fam, 'mother', true);

							$spouse = ( $father == $post->ID ) ? $mother : $father;

							$spouse_date = '';
							$spouse_place = '';

							?>
						<div class="repetead-field"> 
							<?php if ($y===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($y > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table class="gta-table">
								<tr>
									<td width="1%">
										<label style="width: 70px;"> <?php _e('REF #', 'genealogical-tree'); ?> 
											<a class="no-copy" href="<?php echo get_edit_post_link($fam); ?>"> <?php echo $fam; ?> </a>  
										</label>
									</td>
									<td width="100%" style="padding:0px;">
										<input type="hidden" readonly  name="gt[spouses][<?php echo $y; ?>][ref]" value="<?php echo $fam; ?>">
										<table class="gta-table">
											<tr>
												<td width="1%">
													<label style="width: 95px;" for="spouse"><?php _e('Spouse', 'genealogical-tree'); ?></label>
												</td>
												<td width="100%">
													<select class="select2" id="mother" name="gt[spouses][<?php echo $y; ?>][id]">
													<?php 
														$this->select_member_html($females, $males, $unknowns, $y, __('Spouse', 'genealogical-tree'), $spouse); 
													?>
													</select>
													<?php if( $spouse ) { ?>
													<a class="no-copy"  href="<?php echo get_edit_post_link($spouse); ?>"> Edit <?php //echo $spouse; ?></a> 
												<?php } ?>
												</td>
											</tr>
											<tr>
												<td style="width: 100px;"><label for="father"><?php _e('Marriage Date', 'genealogical-tree'); ?></label></td>
												<td colspan="3"><input type="text" name="gt[spouses][<?php echo $y; ?>][date]" value="<?php echo $spouse_date; ?>"> </td>
											</tr>
											<tr>
												<td style="width: 100px;"><label for="father"><?php _e('Marriage Place', 'genealogical-tree'); ?></label></td>
												<td colspan="3"><input type="text" name="gt[spouses][<?php echo $y; ?>][place]" value="<?php echo $spouse_place; ?>"> </td>
											</tr>
											<tr class="no-copy">
												<td><?php _e('Chills', 'genealogical-tree'); ?> </td>
												<td colspan="3">
													<?php 
													$chills = get_post_meta($fam, 'chills') ? get_post_meta($fam, 'chills') : array();
													foreach ($chills as $key => $chi) {
														?>
														<input type="hidden" readonly  name="gt[spouses][<?php echo $y; ?>][chills][]" value="<?php echo $chi; ?>">
														<?php 
														$gender = '<span class="gt-gender-emoji">⚥</span>';
								                        if (get_post_meta($chi, 'sex', true) === 'M') {
								                            $gender = '<span class="gt-gender-emoji">♂️</span>';
								                        }
								                        if (get_post_meta($chi, 'sex', true) === 'F') {
								                            $gender = '<span class="gt-gender-emoji">♀️</span>';
								                        }
														echo ' <a  href="'.get_edit_post_link($chi).'"> '.$gender.' '.get_post_meta($chi, 'full_name', true).'</a>';
													}
													?>
												</td>
											</tr>
										</table>
										<?php 
										$y++;
										?>
									</td>
								</tr>
							</table>
						</div>
						<?php 
						}
						?>
					</td>
				</tr>
			</table>
		</div>
		<div class="gta-col-3 coll-two">
			<table class="gta-table">
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Birth', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">			
						<?php 
						$bc = 0; 
						if(!isset($event['birt'])) {
							$event['birt'] = array(array(
								'date' => '',
								'place' => '',
								'ref' => '',
								'type' => 'birt'
							));
						}
						foreach ($event['birt'] as $key => $value) {
							?>
							<div class="repetead-field rep-birt-deat-event" style="margin-left: -3px; margin-right: -3px;">
								<?php if ($bc===0){ ?>
									<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
								<?php } if ($bc > 0){ ?>
									<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
								<?php }  ?>
								<table class="gta-table">
									<tr>
										<td rowspan="2" width="1%">
											<label style="width: 70px;"><?php _e('REF', 'genealogical-tree'); ?>  #<span data-ref-c="<?php echo $bc; ?>"><?php echo $bc+1; ?></span> </label>
										</td>
										<td  width="1%"><label style="width: 96px;" for="birt-date"><?php _e('Date', 'genealogical-tree'); ?></label></td>
										<td  width="100%"><input id="birt-date" type="text" name="gt[event][birt][<?php echo $key; ?>][date]"  value="<?php echo $value['date']; ?>"></td>
									</tr>
									<tr>
										<td><label for="birt-place"><?php _e('Place', 'genealogical-tree'); ?></label></td>
										<td>
											<input id="birt-place" type="text" name="gt[event][birt][<?php echo $key; ?>][place]" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>">
											<input id="birt-ref" type="hidden" name="gt[event][birt][<?php echo $key; ?>][ref]" >
											<input id="birt-ref" type="hidden" name="gt[event][birt][<?php echo $key; ?>][type]" value="birt">
										</td>
									</tr>
								</table>
							</div>
							<?php 
							$bc++; 
						} 
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Death', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">				
					<?php 
					$dc = 0; 
					if(!isset($event['deat'])) {
						$event['deat'] = array(array(
							'date' => '',
							'place' => '',
							'ref' => '',
							'type' => 'deat'
						));
					}
					foreach ($event['deat'] as $key => $value) { 
						?>
						<div class="repetead-field rep-deat-deat-event" style="margin-left: -3px; margin-right: -3px;">
							<?php if ($dc===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($dc > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table class="gta-table">
								<tr>
									<td rowspan="2" width="1%">
										<label style="width: 70px;"><?php _e('REF', 'genealogical-tree'); ?>  #<span data-ref-c="<?php echo $dc; ?>"><?php echo $dc+1; ?></span> </label>
									</td>
									<td width="1%"><label style="width: 96px;" for="deat-date"><?php _e('Date', 'genealogical-tree'); ?></label></td>
									<td width="100%"><input id="deat-date" type="text" name="gt[event][deat][<?php echo $key; ?>][date]"  value="<?php echo $value['date']; ?>"></td>
								</tr>
								<tr>
									<td><label for="deat-place"><?php _e('Place', 'genealogical-tree'); ?></label></td>
									<td>
										<input id="deat-place" type="text" name="gt[event][deat][<?php echo $key; ?>][place]" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>">
										<input id="deat-ref" type="hidden" name="gt[event][deat][<?php echo $key; ?>][ref]" >
										<input id="deat-ref" type="hidden" name="gt[event][deat][<?php echo $key; ?>][type]" value="deat">
									</td>
								</tr>
							</table>
						</div>
						<?php  
						$dc++; 
					} 
					?>
					</td>
				</tr>	
			</table>
		</div>

		<?php 
		unset($event['birt']);
		unset($event['deat']);

		if(empty($event)){
			$event[0][0] = array(
				'date' => '', 
				'place' => '',
				'type' => ''
			);
		}

		$aditionals_events = $this->get_aditionals_events();

		?>
		<div class="gta-col-3 coll-three">
			<table class="gta-table">
				<tr>
					<td colspan="2">
						<h4><?php _e('Additional Events', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2">					
						<?php 
						$yc = 0; 
						foreach ($event as $key => $event_single_group) {  
						$xc = 0; 
						?>
						<?php				
						foreach ($event_single_group as $keyx => $value) {
						?>
						<div class="repetead-field rep-birt-deat-event"> 
							<?php if ($yc===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($yc>0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table class="gta-table">
								<tr>
									<td style="width: 150px;">
										<label style="padding-right: 20px;" for="schooling">
											<select name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][type]">
												<option value="0"><?php _e('Select an Event', 'genealogical-tree'); ?></option>
												<?php 
												foreach ($aditionals_events as $keye => $valuee) { 
													if (!isset($aditionals_events[$key]['type'])) {
														if($event_single_group[$keyx]['type']){
															$aditionals_events[$event_single_group[$keyx]['type']] = array(
																'type' => $event_single_group[$keyx]['type'],
																'title' => ucfirst(str_replace('_', ' ', $event_single_group[$keyx]['type'])),
															);
														}
													}
												}
												foreach ($aditionals_events as $keye => $valuee) { 
													?>
													<option value="<?php echo $valuee['type']; ?>" <?php if($event[$key][$keyx]['type']===$valuee['type']) {echo "selected";}   ?> > 
														<?php echo ucfirst(strtolower($valuee['title'])); ?>
													</option>
													<?php 
												} 
												?>
											</select>
										</label>
									</td>
									<td>
										<table class="gta-table">
											<tr>
												<td><?php _e('Date', 'genealogical-tree'); ?></td>
												<td><input type="text" id="schooling" value="<?php echo $value['date']; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][date]"></td>
											</tr>
											<tr>
												<td><?php _e('Place', 'genealogical-tree'); ?></td>
												<td>
													<input type="text" id="schooling" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][place]">
													<input type="hidden" id="schooling" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][ref]">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
						<?php 
						$xc++;
						}
						?>
						<?php
						$yc++;
						} 
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding:0px;">
						<h4><?php _e('Contact Information', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label style="width:171px;" for="phone"><?php _e('Phone', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($phone as $key => $phon) { ?>
							<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
								<input type="text" id="phone" name="gt[phone][<?php echo $key; ?>]" value="<?php echo $phon; ?>">
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="email"><?php _e('Email', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($email as $key => $emai) { ?>
						<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<input type="text" id="email" name="gt[email][<?php echo $key; ?>]"  value="<?php echo $emai; ?>">
						</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="address"><?php _e('Address', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($address as $key => $addr) { ?>
						<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<input type="text" id="address" name="gt[address][<?php echo $key; ?>]"  value="<?php echo $addr; ?>">
						</div>
						<?php } ?>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>