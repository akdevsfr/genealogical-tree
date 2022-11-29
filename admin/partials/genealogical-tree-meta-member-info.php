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
if ( gt_fs()->is_not_paying() && !gt_fs()->is_trial() ) {
    ?>
<style type="text/css">
	.gta-table .repetead-field .clone,
	.gta-table .repetead-field .delete {
		display: none;
	}
</style>
<?php 
}
$ref_id = ( get_post_meta( get_the_ID(), 'ref_id', true ) ? get_post_meta( get_the_ID(), 'ref_id', true ) : 'I' . get_the_ID() );
$name = current( $names );
$family_events = $this->plugin->helper->get_family_events();
$useable_members = $this->get_useable_members( $post );
$males = $useable_members['males'];
$females = $useable_members['females'];
$unknowns = $useable_members['unknowns'];
?>
<div class="gta-container">
	<?php 
wp_nonce_field( 'update_member_info_nonce', '_nonce_update_member_info_nonce' );
?>
	<table class="gta-table">
		<tr>
			<td>
				<h4 style="display: inline;background: #0085ba;padding: 0px 0px;color: #fff;">
					<?php 
esc_html_e( 'ID', 'genealogical-tree' );
?>: <?php 
echo  get_the_ID() ;
?> 
					| 
					<?php 
esc_html_e( 'REF ID', 'genealogical-tree' );
?>: <?php 
echo  esc_html( $ref_id ) ;
?>
				</h4>
			</td>
		</tr>
	</table>

	<div class="gta-row">
		<div class="gta-col-2 coll-one">
			<table class="gta-table">
				<tr>
					<td colspan="4" style="padding:0px;">
						<h4>
							<?php 
esc_html_e( 'Name', 'genealogical-tree' );
?>
						</h4>
					</td>
				</tr>
				<tr>
					<td>
						<label style="width:169px;" for="name">
							<?php 
esc_html_e( 'Full Name', 'genealogical-tree' );
?>
						</label>
					</td>
					<td colspan="4">
						<input id="name" type="text" name="gt[names][0][name]" value="<?php 
echo  esc_attr( $name['name'] ) ;
?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="npfx">
							<?php 
esc_html_e( 'Name Prefix', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="npfx" type="text" name="gt[names][0][npfx]" value="<?php 
echo  esc_attr( $name['npfx'] ) ;
?>">
					</td>
					<td>
						<label for="nsfx">
							<?php 
esc_html_e( 'Name Suffix', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="nsfx" type="text" name="gt[names][0][nsfx]" value="<?php 
echo  esc_attr( $name['nsfx'] ) ;
?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="givn">
							<?php 
esc_html_e( 'Given Name', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="givn" type="text" name="gt[names][0][givn]" value="<?php 
echo  esc_attr( $name['givn'] ) ;
?>">
					</td>
					<td>
						<label for="nick">
							<?php 
esc_html_e( 'Nickname', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="nick" type="text" name="gt[names][0][nick]" value="<?php 
echo  esc_attr( $name['nick'] ) ;
?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="spfx">
							<?php 
esc_html_e( 'Surname Prefix', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="spfx" type="text" name="gt[names][0][spfx]" value="<?php 
echo  esc_attr( $name['spfx'] ) ;
?>">
					</td>
					<td>
						<label for="surn">
							<?php 
esc_html_e( 'Surname', 'genealogical-tree' );
?>
						</label>
					</td>
					<td>
						<input id="surn" type="text" name="gt[names][0][surn]" value="<?php 
echo  esc_attr( $name['surn'] ) ;
?>">
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">
						<h4><?php 
esc_html_e( 'Gender', 'genealogical-tree' );
?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="sex"><?php 
esc_html_e( 'Gender', 'genealogical-tree' );
?></label>
					</td>
					<td colspan="3">
						<select id="sex" name="gt[sex]">
							<option value="">
								<?php 
esc_html_e( 'Select Gender', 'genealogical-tree' );
?>
							</option>
							<option value="M" <?php 
echo  esc_attr( ( 'M' === $sex ? 'selected' : '' ) ) ;
?>>
								<?php 
esc_html_e( 'Male', 'genealogical-tree' );
?>
							</option>
							<option value="F" <?php 
echo  esc_attr( ( 'F' === $sex ? 'selected' : '' ) ) ;
?>>
								<?php 
esc_html_e( 'Female', 'genealogical-tree' );
?>
							</option>
						</select>
					</td>
				</tr>

				<tr>
					<td colspan="4" style="padding:0px;">
						<h4><?php 
esc_html_e( 'Birth', 'genealogical-tree' );
?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">

						<?php 
$bc = 0;
foreach ( $birt as $key => $value ) {
    ?>
						<div class="repetead-field rep-birt-deat-event">
							<?php 
    $this->clone_delete( $bc );
    ?>
							<table class="gta-table">
								<tr>
									<td colspan="4" width="1%">
										<label style="width: 150px;">
											<?php 
    esc_html_e( 'REF', 'genealogical-tree' );
    ?>  #<span data-ref-c="<?php 
    echo  esc_attr( $bc ) ;
    ?>"><?php 
    echo  esc_attr( $bc + 1 ) ;
    ?> </span>
										</label>
									</td>
								</tr>
								<tr>
									<td width="1%">
										<label style="width: 96px;" for="birt-date">
											<?php 
    esc_html_e( 'Tag', 'genealogical-tree' );
    ?>
										</label>
									</td>
									<td>
										<div style=" width: 150px;" >
											<input id="birt-date" type="text" readonly name="gt[even][BIRT][<?php 
    echo  esc_attr( $key ) ;
    ?>][tag]"  value="<?php 
    echo  esc_attr( $value['tag'] ) ;
    ?>">
										</div>
									</td>
									<td>
										<div style=" width: 50px;" >
											<input type="checkbox" name="gt[even][BIRT][<?php 
    echo  esc_attr( $key ) ;
    ?>][tag_check]" <?php 
    checked( $value['tag_check'], 'on' );
    ?>>
										</div>
									</td>
									<td width="100%"></td>
								</tr>
								<tr>
									<td width="1%">
										<label style="width: 96px;" for="birt-date">
											<?php 
    esc_html_e( 'Type', 'genealogical-tree' );
    ?>
										</label>
									</td>
									<td colspan="3" width="100%">
										<input id="birt-date" type="text" name="gt[even][BIRT][<?php 
    echo  esc_attr( $key ) ;
    ?>][type]"  value="<?php 
    echo  esc_attr( $value['type'] ) ;
    ?>">
									</td>
								</tr>
								<tr>
									<td width="1%">
										<label style="width: 96px;" for="birt-date">
											<?php 
    esc_html_e( 'Date', 'genealogical-tree' );
    ?>
										</label>
									</td>
									<td colspan="3" width="100%">
										<input id="birt-date" type="text" name="gt[even][BIRT][<?php 
    echo  esc_attr( $key ) ;
    ?>][date]"  value="<?php 
    echo  esc_attr( $value['date'] ) ;
    ?>">
									</td>
								</tr>
								<tr>
									<td>
										<label style="width: 96px;">
											<?php 
    esc_html_e( ' Place', 'genealogical-tree' );
    ?>
										</label>
									</td>
									<td colspan="3">
										<input id="birt-place" type="text" name="gt[even][BIRT][<?php 
    echo  esc_attr( $key ) ;
    ?>][plac]" value="<?php 
    echo  esc_attr( ( isset( $value['plac'] ) ? $value['plac'] : '' ) ) ;
    ?>">
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
					<td colspan="4" style="padding:0px;">
						<h4>
							<?php 
esc_html_e( ' Death', 'genealogical-tree' );
?>
						</h4>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">

					<?php 
$dc = 0;
foreach ( $deat as $key => $value ) {
    ?>
					<div class="repetead-field rep-birt-deat-event">
						<?php 
    $this->clone_delete( $dc );
    ?>
						<table class="gta-table">
							<tr>
								<td colspan="4" width="1%">
									<label style="width: 150px;">
										<?php 
    esc_html_e( ' REF', 'genealogical-tree' );
    ?> #<span data-ref-c="<?php 
    echo  esc_attr( $dc ) ;
    ?>"> <?php 
    echo  esc_attr( $dc + 1 ) ;
    ?> </span>
									</label>
								</td>
							</tr>
							<tr>
								<td width="1%">
									<label style="width: 96px;" for="deat-date">
										<?php 
    esc_html_e( ' Tag', 'genealogical-tree' );
    ?>
									</label>
								</td>
								<td>
									<div style="width: 150px;">
										<input id="deat-date" readonly type="text" name="gt[even][DEAT][<?php 
    echo  esc_attr( $key ) ;
    ?>][tag]"  value="<?php 
    echo  esc_attr( $value['tag'] ) ;
    ?>">
									</div>
								</td>
								<td>
									<div style="width: 50px;">
										<input type="checkbox" name="gt[even][DEAT][<?php 
    echo  esc_attr( $key ) ;
    ?>][tag_check]" <?php 
    checked( $value['tag_check'], 'on' );
    ?>>
									</div>
								</td>
								<td width="100%">
								</td>
							</tr>
							<tr>
								<td width="1%">
									<label style="width: 96px;" for="deat-date">
										<?php 
    esc_html_e( ' Type', 'genealogical-tree' );
    ?>
									</label>
								</td>
								<td colspan="3" width="100%">
									<input id="deat-date" type="text" name="gt[even][DEAT][<?php 
    echo  esc_attr( $key ) ;
    ?>][type]"  value="<?php 
    echo  esc_attr( $value['type'] ) ;
    ?>">
								</td>
							</tr>
							<tr>
								<td width="1%">
									<label style="width: 96px;" for="deat-date">
										<?php 
    esc_html_e( ' Date', 'genealogical-tree' );
    ?>
									</label>
								</td>
								<td colspan="3" width="100%">
									<input id="deat-date" type="text" name="gt[even][DEAT][<?php 
    echo  esc_attr( $key ) ;
    ?>][date]"  value="<?php 
    echo  esc_attr( $value['date'] ) ;
    ?>">
								</td>
							</tr>
							<tr>
								<td>
									<label style="width: 96px;">
										<?php 
    esc_html_e( ' Place', 'genealogical-tree' );
    ?>
									</label>
								</td>
								<td colspan="3">
									<input id="deat-place" type="text" name="gt[even][DEAT][<?php 
    echo  esc_attr( $key ) ;
    ?>][plac]" value="<?php 
    echo  esc_attr( ( isset( $value['plac'] ) ? $value['plac'] : '' ) ) ;
    ?>">
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

				<?php 
?>

			</table>
		</div>

		<div class="gta-col-2 coll-two">

			<table class="gta-table">
								<tr>
					<td colspan="4" style="padding:0px;">
						<h4>
							<?php 
esc_html_e( ' Families (Parents)', 'genealogical-tree' );
?>
						</h4>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">
						<?php 
$famc_count = 0;
foreach ( $famc as $key => $fam ) {
    ?>
						<div class="repetead-field">
							<?php 
    $this->clone_delete( $famc_count );
    ?>
							<table class="gta-table">
								<tr>
									<td width="1%">
										<label style="width: 100px;">
											<?php 
    esc_html_e( ' REF #', 'genealogical-tree' );
    ?>
											<a class="no-copy" href="<?php 
    echo  esc_html( get_edit_post_link( $fam['famc'] ) ) ;
    ?>">
												<?php 
    echo  esc_html( $fam['famc'] ) ;
    ?>
											</a>
										</label>
									</td>
									<td width="100%" style="padding:0px;">
										<table class="gta-table">
											<tr>
												<td width="1%">
													<label style="width: 95px;" for="wife">
														<?php 
    esc_html_e( ' Mother', 'genealogical-tree' );
    ?>
													</label>
												</td>
												<td width="100%" colspan="2">
													<select class="select2" id="wife" name="gt[family][parents][<?php 
    echo  esc_attr( $famc_count ) ;
    ?>][wife]">
														<?php 
    $this->select_member_html(
        $females,
        $males,
        $unknowns,
        __( 'Mother', 'genealogical-tree' ),
        $fam['wife']
    );
    ?>
													</select>
													<?php 
    
    if ( $fam['wife'] ) {
        ?>
													<a class="no-copy" href="<?php 
        echo  esc_attr( get_edit_post_link( $fam['wife'] ) ) ;
        ?>"> Edit </a>
													<?php 
    }
    
    ?>
												</td>
											</tr>
											<tr>
												<td>
													<label for="husb"><?php 
    esc_html_e( ' Father', 'genealogical-tree' );
    ?></label>
												</td>
												<td colspan="2">
													<select class="select2" id="wife" name="gt[family][parents][<?php 
    echo  esc_attr( $famc_count ) ;
    ?>][husb]">
														<?php 
    $this->select_member_html(
        $females,
        $males,
        $unknowns,
        __( 'Father', 'genealogical-tree' ),
        $fam['husb']
    );
    ?>
													</select>
													<?php 
    
    if ( $fam['husb'] ) {
        ?>
													<a class="no-copy"  href="<?php 
        echo  esc_attr( get_edit_post_link( $fam['husb'] ) ) ;
        ?>"> Edit </a>
													<?php 
    }
    
    ?>
												</td>
											</tr>
											<tr>
												<td>
													<label for="husb"><?php 
    esc_html_e( ' Pedigree', 'genealogical-tree' );
    ?></label>
												</td>
												<td colspan="2">
													<select class="select2" name="gt[family][parents][<?php 
    echo  esc_attr( $famc_count ) ;
    ?>][pedi]">
														<option <?php 
    selected( $fam['pedi'], '' );
    ?> value="">
															<?php 
    esc_html_e( ' Relation By', 'genealogical-tree' );
    ?>
														</option>
														<option <?php 
    selected( $fam['pedi'], 'BIRTH' );
    ?> value="BIRTH">
															<?php 
    esc_html_e( ' BIRTH', 'genealogical-tree' );
    ?>
														</option>
														<option <?php 
    selected( $fam['pedi'], 'ADOPTED' );
    ?> value="ADOPTED">
															<?php 
    esc_html_e( ' ADOPTED', 'genealogical-tree' );
    ?>
														</option>
														<option <?php 
    selected( $fam['pedi'], 'FOSTER' );
    ?> value="FOSTER">
															<?php 
    esc_html_e( ' FOSTER', 'genealogical-tree' );
    ?>
														</option>
														<option <?php 
    selected( $fam['pedi'], 'SEALING' );
    ?> value="SEALING">
															<?php 
    esc_html_e( ' SEALING', 'genealogical-tree' );
    ?>
														</option>
														<option <?php 
    selected( $fam['pedi'], 'OTHER' );
    ?> value="OTHER">
															<?php 
    esc_html_e( ' OTHER', 'genealogical-tree' );
    ?>
														</option>
													</select>
												</td>
											</tr>
											<tr>
												<td>
													<label>
														<?php 
    esc_html_e( ' Sealed P (LDS)', 'genealogical-tree' );
    ?>
													</label>
													<input type="radio" name="gt[slgc][slgc_check]" value="<?php 
    echo  esc_attr( $famc_count ) ;
    ?>" <?php 
    checked( $fam['slgc']['famc'], $fam['famc'] );
    ?>>
												</td>
												<td colspan="2">
													<table>
														<tr>
															<td>
																<label style="width: 56px;">
																	<?php 
    esc_html_e( ' Date', 'genealogical-tree' );
    ?>
																</label>
															</td>
															<td>
																<input value="<?php 
    echo  esc_attr( $fam['slgc']['date'] ) ;
    ?>" type="text" name="gt[slgc][<?php 
    echo  esc_attr( $famc_count ) ;
    ?>][date]">
															</td>
														</tr>
														<tr>
															<td>
																<label style="width: 56px;">
																	<?php 
    esc_html_e( ' Place (Temple)', 'genealogical-tree' );
    ?>
																</label>
															</td>
															<td>
																<input value="<?php 
    echo  esc_attr( $fam['slgc']['plac'] ) ;
    ?>" type="text" name="gt[slgc][<?php 
    echo  esc_attr( $famc_count ) ;
    ?>][plac]">
															</td>
														</tr>
													</table>
												</td>
											</tr>


											<tr class="no-copy">
												<td>
													<label>
														<?php 
    esc_html_e( ' Siblings', 'genealogical-tree' );
    ?>
													</label>
												</td>
												<td colspan="2">
													<?php 
    $chils = array_unique( $fam['chil'] );
    foreach ( $chils as $key => $chil ) {
        
        if ( get_the_ID() !== (int) $chil ) {
            $gender = '⚥';
            if ( 'M' === (string) get_post_meta( $chil, 'sex', true ) ) {
                $gender = '♂️';
            }
            if ( 'F' === (string) get_post_meta( $chil, 'sex', true ) ) {
                $gender = '♀️';
            }
            echo  '
															<a style="display:block;" href="' . esc_attr( get_edit_post_link( $chil ) ) . '">
																<span class="gt-gender-emoji">' . esc_html( $gender ) . '</span> ' . esc_html( $this->plugin->helper->get_full_name( $chil ) ) . '
															</a>' ;
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
    $famc_count++;
}
?>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">
						<h4>
							<?php 
esc_html_e( ' Families (Spouses)', 'genealogical-tree' );
?>
						</h4>
					</td>
				</tr>
				<tr>
					<td colspan="4" style="padding:0px;">
						<?php 
$y = 0;
foreach ( $fams as $key => $fam ) {
    ?>
						<div class="repetead-field">
							<?php 
    $this->clone_delete( $y );
    ?>
							<table class="gta-table">
								<tr>
									<td width="1%">
										<label style="width: 100px;">
											<?php 
    esc_html_e( ' REF #', 'genealogical-tree' );
    ?>
											<a class="no-copy" href="<?php 
    echo  esc_attr( get_edit_post_link( $fam['fams'] ) ) ;
    ?>">
												<?php 
    echo  esc_html( $fam['fams'] ) ;
    ?>
											</a><br>
											<?php 
    esc_html_e( 'Order:', 'genealogical-tree' );
    ?>
											<input class="gt-spouse-order" value="<?php 
    echo  esc_attr( ( isset( $fam['order'] ) && $fam['order'] ? $fam['order'] : '0' ) ) ;
    ?>" name="gt[family][spouses][<?php 
    echo  esc_attr( $y ) ;
    ?>][order]" type="text">
										</label>
									</td>
									<td width="100%" style="padding:0px;">
										<table class="gta-table">
											<tr>
												<td width="1%">
													<label style="width: 95px;" for="spouse"><?php 
    esc_html_e( ' Spouse', 'genealogical-tree' );
    ?></label>
												</td>
												<td width="100%">
													<select class="select2" id="wife" name="gt[family][spouses][<?php 
    echo  esc_attr( $y ) ;
    ?>][id]">
														<?php 
    $this->select_member_html(
        $females,
        $males,
        $unknowns,
        __( 'Spouse', 'genealogical-tree' ),
        $fam['spouse']
    );
    ?>
													</select>
													<?php 
    
    if ( $fam['spouse'] ) {
        ?>
													<a class="no-copy"  href="<?php 
        echo  esc_attr( get_edit_post_link( $fam['spouse'] ) ) ;
        ?>"> Edit </a>
													<?php 
    }
    
    ?>
												</td>
											</tr>

											<tr>
												<td colspan="2">
													<strong><?php 
    esc_html_e( ' Events', 'genealogical-tree' );
    ?> </strong>
												</td>
											</tr>
											<tr>
												<td colspan="2" style="padding:0px;">
													<?php 
    $yc = 0;
    foreach ( $fam['event'] as $key => $event ) {
        ?>
													<div class="repetead-field rep-birt-deat-event <?php 
        echo  esc_attr( ( $yc > 0 ? 'no-copy' : '' ) ) ;
        ?>">
														<?php 
        $this->clone_delete( $yc );
        ?>
														<table class="gta-table">
															<tr>
																<td>
																	<label style="width: 56px;">
																		<?php 
        esc_html_e( ' Tag', 'genealogical-tree' );
        ?>
																	</label>
																</td>
																<td>
																	<div style="width: 150px;">
																		<select name="gt[family][spouses][<?php 
        echo  esc_attr( $y ) ;
        ?>][even][<?php 
        echo  esc_attr( $yc ) ;
        ?>][tag]">
																			<option value=""><?php 
        esc_html_e( ' Select an Event', 'genealogical-tree' );
        ?></option>
																			<?php 
        foreach ( $family_events as $keye => $valuee ) {
            ?>
																				<option <?php 
            selected( $valuee['type'], $event['tag'] );
            ?> value="<?php 
            echo  esc_attr( $valuee['type'] ) ;
            ?>" >
																					(<?php 
            echo  esc_html( $valuee['type'] ) ;
            ?>) <?php 
            echo  esc_html( $valuee['title'] ) ;
            ?>
																				</option>
																				<?php 
        }
        ?>
																		</select>
																	</div>
																</td>
																<td>
																	<div style="width: 50px;">
																		<input type="checkbox" <?php 
        checked( $event['tag_check'], 'on' );
        ?> name="gt[family][spouses][<?php 
        echo  esc_attr( $y ) ;
        ?>][even][<?php 
        echo  esc_attr( $yc ) ;
        ?>][tag_check]">
																	</div>
																</td>
																<td width="100%"></td>
															</tr>
															<tr>
																<td width="1%">
																	<label style="width: 56px;">
																		<?php 
        esc_html_e( ' Type', 'genealogical-tree' );
        ?>
																	</label>
																</td>
																<td  colspan="3" width="100%">
																	<input type="text"  value="<?php 
        echo  esc_attr( $event['type'] ) ;
        ?>" name="gt[family][spouses][<?php 
        echo  esc_attr( $y ) ;
        ?>][even][<?php 
        echo  esc_attr( $yc ) ;
        ?>][type]">
																</td>
															</tr>
															<tr>
																<td width="1%">
																	<label style="width: 56px;">
																		<?php 
        esc_html_e( ' Date', 'genealogical-tree' );
        ?>
																	</label>
																</td>
																<td  colspan="3" width="100%">
																	<input type="text"  value="<?php 
        echo  esc_attr( $event['date'] ) ;
        ?>" name="gt[family][spouses][<?php 
        echo  esc_attr( $y ) ;
        ?>][even][<?php 
        echo  esc_attr( $yc ) ;
        ?>][date]">
																</td>
															</tr>
															<tr>
																<td>
																	<label style="width: 56px;">
																		<?php 
        esc_html_e( ' Place', 'genealogical-tree' );
        ?>
																	</label>
																</td>
																<td colspan="3">
																	<input type="text" value="<?php 
        echo  esc_attr( ( isset( $event['plac'] ) ? $event['plac'] : '' ) ) ;
        ?>" name="gt[family][spouses][<?php 
        echo  esc_attr( $y ) ;
        ?>][even][<?php 
        echo  esc_attr( $yc ) ;
        ?>][plac]">
																</td>
															</tr>
														</table>
													</div>
													<?php 
        $yc++;
    }
    ?>
												</td>
											</tr>
											<tr class="no-copy">
												<td><?php 
    esc_html_e( ' Chills', 'genealogical-tree' );
    ?> </td>
												<td>
													<input type="hidden" readonly  name="gt[family][spouses][<?php 
    echo  esc_attr( $y ) ;
    ?>][chil][]" value="">
													<?php 
    foreach ( $fam['chil'] as $key => $chil ) {
        
        if ( $chil ) {
            ?>
															<input type="hidden" readonly  name="gt[family][spouses][<?php 
            echo  esc_attr( $y ) ;
            ?>][chil][]" value="<?php 
            echo  esc_attr( $chil ) ;
            ?>">
															<?php 
            $gender = '⚥';
            if ( 'M' === (string) get_post_meta( $chil, 'sex', true ) ) {
                $gender = '♂️';
            }
            if ( 'F' === (string) get_post_meta( $chil, 'sex', true ) ) {
                $gender = '♀️';
            }
            echo  '<a style="display:block;" href="' . esc_attr( get_edit_post_link( $chil ) ) . '"> 
															<span class="gt-gender-emoji">' . esc_html( $gender ) . '</span> ' . esc_html( $this->plugin->helper->get_full_name( $chil ) ) . '
															</a>' ;
        }
    
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
	</div>
</div>
