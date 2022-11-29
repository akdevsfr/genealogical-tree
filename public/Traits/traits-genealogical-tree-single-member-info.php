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

trait Genealogical_Tree_Single_Member_Info
{
    /**
     * Function for `single_member_info`
     *
     * @param  mixed $post_id post_id.
     * @param  mixed $html html.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function single_member_info( $post_id, $html = '' )
    {
        /**
         * Post id.
         *
         * @since
         */
        if ( !$post_id ) {
            return $html;
        }
        /**
         * Post.
         *
         * @since
         */
        if ( !get_post( $post_id ) ) {
            return $html;
        }
        /**
         * Through import.
         *
         * @since
         */
        $through_import = get_post_meta( $post_id, 'through_import', true );
        /**
         * Premium.
         *
         * @since
         */
        $premium = false;
        if ( $through_import && !$premium ) {
            return;
        }
        /**
         * Even.
         *
         * @since
         */
        $even_array = ( get_post_meta( $post_id, 'even' ) ? get_post_meta( $post_id, 'even' ) : array() );
        /**
         * Fix even tag to uppercase.
         *
         * @since
         */
        foreach ( $even_array as $key => $value ) {
            $even_array[$key]['tag'] = strtoupper( $even_array[$key]['tag'] );
        }
        $event = array();
        $event['EVEN'] = array();
        foreach ( $even_array as $key => $value ) {
            
            if ( 'BIRT' === $value['tag'] ) {
                $event['BIRT'][$key] = $value;
            } elseif ( 'DEAT' === $value['tag'] ) {
                $event['DEAT'][$key] = $value;
            } else {
                $event[$value['tag']][$key] = $value;
            }
        
        }
        $birt = ( isset( $event['BIRT'] ) && !empty($event['BIRT']) ? current( $event['BIRT'] ) : array(
            'date' => '',
            'plac' => '',
        ) );
        $chr = ( isset( $event['CHR'] ) && !empty($event['CHR']) ? current( $event['CHR'] ) : array(
            'date' => '',
            'plac' => '',
        ) );
        $deat = ( isset( $event['DEAT'] ) && !empty($event['DEAT']) ? current( $event['DEAT'] ) : array(
            'date' => '',
            'plac' => '',
        ) );
        $buri = ( isset( $event['BURI'] ) && !empty($event['BURI']) ? current( $event['BURI'] ) : array(
            'date' => '',
            'plac' => '',
        ) );
        $bapl = ( get_post_meta( $post_id, 'bapl', true ) ? get_post_meta( $post_id, 'bapl', true ) : array(
            'date' => '',
            'plac' => '',
        ) );
        $endl = ( get_post_meta( $post_id, 'endl', true ) ? get_post_meta( $post_id, 'endl', true ) : array(
            'date' => '',
            'plac' => '',
        ) );
        /**
         * Note.
         *
         * @since
         */
        $note = ( get_post_meta( $post_id, 'note' ) ? get_post_meta( $post_id, 'note' ) : array() );
        foreach ( $note as $key => $value ) {
            if ( !isset( $value['note'] ) || isset( $value['note'] ) && !$value['note'] ) {
                unset( $note[$key] );
            }
        }
        /**
         * Gender.
         *
         * @since
         */
        $gender = 'U';
        if ( get_post_meta( $post_id, 'sex', true ) === 'M' ) {
            $gender = esc_html__( '♂️ Male', 'genealogical-tree' );
        }
        if ( get_post_meta( $post_id, 'sex', true ) === 'F' ) {
            $gender = esc_html__( '♀️ Female', 'genealogical-tree' );
        }
        /**
         * Slgc.
         *
         * @since
         */
        $slgcs = ( get_post_meta( $post_id, 'slgc' ) ? get_post_meta( $post_id, 'slgc' ) : array( array(
            'famc' => '',
            'date' => '',
            'plac' => '',
        ) ) );
        /**
         * To do make it done with array_filter.
         *
         * @since
         */
        $famc_array = ( get_post_meta( $post_id, 'famc' ) ? get_post_meta( $post_id, 'famc' ) : array( array(
            'famc' => '',
        ) ) );
        $is_duplicate_famc_array = array();
        foreach ( $famc_array as $key => $famc ) {
            if ( !isset( $famc['famc'] ) ) {
                unset( $famc_array[$key] );
            }
            
            if ( is_array( $famc ) ) {
                if ( in_array( $famc['famc'], $is_duplicate_famc_array, true ) ) {
                    unset( $famc_array[$key] );
                }
                array_push( $is_duplicate_famc_array, $famc['famc'] );
            }
        
        }
        $parents = array();
        foreach ( $famc_array as $key => $famc ) {
            if ( isset( $famc['famc'] ) && $famc['famc'] && is_array( $famc['famc'] ) ) {
                $famc['famc'] = $famc['famc']['famc'];
            }
            
            if ( $famc['famc'] ) {
                $parents[$key]['family_id'] = $famc['famc'];
                $parents[$key]['father_id'] = ( get_post_meta( $famc['famc'], 'husb', true ) ? get_post_meta( $famc['famc'], 'husb', true ) : null );
                $parents[$key]['mother_id'] = ( get_post_meta( $famc['famc'], 'wife', true ) ? get_post_meta( $famc['famc'], 'wife', true ) : null );
                $parents[$key]['chil'] = ( get_post_meta( $famc['famc'], 'chil' ) ? get_post_meta( $famc['famc'], 'chil' ) : array() );
                $i = array_search( $post_id, $parents[$key]['chil'], true );
                if ( false !== $i ) {
                    unset( $parents[$key]['chil'][$i] );
                }
                $parents[$key]['even'] = array();
                $famc_even_array = ( get_post_meta( $famc['famc'], 'even' ) ? get_post_meta( $famc['famc'], 'even' ) : array() );
                foreach ( $famc_even_array as $even_key => $even ) {
                    $parents[$key]['even'][strtoupper( $even['tag'] )][$even_key] = $even;
                }
                $parents[$key]['SLGC'] = array(
                    'famc' => '',
                    'date' => '',
                    'plac' => '',
                );
                foreach ( $slgcs as $key => $value ) {
                    if ( $famc['famc'] === $value['famc'] ) {
                        $parents[$key]['SLGC'] = $value;
                    }
                }
                $parents[$key]['MARR'] = ( isset( $parents[$key]['even']['MARR'] ) && !empty($parents[$key]['even']['MARR']) ? current( $parents[$key]['even']['MARR'] ) : array(
                    'date' => '',
                    'plac' => '',
                ) );
            }
        
        }
        /**
         * To do make it done with array_filter.
         *
         * @since
         */
        $fams_array = ( get_post_meta( $post_id, 'fams' ) ? get_post_meta( $post_id, 'fams' ) : array() );
        foreach ( $fams_array as $key => $fams ) {
            if ( !isset( $fams['fams'] ) ) {
                unset( $fams_array[$key] );
            }
        }
        $spouses = array();
        foreach ( $fams_array as $key => $fams ) {
            if ( isset( $fams['fams'] ) && $fams['fams'] && is_array( $fams['fams'] ) ) {
                $fams['fams'] = $fams['fams']['fams'];
            }
            
            if ( $fams['fams'] ) {
                $husb = ( get_post_meta( $fams['fams'], 'husb', true ) ? get_post_meta( $fams['fams'], 'husb', true ) : null );
                $wife = ( get_post_meta( $fams['fams'], 'wife', true ) ? get_post_meta( $fams['fams'], 'wife', true ) : null );
                $fams_even_array = ( get_post_meta( $fams['fams'], 'even' ) ? get_post_meta( $fams['fams'], 'even' ) : array() );
                $spouses[$key]['family_id'] = $fams['fams'];
                $spouses[$key]['spouse'] = ( $husb === $post_id ? $wife : $husb );
                $spouses[$key]['even'] = array();
                foreach ( $fams_even_array as $even_key => $even ) {
                    $spouses[$key]['even'][$even['tag']][$even_key] = $even;
                }
                $spouses[$key]['MARR'] = ( isset( $spouses[$key]['even']['MARR'] ) && !empty($spouses[$key]['even']['MARR']) ? current( $spouses[$key]['even']['MARR'] ) : array(
                    'date' => '',
                    'plac' => '',
                ) );
                $spouses[$key]['SLGS'] = ( get_post_meta( $fams['fams'], 'slgs' ) ? current( get_post_meta( $fams['fams'], 'slgs' ) ) : array(
                    'date' => '',
                    'plac' => '',
                ) );
                $spouses[$key]['chil'] = ( get_post_meta( $fams['fams'], 'chil' ) ? get_post_meta( $fams['fams'], 'chil' ) : array() );
            }
        
        }
        /**
         * Additional fields.
         *
         * @since
         */
        $additional_fields = ( get_post_meta( $post_id, 'additional_fields' ) ? get_post_meta( $post_id, 'additional_fields' ) : array() );
        /**
         * Phone.
         *
         * @since
         */
        $phone = ( get_post_meta( $post_id, 'phone' ) ? get_post_meta( $post_id, 'phone' ) : array() );
        $email = ( get_post_meta( $post_id, 'email' ) ? get_post_meta( $post_id, 'email' ) : array() );
        $address = ( get_post_meta( $post_id, 'address' ) ? get_post_meta( $post_id, 'address' ) : array() );
        $featured_img_url = get_the_post_thumbnail_url( $post_id, 'full' );
        ob_start();
        ?>

		<!-- Personal Information -->
		<h4>
			<?php 
        esc_html_e( 'Personal Information', 'genealogical-tree' );
        ?>
		</h4>
		<table border="0" style="width:100%; max-width: 800px;" class="table table-hover table-condensed indi genealogical-tree-member">

		<?php 
        
        if ( $featured_img_url ) {
            ?>
			<tr>
				<td colspan="3">
					<img src="<?php 
            echo  esc_attr( $featured_img_url ) ;
            ?>">
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<tr>
				<td>
					<div style="width:150px;">
						<?php 
        esc_html_e( 'Name', 'genealogical-tree' );
        ?>
					</div>
				</td>
				<td width="100%" colspan="2">
					<a href="<?php 
        echo  esc_attr( get_the_permalink( $post_id ) ) ;
        ?>">
						<?php 
        echo  esc_html( $this->plugin->helper->get_full_name( $post_id ) ) ;
        ?>
					</a>
					<?php 
        $this->get_tree_link( $post_id );
        ?>
				</td>
			</tr>

			<?php 
        
        if ( $birt['date'] || $birt['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Born', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $birt['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $birt['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<?php 
        
        if ( $chr['date'] || $chr['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Christened', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $chr['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $chr['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<tr>
				<td>
					<?php 
        esc_html_e( 'Gender', 'genealogical-tree' );
        ?>
				</td>
				<td colspan="2">
					<span class="gt-gender-emoji">
						<?php 
        echo  esc_html( $gender ) ;
        ?>
					</span>
				</td>
			</tr>

			<?php 
        
        if ( $deat['date'] || $deat['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Died', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $deat['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $deat['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<?php 
        
        if ( $buri['date'] || $buri['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Buried', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $buri['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $buri['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<?php 
        
        if ( $bapl['date'] || $bapl['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Baptized (LDS)', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $bapl['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $bapl['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<?php 
        
        if ( $endl['date'] || $endl['plac'] ) {
            ?>
			<tr>
				<td>
					<?php 
            esc_html_e( 'Endowed (LDS)', 'genealogical-tree' );
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $endl['date'] ) ;
            ?>
				</td>
				<td>
					<?php 
            echo  esc_html( $endl['plac'] ) ;
            ?>
				</td>
			</tr>
			<?php 
        }
        
        ?>

			<?php 
        
        if ( !empty($note) ) {
            ?>
			<tr>
				<td valign="top"  colspan="3">
					<?php 
            esc_html_e( 'Notes ', 'genealogical-tree' );
            ?>
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div style="max-height: 300px; width: 100%; overflow-y: scroll;">
					<?php 
            foreach ( $note as $key => $value ) {
                
                if ( (!isset( $value['isRef'] ) || $value['isRef']) && $value['note'] ) {
                    echo  esc_html( nl2br( $value['note'] ) ) ;
                    echo  '<br>' ;
                    echo  '<br>' ;
                }
            
            }
            ?>
					</div>
				</td>
			</tr>
			<?php 
        }
        
        ?>


			<tr>
				<td>
					<?php 
        esc_html_e( 'Person ID', 'genealogical-tree' );
        ?>
				</td>
				<td colspan="2">
					<?php 
        echo  esc_html( $post_id ) ;
        ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
        esc_html_e( 'Last Modified', 'genealogical-tree' );
        ?>
				</td>
				<td colspan="2">
					<?php 
        echo  esc_html( get_post( $post_id )->post_modified ) ;
        ?>
				</td>
			</tr>


		</table>

		<!-- Parents -->
		<?php 
        
        if ( !empty($parents) ) {
            ?>
			<?php 
            $parents_count = 1;
            foreach ( $parents as $key => $family ) {
                ?>
		<h4>
				<?php 
                esc_html_e( 'Parents', 'genealogical-tree' );
                ?> 
				(
					<?php 
                echo  esc_html( $parents_count ) ;
                ?> 
					<?php 
                $parents_count++;
                ?>
				)
		</h4>
		<table border="0" style="width:100%; max-width: 800px;">
				<?php 
                
                if ( $family['father_id'] ) {
                    ?>
			<tr>
				<td>
					<div style="width:150px;">
						<?php 
                    esc_html_e( 'Father', 'genealogical-tree' );
                    ?>
					</div>
				</td>
				<td width="100%" colspan="2">
					<a href="<?php 
                    echo  esc_attr( get_the_permalink( $family['father_id'] ) ) ;
                    ?>">
						<?php 
                    echo  esc_html( $this->plugin->helper->get_full_name( $family['father_id'] ) ) ;
                    ?>
					</a>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( $family['mother_id'] ) {
                    ?>
			<tr>
				<td>
					<?php 
                    esc_html_e( 'Mother', 'genealogical-tree' );
                    ?>
				</td>
				<td colspan="2">
					<a href="<?php 
                    echo  esc_attr( get_the_permalink( $family['mother_id'] ) ) ;
                    ?>">
						<?php 
                    echo  esc_html( $this->plugin->helper->get_full_name( $family['mother_id'] ) ) ;
                    ?>
					</a>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( isset( $family['MARR'] ) && ($family['MARR']['date'] || $family['MARR']['plac']) ) {
                    ?>
			<tr>
				<td>
					<?php 
                    esc_html_e( 'Married', 'genealogical-tree' );
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['MARR']['date'] ) ;
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['MARR']['plac'] ) ;
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( $family['SLGC']['date'] || $family['SLGC']['plac'] ) {
                    ?>
			<tr>
				<td>
					<?php 
                    esc_html_e( 'Sealed P (LDS)', 'genealogical-tree' );
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['SLGC']['date'] ) ;
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['SLGC']['plac'] ) ;
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( !empty($family['chil']) ) {
                    ?>
			<tr>
				<td valign="top">
					<?php 
                    esc_html_e( 'Siblings ', 'genealogical-tree' );
                    ?>
				</td>
				<td colspan="2">
					<?php 
                    $chils = array_unique( $family['chil'] );
                    foreach ( $chils as $key => $chil ) {
                        
                        if ( $post_id !== $chil ) {
                            $gender = '⚥';
                            if ( 'M' === get_post_meta( $chil, 'sex', true ) ) {
                                $gender = '♂️';
                            }
                            if ( 'F' === get_post_meta( $chil, 'sex', true ) ) {
                                $gender = '♀️';
                            }
                            ?>
							<a href="<?php 
                            echo  esc_attr( get_the_permalink( $chil ) ) ;
                            ?>">
								<span class="gt-gender-emoji"><?php 
                            echo  esc_html( $gender ) ;
                            ?></span> <?php 
                            echo  esc_html( $this->plugin->helper->get_full_name( $chil ) ) ;
                            ?>
							</a>
							<br>
							<?php 
                        }
                    
                    }
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>
			<tr>
				<td>
					<?php 
                esc_html_e( 'Family  ID', 'genealogical-tree' );
                ?>
				</td>
				<td colspan="2">
					<?php 
                echo  esc_html( $family['family_id'] ) ;
                ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
                esc_html_e( 'Last Modified', 'genealogical-tree' );
                ?>
				</td>
				<td colspan="2">
					<?php 
                echo  esc_html( get_post( $family['family_id'] )->post_modified ) ;
                ?>
				</td>
			</tr>
		</table>
		<?php 
            }
            ?>
		<?php 
        }
        
        ?>

		<?php 
        
        if ( !empty($spouses) ) {
            ?>
		<!-- Families -->
			<?php 
            $spouses_count = 1;
            foreach ( $spouses as $key => $family ) {
                ?>
		<h4>
				<?php 
                esc_html_e( 'Spouses', 'genealogical-tree' );
                ?> (
				<?php 
                echo  esc_html( $spouses_count ) ;
                ?>
				<?php 
                $spouses_count++;
                ?>
				)
		</h4>
		<table border="0" style="width:100%; max-width: 800px;">
			<tr>
				<td>
					<div style="width:150px;"><?php 
                esc_html_e( 'Spouse', 'genealogical-tree' );
                ?> </div>
				</td>
				<td width="100%" colspan="2">
				<?php 
                
                if ( $family['spouse'] ) {
                    ?>
					<a href="<?php 
                    echo  esc_attr( get_the_permalink( $family['spouse'] ) ) ;
                    ?>">
						<?php 
                    echo  esc_html( $this->plugin->helper->get_full_name( $family['spouse'] ) ) ;
                    ?>
					</a>
					<?php 
                } else {
                    ?>
						<?php 
                    esc_html_e( 'Unknown', 'genealogical-tree' );
                    ?>
					<?php 
                }
                
                ?>
				</td>
			</tr>
			<?php 
                
                if ( $family['MARR']['date'] || $family['MARR']['plac'] ) {
                    ?>
			<tr>
				<td>
					<?php 
                    esc_html_e( 'Married', 'genealogical-tree' );
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['MARR']['date'] ) ;
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['MARR']['plac'] ) ;
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( $family['SLGS']['date'] || $family['SLGS']['plac'] ) {
                    ?>

			<tr>
				<td>
					<?php 
                    esc_html_e( 'Sealed S (LDS)', 'genealogical-tree' );
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( $family['SLGS']['date'] ) ;
                    ?>
				</td>
				<td>
					<?php 
                    echo  esc_html( ( isset( $family['SLGS']['plac'] ) ? $family['SLGS']['plac'] : '' ) ) ;
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>

				<?php 
                
                if ( !empty($family['chil']) ) {
                    ?>
			<tr>
				<td valign="top">
					<?php 
                    esc_html_e( 'Children', 'genealogical-tree' );
                    ?>
				</td>
				<td colspan="2">
					<?php 
                    foreach ( $family['chil'] as $key => $chil ) {
                        ?>
						<?php 
                        $gender = '⚥';
                        if ( 'M' === get_post_meta( $chil, 'sex', true ) ) {
                            $gender = '♂️';
                        }
                        if ( 'F' === get_post_meta( $chil, 'sex', true ) ) {
                            $gender = '♀️';
                        }
                        ?>
						<a href="<?php 
                        echo  esc_attr( get_the_permalink( $chil ) ) ;
                        ?>">
							<span class="gt-gender-emoji"><?php 
                        echo  esc_html( $gender ) ;
                        ?></span> <?php 
                        echo  esc_html( $this->plugin->helper->get_full_name( $chil ) ) ;
                        ?>
						</a>
						<br>
					<?php 
                    }
                    ?>
				</td>
			</tr>
			<?php 
                }
                
                ?>
			<!--
			<tr>
				<td>
					<?php 
                esc_html_e( 'Family  ID', 'genealogical-tree' );
                ?>
				</td>
				<td colspan="2">
					<?php 
                echo  esc_html( $family['family_id'] ) ;
                ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php 
                esc_html_e( 'Last Modified', 'genealogical-tree' );
                ?>
				</td>
				<td colspan="2">
					<?php 
                echo  esc_html( get_post( $family['family_id'] )->post_modified ) ;
                ?>
				</td>
			</tr>
			-->
		</table>
		<?php 
            }
            ?>
		<?php 
        }
        
        ?>
		<?php 
        $individual_events = $this->plugin->helper->get_individual_events();
        foreach ( $even_array as $key => $value ) {
            $even_array[$key]['title'] = $this->plugin->helper->search_for_tag( $value['type'], $individual_events );
            if ( !$value['date'] && !$value['plac'] ) {
                unset( $even_array[$key] );
            }
        }
        ?>
		<?php 
        
        if ( !empty($even_array) ) {
            ?>
		<!-- Events -->
		<h4>
			<?php 
            esc_html_e( 'Events', 'genealogical-tree' );
            ?>
		</h4>
			<?php 
            uasort( $even_array, array( $this->plugin->helper, 'sort_events' ) );
            ?>
		<div class="gt-tree-timeline" style="max-width:800px;">
			<?php 
            foreach ( $even_array as $key => $value ) {
                ?>
			<div class="gt-tree-timeline__event">
				<div class="gt-tree-timeline__event__icon ">
					<i class="lni-cake"></i>
					<div class="gt-tree-timeline__event__date">
						<?php 
                echo  esc_html( $value['date'] ) ;
                ?>
					</div>
				</div>
				<div class="gt-tree-timeline__event__content ">
					<div class="gt-tree-timeline__event__title">
						<?php 
                echo  esc_html( ( $value['title'] ? $value['title'] : $value['type'] ) ) ;
                ?>
					</div>
					<div class="gt-tree-timeline__event__description">
						<p> 📍 <?php 
                echo  esc_html( $value['plac'] ) ;
                ?></p>
					</div>
				</div>
			</div>
			<?php 
            }
            ?>
		</div>
		<?php 
        }
        
        ?>

		<?php 
        foreach ( $additional_fields as $key => $value ) {
            if ( !$value['name'] && !$value['value'] ) {
                unset( $additional_fields[$key] );
            }
        }
        
        if ( !empty($additional_fields) || get_post_meta( $post_id, 'additional_info', true ) ) {
            ?>

		<!-- Additional Information -->
		<h4>
			<?php 
            esc_html_e( 'Additional Information', 'genealogical-tree' );
            ?>
		</h4>
		<table border="0" style="width:100%; max-width: 800px;">

			<?php 
            
            if ( !empty($additional_fields) ) {
                ?>
				<?php 
                foreach ( $additional_fields as $key => $additional_field ) {
                    ?>
					<tr>
						<td>
							<div style="width:150px;">
								<strong><?php 
                    echo  esc_html( $additional_fields[$key]['name'] ) ;
                    ?></strong>
							</div>
						</td>
						<td width="100%">
							<?php 
                    echo  esc_html( $additional_fields[$key]['value'] ) ;
                    ?>
						</td>
					</tr>
				<?php 
                }
                ?>
			<?php 
            }
            
            ?>

			<?php 
            
            if ( get_post_meta( $post_id, 'additional_info', true ) ) {
                ?>
			<tr>
				<td colspan="2">
					<div style="width:150px;">
						<strong> <?php 
                esc_html_e( 'Additional Info', 'genealogical-tree' );
                ?> </strong>
					</div>
					<?php 
                echo  wp_kses_post( wpautop( get_post_meta( $post_id, 'additional_info', true ) ) ) ;
                ?>
				</td>
			</tr>
			<?php 
            }
            
            ?>

		</table>
		<?php 
        }
        
        ?>


		<?php 
        
        if ( get_post_meta( $post_id, 'some_custom_gallery', true ) ) {
            ?>

		<!-- Photos -->
		<h4><?php 
            esc_html_e( 'Photos', 'genealogical-tree' );
            ?> </h4>
		<table border="0" style="width:100%; max-width: 800px;">
			<tr>
				<td>
					<?php 
            $this->gt_member_gallery_images( $post_id );
            ?>
				</td>
			</tr>
		</table>
		<?php 
        }
        
        ?>

		<?php 
        
        if ( $phone && !empty($phone) && current( $phone ) || $email && !empty($email) && current( $email ) || $address && !empty($address) && current( $address ) ) {
            ?>

		<!-- Contact Information -->
		<h4>
			<?php 
            esc_html_e( 'Contact Information', 'genealogical-tree' );
            ?>
		</h4>

		<table border="0" style="width:100%; max-width: 800px;">
			<?php 
            
            if ( $phone && !empty($phone) && current( $phone ) ) {
                ?>

				<tr>
					<td width="10" valign="top" rowspan="<?php 
                echo  count( $phone ) ;
                ?>">
						<div style="width:150px;">
							<?php 
                esc_html_e( 'Phone', 'genealogical-tree' );
                ?>
						</div>
					</td>
				<?php 
                foreach ( $phone as $key => $value ) {
                    ?>

					<?php 
                    if ( $key >= 1 ) {
                        ?>
				<tr>
				<?php 
                    }
                    ?>
					<td>
						<?php 
                    echo  esc_html( $value ) ;
                    ?>
					</td>
				</tr>
				<?php 
                }
                ?>

			<?php 
            }
            
            ?>

			<?php 
            
            if ( $email && !empty($email) && current( $email ) ) {
                ?>
				<tr>
					<td rowspan="<?php 
                echo  count( $email ) ;
                ?>">
						<?php 
                esc_html_e( 'Email', 'genealogical-tree' );
                ?>
					</td>
				<?php 
                foreach ( $email as $key => $value ) {
                    ?>
					<?php 
                    if ( $key >= 1 ) {
                        ?>
				<tr>
				<?php 
                    }
                    ?>
					<td>
						<?php 
                    echo  esc_html( $value ) ;
                    ?>
					</td>
				</tr>
				<?php 
                }
                ?>
			<?php 
            }
            
            ?>

			<?php 
            
            if ( $address && !empty($address) && current( $address ) ) {
                ?>
				<tr>
					<td rowspan="<?php 
                echo  count( $address ) ;
                ?>">
						<?php 
                esc_html_e( 'Address', 'genealogical-tree' );
                ?>
					</td>
				<?php 
                foreach ( $address as $key => $value ) {
                    ?>
					<?php 
                    if ( $key >= 1 ) {
                        ?>
				<tr>
				<?php 
                    }
                    ?>
					<td>
						<?php 
                    echo  esc_html( $value ) ;
                    ?>
					</td>
				</tr>
				<?php 
                }
                ?>
			<?php 
            }
            
            ?>
		</table>

		<?php 
        }
        
        ?>

		<!-- Colabaration -->
		<?php 
        $allow_merge_request = false;
        $allow_use_request = false;
        $allow_suggestion = false;
        $terms = get_the_terms( $post_id, 'gt-family-group' );
        if ( $terms && !is_wp_error( $terms ) ) {
            foreach ( $terms as $key => $term ) {
                if ( get_term_meta( $term->term_id, 'allow_merge_request', true ) ) {
                    $allow_merge_request = true;
                }
                if ( get_term_meta( $term->term_id, 'allow_use_request', true ) ) {
                    $allow_use_request = true;
                }
                if ( get_term_meta( $term->term_id, 'allow_suggestion', true ) ) {
                    $allow_suggestion = true;
                }
            }
        }
        // use_request + merge_request + suggestion.
        
        if ( is_single() && get_current_user_id() && ($allow_merge_request || $allow_use_request || $allow_suggestion) && (current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'gt_member' ) || current_user_can( 'gt_manager' )) && get_post_field( 'post_author', $post_id ) != get_current_user_id() ) {
            ?>
			<div class="allow-merge-request">
			<?php 
            
            if ( $allow_use_request ) {
                // use_request.
                $use_request = ( get_post_meta( $post_id, 'use_request' ) ? get_post_meta( $post_id, 'use_request' ) : array() );
                
                if ( isset( $_POST['use_request'] ) ) {
                    array_push( $use_request, get_current_user_id() );
                    $use_request = array_unique( $use_request );
                    delete_post_meta( $post_id, 'use_request' );
                    foreach ( $use_request as $key => $value ) {
                        add_post_meta( $post_id, 'use_request', $value );
                    }
                }
                
                ?>
				<form action="" method="POST">
					<table>
						<tr>
							<td>
								<a href=""><?php 
                esc_html_e( 'Request Use', 'genealogical-tree' );
                ?></a>
							</td>
						</tr>
						<tr>
							<td>
								<?php 
                
                if ( in_array( get_current_user_id(), $use_request ) ) {
                    ?>
									<?php 
                    esc_html_e( 'Already Requested', 'genealogical-tree' );
                    ?>
								<?php 
                } else {
                    ?>
								<button name="use_request" type="submit">
									<?php 
                    esc_html_e( 'Send Request', 'genealogical-tree' );
                    ?>
								</button>
								<?php 
                }
                
                ?>
							</td>
						</tr>
					</table>
				</form>
				<?php 
            }
            
            
            if ( $allow_merge_request ) {
                
                if ( isset( $_POST['merge_request'] ) ) {
                    // merge_request.
                    $merge_request = ( get_post_meta( $post_id, 'merge_request' ) ? get_post_meta( $post_id, 'merge_request' ) : array() );
                    
                    if ( isset( $_POST['member_id'] ) && $_POST['member_id'] ) {
                        array_push( $merge_request, $_POST['member_id'] );
                        $merge_request = array_unique( $merge_request );
                        delete_post_meta( $post_id, 'merge_request' );
                        foreach ( $merge_request as $key => $value ) {
                            add_post_meta( $post_id, 'merge_request', $value );
                        }
                    }
                
                }
                
                $query = new \WP_Query( array(
                    'post_type'      => 'gt-member',
                    'posts_per_page' => -1,
                    'author'         => get_current_user_id(),
                    'meta_query'     => array( array(
                    'key'     => 'merged_to',
                    'compare' => 'NOT EXISTS',
                ) ),
                ) );
                
                if ( count_user_posts( get_current_user_id(), 'gt-member' ) ) {
                    ?>
					<form action="" method="POST">
						<table>
							<tr>
								<td>
									<a href=""><?php 
                    esc_html_e( 'Request Merge With', 'genealogical-tree' );
                    ?></a>
								</td>
							</tr>
							<tr>
								<td>
									<select name="member_id">
									<?php 
                    if ( $query->posts ) {
                        foreach ( $query->posts as $key => $member ) {
                            ?>
										<option value="<?php 
                            echo  esc_attr( $member->ID ) ;
                            ?>">
											<?php 
                            echo  esc_html( $member->post_title ) ;
                            ?>
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
									<button name="merge_request" type="submit">
										<?php 
                    esc_html_e( 'Send Request', 'genealogical-tree' );
                    ?>
									</button>
								</td>
							</tr>
						</table>
					</form>
					<?php 
                }
            
            }
            
            
            if ( $allow_suggestion ) {
                if ( isset( $_POST['submit_suggestion'] ) ) {
                    
                    if ( $_POST['suggestion'] ) {
                        $suggestion = array(
                            'sent_by' => get_current_user_id(),
                            'message' => $_POST['suggestion'],
                        );
                        add_post_meta( $post_id, 'suggestions', $suggestion );
                    }
                
                }
                ?>
				<form action="" method="POST">
					<table>
						<tr>
							<td>
								<a href=""><?php 
                esc_html_e( 'Suggest Information', 'genealogical-tree' );
                ?></a>
							</td>
						</tr>
						<tr>
							<td>
								<textarea name="suggestion"></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<button name="submit_suggestion" type="submit">
									<?php 
                esc_html_e( 'Send Suggest', 'genealogical-tree' );
                ?>
								</button>
							</td>
						</tr>
					</table>
				</form>
			<?php 
            }
            
            ?>
			</div>
		<?php 
        }
        
        ?>

		<?php 
        $html = ob_get_clean();
        return $html;
    }

}