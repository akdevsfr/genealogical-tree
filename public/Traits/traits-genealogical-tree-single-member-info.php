<?php

namespace Genealogical_Tree\Genealogical_Tree_Public\Traits;

trait Genealogical_Tree_Single_Member_info
{
    public function misha_gallery_images( $name, $value = '' )
    {
        $html = '
        <div>
            <ul class="misha_gallery_mtb">';
        $hidden = array();
        if ( $images = get_posts( array(
            'post_type'      => 'attachment',
            'orderby'        => 'post__in',
            'order'          => 'ASC',
            'post__in'       => explode( ',', $value ),
            'numberposts'    => -1,
            'post_mime_type' => 'image',
        ) ) ) {
            foreach ( $images as $image ) {
                $hidden[] = $image->ID;
                $image_src = wp_get_attachment_image_src( $image->ID, array( 80, 80 ) );
                $html .= '<li data-id="' . $image->ID . '">
                <span>
                <img src="' . $image_src[0] . '">
                </span>
                </li>';
            }
        }
        $html .= '</ul><div style="clear:both"></div></div>';
        return $html;
    }
    
    /**
     * Get childrens by father ID and mother ID
     *
     * @since    1.0.0
     */
    public function get_childrens( $root, $spouse )
    {
        $chill = array();
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => 1,
            'meta_query'     => array(
            'relation' => 'AND',
            array(
            'key'     => 'father',
            'value'   => $root,
            'compare' => '=',
        ),
            array(
            'key'     => 'mother',
            'value'   => $spouse,
            'compare' => '=',
        ),
        ),
        ) );
        if ( !$spouse ) {
            $query = new \WP_Query( array(
                'post_type'      => 'gt-family',
                'posts_per_page' => 1,
                'meta_query'     => array(
                'relation' => 'AND',
                array(
                'key'     => 'father',
                'value'   => $root,
                'compare' => '=',
            ),
                array(
                'key'     => 'spouse',
                'compare' => 'NOT EXISTS',
            ),
            ),
            ) );
        }
        
        if ( $query->posts ) {
            $family = current( $query->posts );
            $chill = get_post_meta( $family->ID, 'chills' );
        }
        
        if ( $chill ) {
            foreach ( $chill as $key => $value ) {
                if ( !get_post( $value ) ) {
                    unset( $chill[$key] );
                }
            }
        }
        return $chill;
    }
    
    /**
     * Get children by father ID or mother ID
     *
     * @since    1.0.0
     */
    public function check_unknown_spouses( $root )
    {
        $spouses = array();
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'meta_query'     => array(
            'relation' => 'AND',
            array(
            'key'     => 'root',
            'value'   => $root,
            'compare' => '=',
        ),
            array(
            'key'     => 'spouse',
            'compare' => 'NOT EXISTS',
        ),
        ),
        ) );
        if ( $query->posts ) {
            foreach ( $query->posts as $key => $family ) {
                $chill = get_post_meta( $family->ID, 'chill', true );
                if ( $chill ) {
                    array_push( $spouses, $family->ID );
                }
            }
        }
        return $query->posts;
    }
    
    /**
     * Get siblings by ID
     *
     * @since    1.0.0
     */
    public function get_parent_families( $post_id )
    {
        return $this->findParentFamilyNew( $post_id );
    }
    
    /**
     * Get siblings by ID
     *
     * @since    1.0.0
     */
    public function get_families( $post_id )
    {
        return $this->findFamilyNew( $post_id );
    }
    
    /**
     * Member information By ID
     *
     * @since    1.0.0
     */
    public function single_member_info( $post_id )
    {
        $premium = false;
        $through_import = get_post_meta( $post_id, 'through_import', true );
        if ( $through_import && !$premium ) {
            return;
        }
        $html = '';
        if ( !$post_id ) {
            return $html;
        }
        if ( !get_post( $post_id ) ) {
            return $html;
        }
        $gt_family_group = get_the_terms( $post_id, 'gt-family-group' );
        $tree_link = '';
        if ( $gt_family_group && !is_wp_error( $gt_family_group ) ) {
            if ( $gt_family_group ) {
                foreach ( $gt_family_group as $key => $term ) {
                    $tree_link .= '
                    <a class="tree_link" title="Family Tree – ' . $term->name . '" href="' . get_the_permalink( get_term_meta( $term->term_id, 'tree_page', true ) ) . '?root=' . $post_id . '"> 
                        <img style="display:block; width: 20px;" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/family-tree.svg">
                    </a>
                    ';
                }
            }
        }
        $full_name = get_post_meta( $post_id, 'full_name', true );
        $event = get_post_meta( $post_id, 'event', true );
        $parent_html = '';
        $parents = $this->findFamilyNew( $post_id, 'famc' );
        
        if ( $parents ) {
            $row_count = 0;
            foreach ( $parents as $key => $spouse ) {
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                $mother = get_post_meta( $spouse->ID, 'mother', true );
                $father = get_post_meta( $spouse->ID, 'father', true );
                $parents[$key]->row_count = 0;
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                $row_count++;
                $parents[$key]->row_count++;
                $row_count++;
                $parents[$key]->row_count++;
                
                if ( $childrens ) {
                    $row_count++;
                    $parents[$key]->row_count++;
                }
            
            }
        }
        
        
        if ( $parents ) {
            $parent_html .= '<tr>';
            $parent_html .= '<td colspan="4"></td>';
            $parent_html .= '</tr>';
            $sp = 1;
            foreach ( $parents as $key => $spouse ) {
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                $mother = get_post_meta( $spouse->ID, 'mother', true );
                $father = get_post_meta( $spouse->ID, 'father', true );
                
                if ( $mother || $father ) {
                    $parent_html .= '<tr>';
                    
                    if ( $sp === 1 ) {
                        $parent_html .= '<td valign="top" rowspan="' . $row_count . '">';
                        $parent_html .= __( 'Parents', 'genealogical-tree' );
                        $parent_html .= '</td>';
                    }
                    
                    $parent_html .= '<td  width="50" valign="top" rowspan="' . $parents[$key]->row_count . '">';
                    $parent_html .= '#' . $sp;
                    $sp++;
                    $parent_html .= '</td>';
                    $parent_html .= '<td  width="200">';
                    $parent_html .= __( 'Mother', 'genealogical-tree' );
                    $parent_html .= '</td>';
                    $parent_html .= '<td>';
                    
                    if ( $mother ) {
                        $parent_html .= '<a href="' . get_the_permalink( $mother ) . '">' . get_post_meta( $mother, 'full_name', true ) . '</a>';
                    } else {
                        $parent_html .= __( 'Unknown', 'genealogical-tree' );
                    }
                    
                    $parent_html .= '</td>';
                    $parent_html .= '</tr>';
                    $parent_html .= '<tr>';
                    $parent_html .= '<td  width="200">';
                    $parent_html .= __( 'Father', 'genealogical-tree' );
                    $parent_html .= '</td>';
                    $parent_html .= '<td>';
                    
                    if ( $father ) {
                        $parent_html .= '<a href="' . get_the_permalink( $father ) . '">' . get_post_meta( $father, 'full_name', true ) . '</a>';
                    } else {
                        $parent_html .= __( 'Unknown', 'genealogical-tree' );
                    }
                    
                    $parent_html .= '</td>';
                    $parent_html .= '</tr>';
                    
                    if ( $childrens ) {
                        $parent_html .= '<tr>';
                        $parent_html .= '<td valign="top">';
                        $parent_html .= __( 'Siblings', 'genealogical-tree' );
                        $parent_html .= '</td>';
                        $parent_html .= '<td>';
                        $children_html = array();
                        foreach ( $childrens as $key => $children ) {
                            
                            if ( $children != $post_id ) {
                                $gender = 'U';
                                if ( get_post_meta( $children, 'sex', true ) === 'M' ) {
                                    $gender = '<span class="gt-gender-emoji">♂️</span>';
                                }
                                if ( get_post_meta( $children, 'sex', true ) === 'F' ) {
                                    $gender = '<span class="gt-gender-emoji">♀️</span>';
                                }
                                array_push( $children_html, ' <a href="' . get_the_permalink( $children ) . '">' . $gender . ' ' . get_post_meta( $children, 'full_name', true ) . '</a>' );
                            }
                        
                        }
                        $parent_html .= implode( ', ', $children_html );
                        if ( empty($children_html) ) {
                            $parent_html .= 'N/A';
                        }
                        $parent_html .= '</td>';
                        $parent_html .= '</tr>';
                    }
                
                }
            
            }
        }
        
        $spouse_html = '';
        $spouses = $this->findFamilyNew( $post_id, 'fams' );
        
        if ( $spouses ) {
            $row_count = 0;
            foreach ( $spouses as $key => $spouse ) {
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                $mother = get_post_meta( $spouse->ID, 'mother', true );
                $father = get_post_meta( $spouse->ID, 'father', true );
                $spouse_id = ( $post_id == $father ? $mother : (( $post_id == $mother ? $father : NULL )) );
                $spouses[$key]->row_count = 0;
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                
                if ( $spouse_id ) {
                    $row_count++;
                    $spouses[$key]->row_count++;
                }
                
                
                if ( $childrens ) {
                    $row_count++;
                    $spouses[$key]->row_count++;
                }
                
                
                if ( !$spouse_id && $childrens ) {
                    $row_count++;
                    $spouses[$key]->row_count++;
                }
            
            }
        }
        
        
        if ( $spouses ) {
            $spouse_html .= '<tr>';
            $spouse_html .= '<td colspan="4"></td>';
            $spouse_html .= '</tr>';
            $sp = 1;
            foreach ( $spouses as $key => $spouse ) {
                $childrens = get_post_meta( $spouse->ID, 'chills' );
                $mother = get_post_meta( $spouse->ID, 'mother', true );
                $father = get_post_meta( $spouse->ID, 'father', true );
                $date = get_post_meta( $spouse->ID, 'date', true );
                $place = get_post_meta( $spouse->ID, 'place', true );
                $spouse_id = ( $post_id == $father ? $mother : (( $post_id == $mother ? $father : NULL )) );
                
                if ( $spouse_id || !$spouse_id && $childrens ) {
                    $spouse_html .= '<tr>';
                    
                    if ( $sp === 1 ) {
                        $spouse_html .= '<td valign="top" rowspan="' . $row_count . '">';
                        $spouse_html .= __( 'Spouse', 'genealogical-tree' );
                        $spouse_html .= '</td>';
                    }
                    
                    $spouse_html .= '<td  width="50" valign="top" rowspan="' . $spouses[$key]->row_count . '">';
                    $spouse_html .= '#' . $sp;
                    $sp++;
                    $spouse_html .= '</td>';
                    $spouse_html .= '<td  width="200">';
                    $spouse_html .= __( 'Name', 'genealogical-tree' );
                    $spouse_html .= '</td>';
                    $spouse_html .= '<td>';
                    
                    if ( $spouse_id ) {
                        $spouse_html .= '<a href="' . get_the_permalink( $spouse_id ) . '">' . get_post_meta( $spouse_id, 'full_name', true ) . '</a>';
                    } else {
                        $spouse_html .= __( 'Unknown', 'genealogical-tree' );
                    }
                    
                    $spouse_html .= '</td>';
                    $spouse_html .= '</tr>';
                    
                    if ( $childrens ) {
                        $spouse_html .= '<tr>';
                        $spouse_html .= '<td valign="top">';
                        $spouse_html .= __( 'Children', 'genealogical-tree' );
                        $spouse_html .= '</td>';
                        $spouse_html .= '<td>';
                        $children_html = array();
                        foreach ( $childrens as $key => $children ) {
                            $gender = 'U';
                            if ( get_post_meta( $children, 'sex', true ) === 'M' ) {
                                $gender = '<span class="gt-gender-emoji">♂️</span>';
                            }
                            if ( get_post_meta( $children, 'sex', true ) === 'F' ) {
                                $gender = '<span class="gt-gender-emoji">♀️</span>';
                            }
                            array_push( $children_html, ' <a href="' . get_the_permalink( $children ) . '">' . $gender . ' ' . get_post_meta( $children, 'full_name', true ) . '</a>' );
                        }
                        $spouse_html .= implode( ', ', $children_html );
                        $spouse_html .= '</td>';
                        $spouse_html .= '</tr>';
                    }
                
                }
            
            }
        }
        
        $birt_html = '';
        if ( isset( $event['birt'] ) && $event['birt'] ) {
            foreach ( $event['birt'] as $key => $birt ) {
                if ( !isset( $birt['place'] ) ) {
                    $birt['place'] = '';
                }
                if ( !$birt['date'] && !$birt['place'] ) {
                    unset( $event['birt'][$key] );
                }
            }
        }
        
        if ( isset( $event['birt'] ) && $event['birt'] ) {
            $row_count = 0;
            foreach ( $event['birt'] as $key => $birt ) {
                if ( isset( $birt['date'] ) && $birt['date'] ) {
                    $row_count++;
                }
                if ( isset( $birt['place'] ) && $birt['place'] ) {
                    $row_count++;
                }
            }
            $ref = 1;
            foreach ( $event['birt'] as $key => $birt ) {
                $birt_html .= '<tr>';
                
                if ( $ref === 1 ) {
                    $birt_html .= '<td valign="top" colspan="1" rowspan="' . $row_count . '">';
                    $birt_html .= __( 'Birth', 'genealogical-tree' );
                    $birt_html .= '</td>';
                }
                
                
                if ( isset( $birt['date'] ) || isset( $birt['place'] ) ) {
                    $inner_row_count = 1;
                    if ( isset( $birt['date'] ) && $birt['date'] && isset( $birt['place'] ) && $birt['place'] ) {
                        $inner_row_count = 2;
                    }
                    $birt_html .= '<td valign="top" width="50" rowspan="' . $inner_row_count . '">';
                    $birt_html .= '#' . $ref;
                    $ref++;
                    $birt_html .= '</td>';
                    
                    if ( isset( $birt['date'] ) && $birt['date'] ) {
                        $birt_html .= '<td>';
                        $birt_html .= __( 'Date of Birth', 'genealogical-tree' );
                        $birt_html .= '</td>';
                        $birt_html .= '<td>';
                        $birt_html .= $birt['date'];
                        $birt_html .= '</td>';
                    }
                    
                    if ( isset( $birt['date'] ) && $birt['date'] ) {
                        $birt_html .= '</tr>';
                    }
                    
                    if ( isset( $birt['place'] ) && $birt['place'] ) {
                        if ( isset( $birt['date'] ) && $birt['date'] ) {
                            $birt_html .= '<tr>';
                        }
                        $birt_html .= '<td>';
                        $birt_html .= __( 'Place of Birth', 'genealogical-tree' );
                        $birt_html .= '</td>';
                        $birt_html .= '<td>';
                        $birt_html .= $birt['place'];
                        $birt_html .= '</td>';
                        $birt_html .= '</tr>';
                    }
                
                }
                
                $birt_html .= '</tr>';
            }
        }
        
        $deat_html = '';
        if ( isset( $event['deat'] ) && $event['deat'] ) {
            foreach ( $event['deat'] as $key => $deat ) {
                if ( !isset( $deat['place'] ) ) {
                    $deat['place'] = '';
                }
                if ( !$deat['date'] && !$deat['place'] ) {
                    unset( $event['deat'][$key] );
                }
            }
        }
        
        if ( isset( $event['deat'] ) && $event['deat'] ) {
            $row_count = 0;
            foreach ( $event['deat'] as $key => $deat ) {
                if ( isset( $deat['date'] ) && $deat['date'] ) {
                    $row_count++;
                }
                if ( isset( $deat['place'] ) && $deat['place'] ) {
                    $row_count++;
                }
            }
            $ref = 1;
            foreach ( $event['deat'] as $key => $deat ) {
                $deat_html .= '<tr>';
                
                if ( $ref === 1 ) {
                    $deat_html .= '<td valign="top" colspan="1" rowspan="' . $row_count . '">';
                    $deat_html .= __( 'Death', 'genealogical-tree' );
                    $deat_html .= '</td>';
                }
                
                
                if ( isset( $deat['date'] ) || isset( $deat['place'] ) ) {
                    $inner_row_count = 1;
                    if ( isset( $deat['date'] ) && $deat['date'] && isset( $deat['place'] ) && $deat['place'] ) {
                        $inner_row_count = 2;
                    }
                    $deat_html .= '<td valign="top" rowspan="' . $inner_row_count . '">';
                    $deat_html .= '#' . $ref;
                    $ref++;
                    $deat_html .= '</td>';
                    
                    if ( isset( $deat['date'] ) && $deat['date'] ) {
                        $deat_html .= '<td>';
                        $deat_html .= __( 'Date of death', 'genealogical-tree' );
                        $deat_html .= '</td>';
                        $deat_html .= '<td>';
                        $deat_html .= $deat['date'];
                        $deat_html .= '</td>';
                    }
                    
                    if ( isset( $deat['date'] ) && $deat['date'] ) {
                        $deat_html .= '</tr>';
                    }
                    
                    if ( isset( $deat['place'] ) && $deat['place'] ) {
                        if ( isset( $deat['date'] ) && $deat['date'] ) {
                            $deat_html .= '<tr>';
                        }
                        $deat_html .= '<td>';
                        $deat_html .= __( 'Place of death', 'genealogical-tree' );
                        $deat_html .= '</td>';
                        $deat_html .= '<td>';
                        $deat_html .= $deat['place'];
                        $deat_html .= '</td>';
                        $deat_html .= '</tr>';
                    }
                
                }
                
                $deat_html .= '</tr>';
            }
        }
        
        $address_html = '';
        
        if ( isset( $event['address_(other)'] ) ) {
            $address_html .= '<tr>';
            $address_html .= '<td colspan="4"></td>';
            $address_html .= '</tr>';
            $address = array();
            foreach ( $event['address_(other)'] as $keya => $value ) {
                array_push( $address, $value );
            }
            $address_html .= '<tr>';
            $address_html .= '<td valign="top" rowspan="' . (count( $address ) + 1) . '">';
            $address_html .= __( 'Location', 'genealogical-tree' );
            $address_html .= '</td>';
            $ref = 1;
            if ( $address ) {
                foreach ( $address as $keyas => $address_single ) {
                    
                    if ( isset( $address_single['place'] ) && $address_single['place'] ) {
                        $address_html .= '<tr>';
                        $address_html .= '<td>';
                        $address_html .= '#' . $ref;
                        $ref++;
                        $address_html .= '</td>';
                        $address_html .= '<td colspan="2">';
                        $address_html .= $address_single['place'];
                        if ( isset( $address_single['date'] ) && $address_single['date'] ) {
                            $address_html .= ' (' . $address_single['date'] . ') ';
                        }
                        $address_html .= '</td>';
                        $address_html .= '</tr>';
                    }
                
                }
            }
            $address_html .= '</tr>';
        }
        
        $aditionals_events = array(
            'buri'            => array(
            'type'  => 'buri',
            'title' => __( 'Burial', 'genealogical-tree' ),
        ),
            'adop'            => array(
            'type'  => 'adop',
            'title' => __( 'Adoption', 'genealogical-tree' ),
        ),
            'enga'            => array(
            'type'  => 'enga',
            'title' => __( 'Engagement', 'genealogical-tree' ),
        ),
            'marr'            => array(
            'type'  => 'marr',
            'title' => __( 'Marriage', 'genealogical-tree' ),
        ),
            'div'             => array(
            'type'  => 'div',
            'title' => __( 'Divorce', 'genealogical-tree' ),
        ),
            'address_(other)' => array(
            'type'  => 'address_(other)',
            'title' => __( 'Address (Other)', 'genealogical-tree' ),
        ),
            'bapm'            => array(
            'type'  => 'bapm',
            'title' => __( 'Baptism', 'genealogical-tree' ),
        ),
            'arms'            => array(
            'type'  => 'arms',
            'title' => __( 'arms', 'genealogical-tree' ),
        ),
            'occupation_1'    => array(
            'type'  => 'occupation_1',
            'title' => __( 'Occupation', 'genealogical-tree' ),
        ),
        );
        if ( isset( $event['birt'] ) ) {
            unset( $event['birt'] );
        }
        if ( isset( $event['deat'] ) ) {
            unset( $event['deat'] );
        }
        if ( isset( $event['address_(other)'] ) ) {
            unset( $event['address_(other)'] );
        }
        $events_html = '';
        
        if ( $event ) {
            $events_html .= '<tr>';
            $events_html .= '<td colspan="4"></td>';
            $events_html .= '</tr>';
            $events_html .= '<tr>';
            $events_html .= '<td valign="top" colspan="4">';
            $events_html .= __( 'Events', 'genealogical-tree' );
            $events_html .= '</td>';
            $events_html .= '</tr>';
            $events_html .= '<tr>';
            foreach ( $event as $key => $ev ) {
                
                if ( $key != 'birt' && $key != 'deat' && $key != 'address_(other)' ) {
                    $ref = 1;
                    if ( $ev ) {
                        foreach ( $ev as $keyx => $evs ) {
                            $events_html .= '<tr>';
                            
                            if ( $ref == 1 ) {
                                $events_html .= '<td  valign="top" rowspan="2">';
                                
                                if ( isset( $aditionals_events[$key] ) ) {
                                    $events_html .= $aditionals_events[$key]['title'];
                                } else {
                                    $events_html .= ucfirst( str_replace( '_', ' ', $key ) );
                                }
                                
                                $events_html .= '</td>';
                            }
                            
                            $events_html .= '<td  valign="top" rowspan="2">';
                            $events_html .= ' #' . $ref;
                            $ref++;
                            $events_html .= '</td>';
                            
                            if ( isset( $evs['date'] ) && $evs['date'] ) {
                                $events_html .= '<td  valign="top">';
                                $events_html .= __( 'Date', 'genealogical-tree' );
                                $events_html .= '</td>';
                                $events_html .= '<td>';
                                $events_html .= $evs['date'];
                                $events_html .= '</td>';
                                $events_html .= '</tr>';
                            }
                            
                            
                            if ( isset( $evs['place'] ) && $evs['place'] ) {
                                $events_html .= '<tr>';
                                $events_html .= '<td>';
                                $events_html .= __( 'Place', 'genealogical-tree' );
                                $events_html .= '</td>';
                                $events_html .= '<td>';
                                $events_html .= $evs['place'];
                                $events_html .= '</td>';
                                $events_html .= '</tr>';
                            }
                            
                            $events_html .= '</tr>';
                        }
                    }
                }
            
            }
            $events_html .= '</tr>';
        }
        
        $gender = 'U';
        if ( get_post_meta( $post_id, 'sex', true ) === 'M' ) {
            $gender = '<span class="gt-gender-emoji">♂️</span>';
        }
        if ( get_post_meta( $post_id, 'sex', true ) === 'F' ) {
            $gender = '<span class="gt-gender-emoji">♀️</span>';
        }
        if ( is_single() ) {
            $featured_img_url = get_the_post_thumbnail_url( $post_id, 'full' );
        }
        $name_html = '
        <tr>
            <td colspan="1">' . __( 'Full Name', 'genealogical-tree' ) . '</td>
            <td colspan="3"> <a href="' . get_the_permalink( $post_id ) . '"> ' . $full_name . '</a> - ' . $tree_link . '</td>
        </tr>';
        $featured_img_html = '';
        if ( isset( $featured_img_url ) && $featured_img_url ) {
            $featured_img_html .= '
            <tr>
                <td colspan="4">
                    <img src="' . $featured_img_url . '">
                </td>
            </tr>';
        }
        $html .= '<h3>' . __( 'Member Information', 'genealogical-tree' ) . '</h3>';
        /*print_r(get_query_var( 'tab'));*/
        $html .= '
		<table class="table table-hover table-condensed indi genealogical-tree-member">
			<tbody>
			<tr style="visibility: collapse;">
			<td width="100"><div style="width:100px;"></div></td>
			<td width="40"><div style="width:40px;"></div></td>
			<td width="100"><div style="width:100px;"></div></td>
			<td width="100%"></td>
			</tr>';
        $html .= '
                ' . $featured_img_html . '
                ' . $name_html . '
                ' . $birt_html . '
                ' . $parent_html . '
				' . $spouse_html . '
				' . $deat_html . '
				' . $address_html . '
				' . $events_html . '
			</tbody>
		</table>
		';
        $additional_info = get_post_meta( $post_id, 'additional_info', true );
        
        if ( $additional_info ) {
            $html .= '<h3>' . __( 'Additional Information', 'genealogical-tree' ) . '</h3>';
            $html .= wpautop( wp_kses_post( get_post_meta( $post_id, 'additional_info', true ) ) );
        }
        
        $html .= $this->misha_gallery_images( 'some_custom_gallery', get_post_meta( $post_id, 'some_custom_gallery', true ) );
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
        // use_request + merge_request + suggestion
        
        if ( is_single() && get_current_user_id() && ($allow_merge_request || $allow_use_request || $allow_suggestion) && (current_user_can( 'editor' ) || current_user_can( 'administrator' ) || current_user_can( 'gt_member' ) || current_user_can( 'gt_manager' )) && get_post_field( 'post_author', $post_id ) != get_current_user_id() ) {
            $html .= '<div class="allow-merge-request">';
            
            if ( $allow_use_request ) {
                // use_request
                $use_request = ( get_post_meta( $post_id, 'use_request' ) ? get_post_meta( $post_id, 'use_request' ) : array() );
                
                if ( isset( $_POST['use_request'] ) ) {
                    array_push( $use_request, get_current_user_id() );
                    $use_request = array_unique( $use_request );
                    delete_post_meta( $post_id, 'use_request' );
                    foreach ( $use_request as $key => $value ) {
                        add_post_meta( $post_id, 'use_request', $value );
                    }
                }
                
                $html .= '<form action="" method="POST">';
                $html .= '<table>';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<a href="">' . __( 'Request Use', 'genealogical-tree' ) . '</a>';
                $html .= '</td></tr>';
                $html .= '<td>';
                
                if ( in_array( get_current_user_id(), $use_request ) ) {
                    $html .= 'Already Requested';
                } else {
                    $html .= '<button name="use_request" type="submit">' . __( 'Send Request', 'genealogical-tree' ) . '</button>';
                }
                
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '</table>';
                $html .= '</form>';
            }
            
            
            if ( $allow_merge_request ) {
                
                if ( isset( $_POST['merge_request'] ) ) {
                    // merge_request
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
                    $html .= '<form action="" method="POST">';
                    $html .= '<table>';
                    $html .= '<tr>';
                    $html .= '<td>';
                    $html .= '<a href="">' . __( 'Request Merge With', 'genealogical-tree' ) . '</a>';
                    $html .= '</td></tr><tr>';
                    $html .= '<td>';
                    $html .= '<select name="member_id">';
                    if ( $query->posts ) {
                        foreach ( $query->posts as $key => $member ) {
                            $html .= '<option value="' . $member->ID . '">';
                            $html .= $member->post_title;
                            $html .= '</option>';
                        }
                    }
                    $html .= '</select>
                                    ';
                    $html .= '</td></tr><tr>';
                    $html .= '<td>';
                    $html .= '<button name="merge_request" type="submit">' . __( 'Send Request', 'genealogical-tree' ) . '</button>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table>';
                    $html .= '</form>';
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
                $html .= '<form action="" method="POST">';
                $html .= '<table>';
                $html .= '<tr>';
                $html .= '<td>';
                $html .= '<a href="">' . __( 'Suggest Information', 'genealogical-tree' ) . '</a>';
                $html .= '</td></tr><tr>';
                $html .= '<td>';
                $html .= '<textarea name="suggestion"></textarea>';
                $html .= '</td></tr><tr>';
                $html .= '<td>';
                $html .= '<button name="submit_suggestion" type="submit">' . __( 'Send Suggest', 'genealogical-tree' ) . '</button>';
                $html .= '</td>';
                $html .= '</tr>';
                $html .= '</table>';
                $html .= '</form>';
            }
            
            $html .= '</div>';
        }
        
        return $html;
    }

}