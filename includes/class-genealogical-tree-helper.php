<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 */

namespace Zqe;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/includes
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Helper {

	/**
	 * It gets the full name of a member.
	 *
	 * @param int $member_id The ID of the member you want to get the full name of.
	 *
	 * @since    1.0.0
	 */
	public function get_full_name( $member_id ) {
		$names = get_post_meta( $member_id, 'names' ) ? get_post_meta( $member_id, 'names' ) : array(
			array(
				'name' => get_post_meta( $member_id, 'full_name', true ) ? get_post_meta( $member_id, 'full_name', true ) : '',
			),
		);

		foreach ( $names as $key => $value ) {
			if ( $value && ! is_array( $value ) ) {
				$names[ $key ] = array(
					'name' => $value,
				);
			}
		}

		foreach ( $names as $key => $value ) {
			if ( ! isset( $value['name'] ) ) {
				unset( $names[ $key ] );
			}
		}

		return $this->repear_full_name( esc_html( current( $names )['name'] ) );
	}

	/**
	 * It strips all tags, trims whitespace, and replaces slashes with spaces.
	 *
	 * @param string $name The name of the member.
	 *
	 * @return string      The name of the file without the extension.
	 *
	 * @since    1.0.0
	 */
	public function repear_full_name( $name ) {
		return wp_strip_all_tags( trim( str_replace( array( '/', '\\' ), array( ' ', '' ), $name ) ) );
	}

	/**
	 * It returns an array of all the individual events
	 *
	 * @return array An array of all the individual events.
	 *
	 * @since    1.0.0
	 */
	public function get_individual_events() {
		return array(
			'ADOP' => array(
				'type'  => 'ADOP',
				'title' => 'Adoption',
				'disc'  => 'Creation of a legally approved child-parent relationship that does not exist biologically.',
			),
			'BAPM' => array(
				'type'  => 'BAPM',
				'title' => 'Baptism',
				'disc'  => 'Baptism, performed in infancy or later. ( See also BAPL and CHR. )',
			),
			'BARM' => array(
				'type'  => 'BARM',
				'title' => 'Bar Mitzvah',
				'disc'  => 'The ceremonial event held when a Jewish boy reaches age 13.',
			),
			'BASM' => array(
				'type'  => 'BASM',
				'title' => 'Bas Mitzvah',
				'disc'  => 'The ceremonial event held when a Jewish girl reaches age 13, also known as “Bat Mitzvah.”',
			),
			'BIRT' => array(
				'type'  => 'BIRT',
				'title' => 'Birth',
				'disc'  => 'Entering into life.',
			),
			'BLES' => array(
				'type'  => 'BLES',
				'title' => 'Blessing',
				'disc'  => 'Bestowing divine care or intercession. Sometimes given in connection with a naming ceremony.',
			),
			'BURI' => array(
				'type'  => 'BURI',
				'title' => 'Burial',
				'disc'  => 'Disposing of the mortal remains of a deceased person.',
			),
			'CENS' => array(
				'type'  => 'CENS',
				'title' => 'Census',
				'disc'  => 'CENS	Periodic count of the population for a designated locality, such as a national or state census.',
			),
			'CHR'  => array(
				'type'  => 'CHR',
				'title' => 'Christening',
				'disc'  => 'Baptism or naming events for a child.',
			),
			'CHRA' => array(
				'type'  => 'CHRA',
				'title' => 'Adult Christening',
				'disc'  => 'Baptism or naming events for an adult person.',
			),
			'CONF' => array(
				'type'  => 'CONF',
				'title' => 'Confirmation',
				'disc'  => 'Conferring full church membership.',
			),
			'CREM' => array(
				'type'  => 'CREM',
				'title' => 'Cremation',
				'disc'  => 'Disposal of the remains of a person’s body by fire.',
			),
			'DEAT' => array(
				'type'  => 'DEAT',
				'title' => 'Ceath',
				'disc'  => 'Mortal life terminates.',
			),
			'EMIG' => array(
				'type'  => 'EMIG',
				'title' => 'Emigration',
				'disc'  => 'Leaving one’s homeland with the intent of residing elsewhere.',
			),
			'FCOM' => array(
				'type'  => 'FCOM',
				'title' => 'First Communion',
				'disc'  => 'The first act of sharing in the Lord’s supper as part of church worship.',
			),
			'GRAD' => array(
				'type'  => 'GRAD',
				'title' => 'Graduation',
				'disc'  => 'Awarding educational diplomas or degrees to individuals.',
			),
			'IMMI' => array(
				'type'  => 'IMMI',
				'title' => 'Immigration',
				'disc'  => 'Entering into a new locality with the intent of residing there.',
			),
			'NATU' => array(
				'type'  => 'NATU',
				'title' => 'Naturalization',
				'disc'  => 'Obtaining citizenship.',
			),
			'ORDN' => array(
				'type'  => 'ORDN',
				'title' => 'Ordination',
				'disc'  => 'Receiving authority to act in religious matters.',
			),
			'PROB' => array(
				'type'  => 'PROB',
				'title' => 'Probate',
				'disc'  => 'Judicial determination of the validity of a will. It may indicate several related court activities over several dates.',
			),
			'RETI' => array(
				'type'  => 'RETI',
				'title' => 'Retirement',
				'disc'  => 'Exiting an occupational relationship with an employer after a qualifying time period.',
			),
			'WILL' => array(
				'type'  => 'WILL',
				'title' => 'Will',
				'disc'  => 'A legal document treated as an event, by which a person disposes of his or her estate. It takes effect after death. The event date is the date the will was signed while the person was alive. ( See also PROB )',
			),
			'EVEN' => array(
				'type'  => 'EVEN',
				'title' => 'Generic Event',
				'disc'  => 'Generic Individual Event',
			),
		);
	}

	/**
	 * It returns an array of family events
	 *
	 * @return An array of all the events that are available for the family.
	 *
	 * @since    1.0.0
	 */
	public function get_family_events() {
		return array(

			'ANUL' => array(
				'type'  => 'ANUL',
				'title' => 'Annulment',
				'disc'  => 'Declaring a marriage void from the beginning ( never existed ).',
			),
			'CENS' => array(
				'type'  => 'CENS',
				'title' => 'Census',
				'disc'  => 'CENS Periodic count of the population for a designated locality, such as a national or state census.',
			),
			'DIV'  => array(
				'type'  => 'DIV',
				'title' => 'Divorce',
				'disc'  => 'Dissolving a marriage through civil action.',
			),
			'DIVF' => array(
				'type'  => 'DIVF',
				'title' => 'Divorce Filed',
				'disc'  => 'Filing for a divorce by a spouse.',
			),
			'ENGA' => array(
				'type'  => 'ENGA',
				'title' => 'Engagement',
				'disc'  => 'Recording or announcing an agreement between 2 people to become married.',
			),
			'MARB' => array(
				'type'  => 'MARB',
				'title' => 'Marriage Bann',
				'disc'  => 'Official public notice given that 2 people intend to marry.',
			),
			'MARC' => array(
				'type'  => 'MARC',
				'title' => 'Marriage Contract',
				'disc'  => 'Recording a formal agreement of marriage, including the prenuptial agreement in which marriage partners reach agreement about the property rights of 1 or both, securing property to their children.',
			),
			'MARL' => array(
				'type'  => 'MARL',
				'title' => 'Marriage License',
				'disc'  => 'Obtaining a legal license to marry.',
			),
			'MARR' => array(
				'type'  => 'MARR',
				'title' => 'Marriage',
				'disc'  => 'A legal, common-law, or customary event such as a wedding or marriage ceremony that joins 2 partners to create or extend a family unit.',
			),
			'MARS' => array(
				'type'  => 'MARS',
				'title' => 'Marriage Settlement',
				'disc'  => 'Creating an agreement between 2 people contemplating marriage, at which time they agree to release or modify property rights that would otherwise arise from the marriage.',
			),
			'EVEN' => array(
				'type'  => 'EVEN',
				'title' => 'Generic Event',
				'disc'  => 'Generic Family Event',
			),
		);
	}

	/**
	 * It searches through an array of events and returns the title of the event that matches the tag
	 *
	 * @param string $tag The tag you're looking for.
	 * @param array  $events An array of events.
	 *
	 * @return string The title of the event that matches the tag.
	 *
	 * @since    1.0.0
	 */
	public function search_for_tag( $tag, $events ) {
		foreach ( $events as $key => $val ) {
			if ( $val['type'] === $tag ) {
				return $val['title'];
			}
		}
		return null;
	}

	/**
	 * It returns an array of default values for the tree shortcode
	 *
	 * @return array An array of default values for the tree.
	 *
	 * @since    1.0.0
	 */
	public function tree_default_meta() {
		return array(
			'family'                     => null,
			'root'                       => null,
			'root_highlight'             => 'on',
			'style'                      => '1',
			'layout'                     => 'vr',
			'ajax'                       => false,
			'name'                       => 'full',
			'birt'                       => 'full',
			'birt_hide_alive'            => false,
			'deat'                       => 'full',
			'gender'                     => 'full',
			'sibling_order'              => 'default',
			'generation'                 => false,
			'generation_number'          => -1,
			'generation_number_ancestor' => -1,
			'generation_start_from'      => 1,
			'ancestor'                   => false,
			'popup'                      => false,
			'female_tree'                => false,
			'hide_spouse'                => false,
			'hide_un_spouse'             => false,
			'collapsible_family_root'    => false,
			'collapsible_family_spouse'  => false,
			'collapsible_family_onload'  => false,
			'treelink'                   => false,
			'background'                 => array(
				'color' => 'rgba( 0, 0, 0, .05 )',
			),
			'marr_icon'                  => false,
			'container'                  => array(
				'background' => array(
					'color' => '#ffffff',
					'image' => 'linear-gradient( 90deg, #f0f0f0 1px, transparent 0 ), linear-gradient( 180deg, #f0f0f0 1px, transparent 0 )',
					'size'  => '2.14785rem 2.14785rem',
				),
				'border'     => array(
					'style'  => 'solid',
					'color'  => '#000000',
					'width'  => '1px',
					'radius' => '0px',
				),
			),
			'box'                        => array(
				'layout'     => 'vr',
				'width'      => '90px',
				'background' => array(
					'color' => array(
						'male'   => '#daeef3',
						'female' => '#ffdbe0',
						'other'  => '#ffffff',
					),
				),
				'border'     => array(
					'style'  => 'solid',
					'color'  => array(
						'male'   => '#000000',
						'female' => '#000000',
						'other'  => '#000000',
					),
					'width'  => '1px',
					'radius' => '1px',
				),
			),
			'thumb'                      => array(
				'show'   => false,
				'width'  => '80px',
				'border' => array(
					'style'  => 'solid',
					'color'  => array(
						'male'   => '#000000',
						'female' => '#000000',
						'other'  => '#000000',
					),
					'width'  => '1px',
					'radius' => '1px',
				),
			),
			'line'                       => array(
				'border' => array(
					'style'  => 'solid',
					'color'  => '#000000',
					'width'  => '1px',
					'radius' => '1px',
				),
			),
			'name_text'                  => array(
				'font_family' => 'inherit',
				'font_size'   => '12px',
				'font_style'  => 'inherit',
				'font_weight' => 'regular',
				'color'       => 'inherit',
				'align'       => 'center',
			),
			'other_text'                 => array(
				'font_family' => 'inherit',
				'font_size'   => '10px',
				'font_style'  => 'inherit',
				'font_weight' => 'regular',
				'color'       => 'inherit',
				'align'       => 'center',
			),
		);
	}

	/**
	 * It returns an array of border styles
	 *
	 * @return array An array of border styles.
	 *
	 * @since    1.0.0
	 */
	public function border_style() {
		return array(
			'dotted' => __( 'Dotted', 'genealogical-tree' ),
			'dashed' => __( 'Dashed', 'genealogical-tree' ),
			'solid'  => __( 'Solid', 'genealogical-tree' ),
			'double' => __( 'Double', 'genealogical-tree' ),
			'groove' => __( 'Groove', 'genealogical-tree' ),
			'ridge'  => __( 'Ridge', 'genealogical-tree' ),
			'inset'  => __( 'Inset', 'genealogical-tree' ),
			'outset' => __( 'Outset', 'genealogical-tree' ),
			'none'   => __( 'None', 'genealogical-tree' ),
			'hidden' => __( 'Hidden', 'genealogical-tree' ),
		);
	}

	/**
	 * It takes two events, strips out the date, converts the date to a timestamp, converts the timestamp
	 * to a string, splits the string into an array, converts the array elements to integers, and then
	 * compares the integers
	 *
	 * @param string $event_prev The previous event in the array.
	 * @param string $event_next The next event in the array.
	 *
	 * @return array the difference between the two dates.
	 *
	 * @since    1.0.0
	 */
	public function sort_events( $event_prev, $event_next ) {

		$birt_prev = str_replace( array( 'BEF', 'EST', 'AFT', 'FROM', 'TO', 'ABT' ), array( '', '', '', '', '', '' ), $event_prev['date'] );
		$birt_next = str_replace( array( 'BEF', 'EST', 'AFT', 'FROM', 'TO', 'ABT' ), array( '', '', '', '', '', '' ), $event_next['date'] );

		$birt_prev = strtotime( $birt_prev );
		$birt_next = strtotime( $birt_next );

		$birt_prev = date( 'Y-d-m', $birt_prev );
		$birt_next = date( 'Y-d-m', $birt_next );

		$event_prev = explode( '-', $birt_prev );
		$event_next = explode( '-', $birt_next );

		if ( isset( $event_prev[0] ) ) {
			if ( ! is_numeric( $event_prev[0] ) ) {
				$event_prev[0] = intval( $event_prev[0] );
			}
		} else {
			$event_prev[0] = 0;
		}

		if ( isset( $event_prev[1] ) ) {
			if ( ! is_numeric( $event_prev[1] ) ) {
				$event_prev[1] = intval( $event_prev[1] );
			}
		} else {
			$event_prev[1] = 0;
		}

		if ( isset( $event_prev[2] ) ) {
			if ( ! is_numeric( $event_prev[2] ) ) {
				$event_prev[2] = intval( $event_prev[2] );
			}
		} else {
			$event_prev[2] = 0;
		}

		if ( isset( $event_next[0] ) ) {
			if ( ! is_numeric( $event_next[0] ) ) {
				$event_next[0] = intval( $event_next[0] );
			}
		} else {
			$event_next[0] = 0;
		}

		if ( isset( $event_next[1] ) ) {
			if ( ! is_numeric( $event_next[1] ) ) {
				$event_next[1] = intval( $event_next[1] );
			}
		} else {
			$event_next[1] = 0;
		}

		if ( isset( $event_next[2] ) ) {
			if ( ! is_numeric( $event_next[2] ) ) {
				$event_next[2] = intval( $event_next[2] );
			}
		} else {
			$event_next[2] = 0;
		}

		$year_distance = $event_prev[0] - $event_next[0];

		if ( 0 !== $year_distance ) {
			return $year_distance;
		} else {
			$month_distance = $event_prev[1] - $event_next[1];
			if ( 0 !== $month_distance ) {
				return $month_distance;
			} else {
				$day_distance = $event_prev[2] - $event_next[2];
				return $day_distance;
			}
		}
	}

	/**
	 * It sorts siblings by birth date.
	 *
	 * @param string $member_prev The ID of the previous sibling.
	 * @param string $member_next The ID of the next sibling.
	 *
	 * @return array The difference between the two dates.
	 *
	 * @since    1.0.0
	 */
	public function sort_siblings( $member_prev, $member_next ) {
		$event_prev = get_post_meta( $member_prev, 'event', true );

		$birt_prev = null;

		if ( isset( $event_prev['birt'] ) && $event_prev['birt'] ) {
			if ( null !== current( $event_prev['birt'] ) && current( $event_prev['birt'] ) ) {
				$birt_prev = current( $event_prev['birt'] )['date'];
			}
		}

		$event_next = get_post_meta( $member_next, 'event', true );

		$birt_next = null;

		if ( isset( $event_next['birt'] ) && $event_next['birt'] ) {
			if ( null !== current( $event_next['birt'] ) && current( $event_next['birt'] ) ) {
				$birt_next = current( $event_next['birt'] )['date'];
			}
		}

		$birt_prev = strtotime( $birt_prev );
		$birt_next = strtotime( $birt_next );

		$birt_prev = date( 'Y-d-m', $birt_prev );
		$birt_next = date( 'Y-d-m', $birt_next );

		$member_prev = explode( '-', $birt_prev );
		$member_next = explode( '-', $birt_next );

		if ( isset( $member_prev[0] ) ) {
			if ( ! is_numeric( $member_prev[0] ) ) {
				$member_prev[0] = intval( $member_prev[0] );
			}
		} else {
			$member_prev[0] = 0;
		}

		if ( isset( $member_prev[1] ) ) {
			if ( ! is_numeric( $member_prev[1] ) ) {
				$member_prev[1] = intval( $member_prev[1] );
			}
		} else {
			$member_prev[1] = 0;
		}

		if ( isset( $member_prev[2] ) ) {
			if ( ! is_numeric( $member_prev[2] ) ) {
				$member_prev[2] = intval( $member_prev[2] );
			}
		} else {
			$member_prev[2] = 0;
		}

		if ( isset( $member_next[0] ) ) {
			if ( ! is_numeric( $member_next[0] ) ) {
				$member_next[0] = intval( $member_next[0] );
			}
		} else {
			$member_next[0] = 0;
		}

		if ( isset( $member_next[1] ) ) {
			if ( ! is_numeric( $member_next[1] ) ) {
				$member_next[1] = intval( $member_next[1] );
			}
		} else {
			$member_next[1] = 0;
		}

		if ( isset( $member_next[2] ) ) {
			if ( ! is_numeric( $member_next[2] ) ) {
				$member_next[2] = intval( $member_next[2] );
			}
		} else {
			$member_next[2] = 0;
		}

		$year_distance = $member_prev[0] - $member_next[0];

		if ( 0 !== $year_distance ) {
			return $year_distance;
		} else {
			$month_distance = $member_prev[1] - $member_next[1];
			if ( 0 !== $month_distance ) {
				return $month_distance;
			} else {
				$day_distance = $member_prev[2] - $member_next[2];
				return $day_distance;
			}
		}
	}

	/**
	 * It merges two arrays.
	 *
	 * @param array $base The base array to merge into.
	 * @param array $input The array you want to merge into the base array.
	 * @param bool  $default If the input is empty, return the default.
	 *
	 * @return array the base array with the input array merged into it.
	 */
	public function tree_merge( array $base, array $input = array(), bool $default = false ) {
		if ( empty( $tree ) && true === $default ) {
			return $base;
		}
		foreach ( $base as $key => $value ) {
			if ( is_array( $value ) ) {
				$base[ $key ] = $this->tree_merge( $value, $input[ $key ] ? $input[ $key ] : array() );
			} else {
				$base[ $key ] = $input[ $key ] ? $input[ $key ] : '';
			}
		}
		return $base;
	}

}
