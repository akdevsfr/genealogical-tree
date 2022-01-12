<?php
namespace Genealogical_Tree\Includes\Traits;

/**
 * The api functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

/**
 * The api functionality of the plugin.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
trait Genealogical_Tree_Data {


	public function tree_default_meta(){
		return  array(
			'family' => null,
			'root' => null,
			'root_highlight' => false,
			'style' => '1',
			'layout' => 'vr',
			'ajax' => false,
			'name' => 'full',
			'birt' => 'full',
			'birt_hide_alive' => false,
			'deat' => 'full',
			'gender' => 'full',
			'sibling_order' => 'default',
			'generation'=> false,
			'generation_number' => -1,
			'generation_number_ancestor' => -1,
			'ancestor'=> false,
			'popup'=> false,
			'female_tree'=> false,
			'hide_spouse'=> false,
			'hide_un_spouse'=> false,
			'collapsible_family_root'=> false,
			'collapsible_family_spouse'=> false,
			'collapsible_family_onload'=> false,
			'treelink' => false,
			'background' => array (
				'color' => 'rgba(0, 0, 0, .05)'
			),
			'container' => array (
				'background' => array (
					'color' => '#ffffff',
					'image' => 'linear-gradient(90deg, #f0f0f0 1px, transparent 0), linear-gradient(180deg, #f0f0f0 1px, transparent 0)',
					'size' => '2.14785rem 2.14785rem',
				),
				'border' =>  array(
					'style' => 'solid',
					'color' => '#000000',
					'width' => '1px',
					'radius' => '0px',
				)
			), 
			'box' => array (
				'layout' => 'vr',
				'width' => '90px',
				'background' => array(
					'color' => array(
						'male' => '#daeef3',
						'female' => '#ffdbe0',
						'other' => '#ffffff'
					)
				),
				'border' => array(
					'style' => 'solid',
					'color' => array(
						'male' => '#000000',
						'female' => '#000000',
						'other' => '#000000'
					),
					'width' => '1px',
					'radius' => '1px',
				)

			),
			'thumb' => array(
				'show' => false,
				'width' => '80px',
				'border' => array(
					'style' => 'solid',
					'color' => array(
						'male' => '#000000',
						'female' => '#000000',
						'other' => '#000000'
					),
					'width' => '1px',
					'radius' => '1px',
				),
				
			),
			'line' => array(
				'border' => array(
					'style' => 'solid',
					'color'=> '#000000',
					'width' => '1px',
					'radius' => '1px',
				)
			),
			'name_text' => array(
				'font_family' => 'inherit',
				'font_size' => '12px',
				'font_style' => 'inherit',
				'font_weight' => 'regular',
				'color' => 'inherit',
				'align' => 'center',
			),
			'other_text' => array (
				'font_family' => 'inherit',
				'font_size' => '10px',
				'font_style' => 'inherit',
				'font_weight' => 'regular',
				'color' => 'inherit',
				'align' => 'center',
			)
		);
	}

	public function tree_combine_meta($data){
		$default_data = $this->tree_default_meta();
		foreach ($default_data as $key => $value) {
			if(is_array($value)) {
				foreach ($value as $key2 => $value2) {
					if(is_array($value2)) {
						foreach ($value2 as $key3 => $value3) {
							if(is_array($value3)) {
								foreach ($value3 as $key4 => $value4) {
									$default_data[$key][$key2][$key3][$key4] = isset($data[$key][$key2][$key3][$key4]) && $data[$key][$key2][$key3][$key4] ? $data[$key][$key2][$key3][$key4] : $value4;
								}
							} else {
								$default_data[$key][$key2][$key3] = isset($data[$key][$key2][$key3]) && $data[$key][$key2][$key3] ? $data[$key][$key2][$key3] : $value3;
							}
						}
					} else {
						$default_data[$key][$key2] = isset($data[$key][$key2]) && $data[$key][$key2] ? $data[$key][$key2] : $value2;
					}
				}
			} else {
				$default_data[$key] = isset($data[$key]) && $data[$key] ? $data[$key] : $value;
			}
		}



		return $default_data;
	}

    /**
     * merge setting.
     *
     * @since    3.0.0
     */
    public function merge_setting( $base, $input, $final = array() )
    {
        foreach ( $base as $key => $value ) {
            if ( $value && is_array( $value ) && isset( $input[$key] ) && $input[$key] && is_array( $input[$key] ) ) {
                $final[$key] = $this->merge_setting( $value, $input[$key] );
            } else if ( ( ! is_array( $value ) && isset( $input[$key] ) && $input[$key] && ! is_array( $input[$key] ) ) || ( isset( $input[$key] ) && ( $input[$key] == '0' || $input[$key] == '1' || $input[$key] == 'false' || $input[$key] == 'true' || $input[$key] == 0 || $input[$key] == 1 || $input[$key] == false || $input[$key] == true ) ) ) {
                $final[$key] = $input[$key];
            } else {
                $final[$key] = $base[$key];
            }
        }
        return $final;
    }

}