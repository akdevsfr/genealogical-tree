<?php

namespace Genealogical_Tree\Genealogical_Tree_Public\Traits;

trait Genealogical_Tree_Ind_Style
{
    public function ind_style(
        $ind,
        $setting,
        $gen = null,
        $famsORchills = array(),
        $type = 'root',
        $sign = '-'
    )
    {
        $premium = false;
        $through_import = get_post_meta( $ind, 'through_import', true );
        if ( $through_import && !$premium ) {
            return '<div class="ind"><div class="ind-cont"><div class="info">' . __( 'Your license is not active.', 'genealogical-tree' ) . '</div></div></div>';
        }
        $html = '';
        $event = get_post_meta( $ind, 'event', true );
        $isalive = true;
        $birt = null;
        if ( isset( $event['birt'] ) && $event['birt'] ) {
            
            if ( null !== current( $event['birt'] ) && current( $event['birt'] ) ) {
                $birt = current( $event['birt'] )['date'];
                if ( $setting->birt == 'full' ) {
                    $birt = $birt;
                }
                
                if ( $setting->birt == 'year' ) {
                    $birt = strtotime( $birt );
                    $birt = date( 'Y-d-m', $birt );
                    $birt = explode( '-', $birt );
                    $birt = $birt[0];
                }
                
                if ( $setting->birt == 'none' ) {
                    $birt = null;
                }
            }
        
        }
        $deat = null;
        if ( isset( $event['deat'] ) && $event['deat'] ) {
            
            if ( null !== current( $event['deat'] ) && current( $event['deat'] ) ) {
                $deat = current( $event['deat'] )['date'];
                $isalive = ( $deat ? false : true );
                if ( $setting->deat == 'full' ) {
                    $deat = $deat;
                }
                
                if ( $setting->deat == 'year' ) {
                    $deat = strtotime( $deat );
                    $deat = date( 'Y-d-m', $deat );
                    $deat = explode( '-', $deat );
                    $deat = $deat[0];
                }
                
                if ( $setting->deat == 'none' ) {
                    $deat = null;
                }
            }
        
        }
        $gender = get_post_meta( $ind, 'sex', true );
        $name = get_post_meta( $ind, 'full_name', true );
        if ( !$ind ) {
            $name = __( 'Unknown', 'genealogical-tree' );
        }
        if ( isset( $setting->name ) && $setting->name == 'first' ) {
            $name = explode( ' ', $name )[0];
        }
        if ( isset( $setting->name ) && $setting->name == 'title' ) {
            $name = get_the_title( $ind );
        }
        $highlight = '';
        if ( isset( $setting->root ) && $setting->root == $ind && $setting->root_highlight == 'on' ) {
            $highlight = 'H';
        }
        if ( $setting->style != 3 || $type == 'alter' ) {
            $html .= '<div data-pid="' . $ind . '" class="ind ' . $gender . ' ' . $setting->box->layout . ' ' . get_post_status( $ind ) . ' ' . $highlight . '">';
        }
        $collapsible = '';
        if ( ($setting->collapsible_family_root && $type == 'root' || $setting->collapsible_family_spouse && $type == 'spouse') && count( $famsORchills ) > 0 ) {
            $collapsible = 'gt-collapsible';
        }
        $html .= '<div class="ind-cont ' . $collapsible . '" >';
        if ( isset( $setting->image ) && $setting->image == 'true' ) {
            $setting->thumb->show = 'on';
        }
        
        if ( isset( $setting->thumb->show ) && $setting->thumb->show == 'on' ) {
            $image_url = GENEALOGICAL_TREE_DIR_URL . 'public/img/ava-' . $gender . '.jpg';
            if ( $gender == 'F' ) {
                //$image_url = 'https://randomuser.me/api/portraits/med/women/'.rand(1,99).'.jpg';
            }
            if ( $gender == 'M' ) {
                //$image_url = 'https://randomuser.me/api/portraits/med/men/'.rand(1,99).'.jpg';
            }
            $html .= ' 
			<div class="image">
				<div class="image-cont">';
            $html .= ' 
					<img src="' . $image_url . ' ">
				</div>
			</div>';
        }
        
        $html .= ' 
		<div class="info" data-member-id="' . $ind . '">';
        if ( $gen ) {
            if ( isset( $setting->generation ) && $setting->generation == 'on' ) {
                $html .= '<div class="gt-generation">GEN: ' . $gen . '</div>';
            }
        }
        if ( $ind ) {
            if ( isset( $setting->treelink ) && $setting->treelink == 'on' ) {
                $html .= ' 
					<div class="tree-link">
						<a data-popid="' . $ind . '" href="?root=' . $ind . '"><img style="width:20px;display:block;" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/family-tree.svg"></a>
					</div>';
            }
        }
        $html .= ' 
			<div class="name">
				<a data-popid="' . $ind . '" href="' . get_the_permalink( $ind ) . '">' . $name . '</a>';
        
        if ( isset( $setting->gender ) && $setting->gender != 'none' ) {
            $html .= '
					<span class="gender ' . $gender . '">';
            if ( $setting->gender == 'icon' ) {
                $html .= ' <span></span>';
            }
            
            if ( $setting->gender == 'short' ) {
                if ( $gender == 'M' ) {
                    $html .= '(' . __( 'M', 'genealogical-tree' ) . ')';
                }
                if ( $gender == 'F' ) {
                    $html .= '(' . __( 'F', 'genealogical-tree' ) . ')';
                }
            }
            
            
            if ( $setting->gender == 'full' ) {
                if ( $gender == 'M' ) {
                    $html .= '(' . __( 'Male', 'genealogical-tree' ) . ')';
                }
                if ( $gender == 'F' ) {
                    $html .= '(' . __( 'Female', 'genealogical-tree' ) . ')';
                }
            }
            
            $html .= '
					</span>';
        }
        
        $html .= ' 
			</div>';
        
        if ( ($setting->birt != 'none' || $setting->deat != 'none') && ($birt || $deat) ) {
            $html .= '
			<div class="birt-deat">';
            if ( $setting->birt != 'none' && $setting->deat != 'none' && ($birt && $deat) ) {
                $html .= '(';
            }
            if ( $setting->birt != 'none' && $birt ) {
                
                if ( isset( $setting->birt_hide_alive ) && $setting->birt_hide_alive == 'on' ) {
                    if ( !$isalive ) {
                        $html .= __( 'B', 'genealogical-tree' ) . ':' . $birt;
                    }
                } else {
                    $html .= __( 'B', 'genealogical-tree' ) . ':' . $birt;
                }
            
            }
            if ( $setting->birt != 'none' && $setting->deat != 'none' && ($birt && $deat) ) {
                $html .= ' - ';
            }
            if ( $setting->deat != 'none' && $deat ) {
                $html .= __( 'D', 'genealogical-tree' ) . ':' . $deat;
            }
            if ( $setting->birt != 'none' && $setting->deat != 'none' && ($birt && $deat) ) {
                $html .= ')';
            }
            $html .= '
			</div>';
        }
        
        $html .= '
		</div>';
        
        if ( ($setting->collapsible_family_root && $type == 'root' || $setting->collapsible_family_spouse && $type == 'spouse') && count( $famsORchills ) > 0 ) {
            
            if ( $sign == '+' ) {
                $gtCollapsed = 'gt-collapsed';
            } else {
                
                if ( $sign == '-' ) {
                    $gtCollapsed = '';
                } else {
                    $gtCollapsed = '';
                }
            
            }
            
            $html .= '<div class="gt-collapse-family ' . $gtCollapsed . '">' . $sign . '</div>';
        }
        
        $html .= '
		</div>';
        if ( $setting->style != 3 || $type == 'alter' ) {
            $html .= '
			</div>';
        }
        return $html;
    }

}