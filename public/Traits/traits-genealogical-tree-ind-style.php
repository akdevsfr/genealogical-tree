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

trait Genealogical_Tree_Ind_Style
{
    /**
     * It displays the individual's information in the tree.
     *
     * @param  int    $ind The ID of the individual.
     * @param  object $setting the settings for the tree.
     * @param  int    $gen the generation of the individual.
     * @param  array  $fams_or_chills array of family IDs or child IDs.
     * @param  string $type root, spouse, child.
     * @param  string $sign This is the +/- sign that appears on the left of the person's box..
     *
     * @return void
     */
    public function ind_style(
        $ind,
        $setting,
        $gen = null,
        $fams_or_chills = array(),
        $type = 'root',
        $sign = '-'
    )
    {
        /**
         * Check premium?.
         *
         * @since 1.0.0
         */
        $premium = false;
        /**
         * Check through_import?.
         *
         * @since 1.0.0
         */
        $through_import = get_post_meta( $ind, 'through_import', true );
        
        if ( $through_import && !$premium ) {
            ?>
			<div class="ind">
				<div class="ind-cont">
					<div class="info"><?php 
            echo  esc_html__( 'Your license is not active.', 'genealogical-tree' ) ;
            ?></div>
				</div>
			</div>
			<?php 
            return;
        }
        
        /**
         * Get events.
         *
         * @since 1.0.0
         */
        $events = ( get_post_meta( $ind, 'even' ) ? get_post_meta( $ind, 'even' ) : array() );
        foreach ( $events as $key => $value ) {
            $events[$key]['tag'] = strtoupper( $events[$key]['tag'] );
        }
        $event = array();
        foreach ( $events as $key => $value ) {
            if ( 'BIRT' === $value['tag'] ) {
                $event['BIRT'][$key] = $value;
            }
            if ( 'DEAT' === $value['tag'] ) {
                $event['DEAT'][$key] = $value;
            }
        }
        /**
         * Check if have birth date data.
         *
         * @since 1.0.0
         */
        $birt = null;
        if ( isset( $event['BIRT'] ) && $event['BIRT'] ) {
            
            if ( null !== current( $event['BIRT'] ) && current( $event['BIRT'] ) ) {
                $birt = current( $event['BIRT'] )['date'];
                if ( 'full' === $setting->birt ) {
                    $birt = $birt;
                }
                
                if ( 'year' === $setting->birt ) {
                    $birt = strtotime( $birt );
                    $birt = date( 'Y-d-m', $birt );
                    $birt = explode( '-', $birt );
                    $birt = $birt[0];
                }
                
                if ( 'none' === $setting->birt ) {
                    $birt = null;
                }
            }
        
        }
        /**
         * Check if have death date data.
         *
         * @since 1.0.0
         */
        $deat = null;
        if ( isset( $event['DEAT'] ) && $event['DEAT'] ) {
            
            if ( null !== current( $event['DEAT'] ) && current( $event['DEAT'] ) ) {
                $deat = current( $event['DEAT'] )['date'];
                if ( 'full' === $setting->deat ) {
                    $deat = $deat;
                }
                
                if ( 'year' === $setting->deat ) {
                    $deat = strtotime( $deat );
                    $deat = date( 'Y-d-m', $deat );
                    $deat = explode( '-', $deat );
                    $deat = $deat[0];
                }
                
                if ( 'none' === $setting->deat ) {
                    $deat = null;
                }
            }
        
        }
        /**
         * Is alive?
         *
         * @since 1.0.0
         */
        $isalive = ( $deat ? false : true );
        /**
         * Get gender.
         *
         * @since 1.0.0
         */
        $gender = get_post_meta( $ind, 'sex', true );
        /**
         * Get name.
         *
         * @since 1.0.0
         */
        $name = $this->plugin->helper->get_full_name( $ind );
        if ( !$ind ) {
            $name = __( 'Unknown', 'genealogical-tree' );
        }
        if ( isset( $setting->name ) && 'first' === $setting->name ) {
            $name = explode( ' ', $name )[0];
        }
        if ( isset( $setting->name ) && 'title' === $setting->name ) {
            $name = get_the_title( $ind );
        }
        $name = trim( str_replace( array( '/', '\\', '  ' ), array( ' ', '', ' ' ), $name ) );
        /**
         * Chick highlight.
         *
         * @since 1.0.0
         */
        $highlight = '';
        if ( isset( $setting->root ) && $setting->root === $ind && 'on' === $setting->root_highlight ) {
            $highlight = 'H';
        }
        
        if ( '3' !== $setting->style && '3-alt' !== $setting->style || 'alter' === $type ) {
            ?>
			<div data-pid="<?php 
            echo  esc_attr( $ind ) ;
            ?>" class="ind <?php 
            echo  esc_attr( $gender ) ;
            ?> <?php 
            echo  esc_attr( $setting->box->layout ) ;
            ?> <?php 
            echo  esc_attr( get_post_status( $ind ) ) ;
            ?> <?php 
            echo  esc_attr( $highlight ) ;
            ?>">
			<?php 
        }
        
        /**
         * Is collapsible?
         *
         * @since 1.0.0
         */
        $collapsible = '';
        if ( ($setting->collapsible_family_root && 'root' === $type || $setting->collapsible_family_spouse && 'spouse' === $type) && count( $fams_or_chills ) > 0 ) {
            $collapsible = 'gt-collapsible';
        }
        ?>
		<div class="ind-cont <?php 
        echo  esc_attr( $collapsible ) ;
        ?>" >
		<?php 
        /**
         * Show image?
         *
         * @since 1.0.0
         */
        if ( isset( $setting->image ) && 'true' === $setting->image ) {
            $setting->thumb->show = 'on';
        }
        
        if ( isset( $setting->thumb->show ) && 'on' === $setting->thumb->show ) {
            $image_url = GENEALOGICAL_TREE_DIR_URL . 'public/img/ava-' . $gender . '.jpg';
            
            if ( defined( 'GENEALOGICAL_DEV' ) && \GENEALOGICAL_TREE_DEBUG ) {
                if ( 'F' === $gender ) {
                    $image_url = 'https://randomuser.me/api/portraits/med/women/' . wp_rand( 1, 99 ) . '.jpg';
                }
                if ( 'M' === $gender ) {
                    $image_url = 'https://randomuser.me/api/portraits/med/men/' . wp_rand( 1, 99 ) . '.jpg';
                }
            }
            
            ?>
			<div class="image">
				<div class="image-cont">';
					<img src="<?php 
            echo  esc_attr( $image_url ) ;
            ?>">
				</div>
			</div>
			<?php 
        }
        
        ?>
		<div class="info" data-member-id="<?php 
        esc_attr( $ind );
        ?>">
		<?php 
        if ( $gen ) {
            
            if ( isset( $setting->generation ) && 'on' === $setting->generation ) {
                ?>
				<div class="gt-generation"><?php 
                echo  esc_html__( 'GEN:', 'genealogical-tree' ) ;
                ?> <?php 
                echo  esc_html( $gen ) ;
                ?></div>
				<?php 
            }
        
        }
        if ( $ind ) {
            
            if ( isset( $setting->treelink ) && 'on' === $setting->treelink ) {
                ?>
				<div class="tree-link">
					<a data-popid="<?php 
                echo  esc_attr( $ind ) ;
                ?>" href="?root=<?php 
                echo  esc_attr( $ind ) ;
                ?>">
						<img style="width:20px;display:block;" src="<?php 
                echo  esc_attr( GENEALOGICAL_TREE_DIR_URL ) ;
                ?>public/img/family-tree.svg">
					</a>
				</div>
				<?php 
            }
        
        }
        ?>
			<div class="name">
				<a data-popid="<?php 
        echo  esc_attr( $ind ) ;
        ?>" href="<?php 
        echo  esc_attr( get_the_permalink( $ind ) ) ;
        ?>">
					<?php 
        echo  esc_html( $name ) ;
        ?>
				</a>
		<?php 
        
        if ( isset( $setting->gender ) && 'none' !== $setting->gender ) {
            ?>
			<span class="gender <?php 
            echo  esc_attr( $gender ) ;
            ?>">

			<?php 
            if ( 'icon' === $setting->gender ) {
                ?>
				<span></span>
			<?php 
            }
            ?>
			<?php 
            
            if ( 'short' === $setting->gender ) {
                
                if ( 'M' === $gender ) {
                    ?>
					(<?php 
                    echo  esc_html__( 'M', 'genealogical-tree' ) ;
                    ?>)
					<?php 
                }
                
                
                if ( 'F' === $gender ) {
                    ?>
					(<?php 
                    echo  esc_html__( 'F', 'genealogical-tree' ) ;
                    ?>)
					<?php 
                }
            
            }
            
            
            if ( 'full' === $setting->gender ) {
                
                if ( 'M' === $gender ) {
                    ?>
					(<?php 
                    echo  esc_html__( 'Male', 'genealogical-tree' ) ;
                    ?>)
					<?php 
                }
                
                
                if ( 'F' === $gender ) {
                    ?>
					(<?php 
                    echo  esc_html__( 'Female', 'genealogical-tree' ) ;
                    ?>)
					<?php 
                }
            
            }
            
            ?>
			</span>
			<?php 
        }
        
        ?>
		</div>

			<?php 
        
        if ( ('none' !== $setting->birt || 'none' !== $setting->deat) && ($birt || $deat) ) {
            ?>
			<div class="birt-deat">
				<?php 
            if ( 'none' !== $setting->birt && 'none' !== $setting->deat && ($birt && $deat) ) {
                ?>
					(
				<?php 
            }
            ?>

				<?php 
            if ( 'none' !== $setting->birt && $birt ) {
                
                if ( isset( $setting->birt_hide_alive ) && 'on' === $setting->birt_hide_alive ) {
                    
                    if ( !$isalive ) {
                        ?>
							<?php 
                        echo  esc_html__( 'B', 'genealogical-tree' ) ;
                        ?> : <?php 
                        echo  esc_html( $birt ) ;
                        ?>
							<?php 
                    }
                
                } else {
                    ?>
						<?php 
                    echo  esc_html__( 'B', 'genealogical-tree' ) ;
                    ?> : <?php 
                    echo  esc_html( $birt ) ;
                    ?>
						<?php 
                }
            
            }
            ?>

				<?php 
            if ( 'none' !== $setting->birt && 'none' !== $setting->deat && ($birt && $deat) ) {
                ?>
					-
				<?php 
            }
            ?>

				<?php 
            
            if ( 'none' !== $setting->deat && $deat ) {
                ?>
					<?php 
                echo  esc_html__( 'D', 'genealogical-tree' ) ;
                ?> : <?php 
                echo  esc_html( $deat ) ;
                ?>
				<?php 
            }
            
            ?>

				<?php 
            if ( 'none' !== $setting->birt && 'none' !== $setting->deat && ($birt && $deat) ) {
                ?>
					)
				<?php 
            }
            ?>
			</div>
			<?php 
        }
        
        ?>
		</div>
		<?php 
        
        if ( ($setting->collapsible_family_root && 'root' === $type || $setting->collapsible_family_spouse && 'spouse' === $type) && count( $fams_or_chills ) > 0 ) {
            
            if ( '+' === $sign ) {
                $gt_collapsed = 'gt-collapsed';
            } elseif ( '-' === $sign ) {
                $gt_collapsed = '';
            } else {
                $gt_collapsed = '';
            }
            
            ?>
			<div class="gt-collapse-family <?php 
            echo  esc_html( $gt_collapsed ) ;
            ?>"> <?php 
            echo  esc_html( $sign ) ;
            ?></div>
			<?php 
        }
        
        ?>
				</div>
		<?php 
        if ( '3' !== $setting->style && '3-alt' !== $setting->style || 'alter' === $type ) {
            ?>
			</div>
			<?php 
        }
    }

}