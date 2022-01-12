<?php

namespace Genealogical_Tree\Genealogical_Tree_Public;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/public
 * @author     ak devs <akdevs.fr@gmail.com>
 */
class Genealogical_Tree_Public
{
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Style_1 ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Style_2 ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Style_3 ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Style_4 ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Style_5 ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Single_Member_info ;
    use  \Genealogical_Tree\Genealogical_Tree_Public\Traits\Genealogical_Tree_Ind_Style ;
    use  \Genealogical_Tree\Includes\Traits\Genealogical_Tree_Data ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-public.min.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Genealogical_Tree_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Genealogical_Tree_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_script(
            $this->plugin_name . '-panzoom',
            plugin_dir_url( __FILE__ ) . 'js/panzoom.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-public.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );
        $gt_plan = 'free';
        if ( defined( 'GENEALOGICAL_DEV' ) && GENEALOGICAL_DEV ) {
        }
        $gtObj = array(
            'gt_dir_url'  => GENEALOGICAL_TREE_DIR_URL,
            'gt_dir_path' => GENEALOGICAL_TREE_DIR_PATH,
            'gt_site_url' => site_url(),
            'gt_rest_url' => rest_url(),
            'gt_ajax_url' => admin_url( 'admin-ajax.php' ),
            'gt_plan'     => $gt_plan,
        );
        if ( defined( 'GENEALOGICAL_DEV' ) && GENEALOGICAL_DEV ) {
            $gtObj['dev'] = true;
        }
        wp_localize_script( $this->plugin_name, 'gtObj', $gtObj );
    }
    
    /**
     * Filter callback for displaing data into single page
     *
     * @since    1.0.0
     */
    public function data_in_single_page( $content )
    {
        $html = '';
        global  $post ;
        $post_id = $post->ID;
        if ( $post->post_type == 'gt-member' ) {
            $html = $this->single_member_info( $post_id );
        }
        if ( $post->post_type == 'gt-tree' ) {
            $html = do_shortcode( '[tree id=' . $post_id . ']' );
        }
        return $html . $content;
    }
    
    /**
     * Get root by family group
     *
     * @since    1.0.0
     */
    public function get_root_by_family_group( $family_group )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'tax_query'      => array( array(
            'taxonomy' => 'gt-family-group',
            'field'    => 'term_id',
            'terms'    => $family_group,
        ) ),
        ) );
        $aloneArray = array();
        $finalArray = array();
        $getdualNew = array();
        // find who dont have father and mother.
        $possibles = array();
        if ( $query->posts ) {
            foreach ( $query->posts as $keym => $member ) {
                $famc = get_post_meta( $member->ID, 'famc' );
                $fams = get_post_meta( $member->ID, 'fams' );
                if ( $fams && !$famc ) {
                    foreach ( $fams as $keyx => $fam ) {
                        $father = get_post_meta( $fam, 'father', true );
                        $mother = get_post_meta( $fam, 'mother', true );
                        
                        if ( $mother && $father ) {
                            $spouse = ( $mother == $member->ID ? $father : $mother );
                            $famc = get_post_meta( $spouse, 'famc' );
                            
                            if ( !$famc ) {
                                $getdualNew[$father . $mother][0] = $father;
                                $getdualNew[$father . $mother][1] = $mother;
                            }
                        
                        } else {
                            if ( $father ) {
                                $finalArray[$father]['husb'] = $father;
                            }
                            if ( $mother ) {
                                $finalArray[$mother]['wife'] = $mother;
                            }
                        }
                    
                    }
                }
                if ( !$famc && !$fams ) {
                    $aloneArray[] = $member->ID;
                }
            }
        }
        $data = array(
            'alone'  => array_unique( $aloneArray ),
            'single' => $finalArray,
            'dual'   => $getdualNew,
        );
        return $data;
    }
    
    /**
     * Get default root
     *
     * @since    1.0.0
     */
    public function get_default_root( $family )
    {
        $tree_root = $this->get_root_by_family_group( $family );
        if ( $tree_root['single'] ) {
            foreach ( $tree_root['single'] as $key => $value ) {
                if ( $value['husb'] ) {
                    return $value['husb'];
                }
                if ( $value['wife'] ) {
                    return $value['wife'];
                }
            }
        }
        if ( $tree_root['dual'] ) {
            foreach ( $tree_root['dual'] as $key => $value ) {
                return $value[0];
            }
        }
        return null;
    }
    
    /**
     * Get  families by root
     *
     * @since    1.0.0
     */
    public function get_ancestors_by_root( $root, $setting = array() )
    {
    }
    
    /**
     * Get  families by root
     *
     * @since    1.0.0
     */
    public function get_families_by_root( $root, $setting = array() )
    {
        $fams = ( get_post_meta( $root, 'fams' ) ? get_post_meta( $root, 'fams' ) : array() );
        $fams = array_unique( $fams );
        if ( empty($fams) ) {
            return [];
        }
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'post__in'       => $fams,
        ) );
        $families = $query->posts;
        foreach ( $families as $keyx => $family ) {
            $family->father = get_post_meta( $family->ID, 'father', true );
            $family->mother = get_post_meta( $family->ID, 'mother', true );
            $family->root = $root;
            $family->spouse = ( $root == $family->father ? $family->mother : (( $family->father ? $family->father : false )) );
            $chill_ids = get_post_meta( $family->ID, 'chills' );
            sort( $chill_ids );
            if ( isset( $setting->sibling_order ) && $setting->sibling_order == 'oldest' ) {
                uasort( $chill_ids, array( $this, 'sort_siblings' ) );
            }
            
            if ( isset( $setting->sibling_order ) && $setting->sibling_order == 'youngest' ) {
                uasort( $chill_ids, array( $this, 'sort_siblings' ) );
                $chill_ids = array_reverse( $chill_ids );
            }
            
            $family->chill = $chill_ids;
            if ( !$family->chill && !$family->spouse ) {
                unset( $families[$keyx] );
            }
        }
        return $families;
    }
    
    /**
     * Get all members of family by group ID
     *
     * @since    1.0.0
     */
    public function get_all_members_of_family( $family_id )
    {
        $query = new \WP_Query( array(
            'post_type'      => 'gt-member',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => array( array(
            'taxonomy' => 'gt-family-group',
            'field'    => 'term_id',
            'terms'    => $family_id,
        ) ),
        ) );
        return $query->posts;
    }
    
    /**
     * Get all members of family by group ID
     *
     * @since    1.0.0
     */
    public function clean_css( $inline_css )
    {
        $inline_css = str_ireplace( array(
            "\r\n",
            "\r",
            "\n",
            "\t"
        ), '', $inline_css );
        $inline_css = preg_replace( "/\\s+/", ' ', $inline_css );
        return trim( $inline_css );
    }
    
    public function generateRandomString( $length = 10 )
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen( $characters );
        $randomString = '';
        for ( $i = 0 ;  $i < $length ;  $i++ ) {
            $randomString .= $characters[rand( 0, $charactersLength - 1 )];
        }
        return $randomString;
    }
    
    /**
     * Display tree style
     *
     * @since    1.0.0
     */
    public function display_tree_style( $tree, $setting )
    {
        $class = '';
        $html = '';
        include plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-google-fonts.php';
        
        if ( gt_fs()->is_not_paying() && !gt_fs()->is_trial() ) {
            if ( isset( $setting->style ) ) {
                $setting->style = '1';
            }
            if ( isset( $setting->ajax ) ) {
                $setting->ajax = false;
            }
            if ( isset( $setting->ancestor ) ) {
                $setting->ancestor = false;
            }
            if ( isset( $setting->birt_hide_alive ) ) {
                $setting->birt_hide_alive = false;
            }
            if ( isset( $setting->sibling_order ) ) {
                $setting->sibling_order = 'default';
            }
            if ( isset( $setting->generation ) ) {
                $setting->generation = false;
            }
            if ( isset( $setting->popup ) ) {
                $setting->popup = false;
            }
            if ( isset( $setting->female_tree ) ) {
                $setting->female_tree = false;
            }
            if ( isset( $setting->hide_spouse ) ) {
                $setting->hide_spouse = false;
            }
            if ( isset( $setting->hide_un_spouse ) ) {
                $setting->hide_un_spouse = false;
            }
            if ( isset( $setting->collapsible_family_root ) ) {
                $setting->collapsible_family_root = false;
            }
            if ( isset( $setting->collapsible_family_spouse ) ) {
                $setting->collapsible_family_spouse = false;
            }
            if ( isset( $setting->box->layout ) ) {
                $setting->box->layout = 'vr';
            }
            if ( isset( $setting->box->border->width ) ) {
                $setting->box->border->width = '1px';
            }
            if ( isset( $setting->box->border->style ) ) {
                $setting->box->border->style = 'solid';
            }
            if ( isset( $setting->thumb->border->width ) ) {
                $setting->thumb->border->width = '1px';
            }
            if ( isset( $setting->thumb->border->style ) ) {
                $setting->thumb->border->style = 'solid';
            }
            if ( isset( $setting->line->border->width ) ) {
                $setting->line->border->width = '1px';
            }
            if ( isset( $setting->thumb->border->style ) ) {
                $setting->thumb->border->style = 'solid';
            }
            if ( isset( $setting->name_text->font_family ) ) {
                $setting->name_text->font_family = 'inherit';
            }
            if ( isset( $setting->other_text->font_family ) ) {
                $setting->other_text->font_family = 'inherit';
            }
        }
        
        $rand_id = $this->generateRandomString();
        include plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-settings.php';
        if ( isset( $inline_style ) ) {
            $html .= $inline_style;
        }
        $html .= '
		<div class="gt-zoom-contorl">

			<button class="gt-expand-compress"><!--
				--><img class="gt-expand-icon" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/expand-arrows-alt.svg"><!--
				--><img class="gt-compress-icon"  src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/compress-arrows-alt.svg"><!--
			--></button><!--
			--><button class="gt-desk-zoom">SHIFT + MOUSEWHEEL to ZOOM</button>
		</div>
		<input class="current-ratio" type="hidden" >
		';
        if ( isset( $setting->layout ) && $setting->layout ) {
            $class .= ' gt-tree-' . $setting->layout . ' ';
        }
        if ( isset( $setting->popup ) && $setting->popup == 'on' ) {
            $class .= ' gt-tree-popup ';
        }
        $class .= ' gt-tree-collapse ';
        
        if ( isset( $setting->ajax ) && $setting->ajax == 'on' ) {
            $html .= '<div id="famTree" class="' . $rand_id . ' gt-tree ' . $class . '" data-setting=\'' . json_encode( $setting ) . '\' data-id="' . $tree . '"></div>';
        } else {
            $html .= '<div id="famTree" class="' . $rand_id . ' gt-tree ' . $class . '">';
            if ( $setting->style == '1' ) {
                $html .= $this->display_tree_style1( $tree, $setting );
            }
            $html .= '</div>';
        }
        
        if ( gt_fs()->is_not_paying() && !gt_fs()->is_trial() ) {
            $html .= '<div style="padding:10px;text-align:right;position: absolute;right: 0;bottom: 0;">Powered By <a href="https://www.devs.family/genealogical-tree">Genealogical Tree</a></div>';
        }
        $container_background_color = $setting->container->background->color;
        $container_background_image = $setting->container->background->image;
        $container_background_size = $setting->container->background->size;
        $container_border_width = $setting->container->border->width;
        $container_border_style = $setting->container->border->style;
        $container_border_color = $setting->container->border->color;
        $container_border_radius = $setting->container->border->radius;
        $gtx = '';
        if ( isset( $_GET['expand'] ) ) {
            $gtx = 'gt-expand-compress-toggle';
        }
        return '
		<div id="gt-container" class="gt-container">
			<div id="gt-content" class="gt-content ' . $gtx . '" style="background-color:' . $container_background_color . '; background-image:' . $container_background_image . '; background-size:' . $container_background_size . '; border:' . $container_border_width . ' ' . $container_border_style . ' ' . $container_border_color . '; border-radius:' . $container_border_radius . ';">
				' . $html . '
			</div>
		</div>';
    }
    
    /**
     * Get display tree for shortcode
     *
     * @since    1.0.0
     */
    public function display_formated_tree( $atts, $content = null )
    {
        $opt = array(
            'id' => null,
        );
        $data = shortcode_atts( $opt, $atts );
        if ( !$data['id'] ) {
            return 'No Data. Tree ID required.';
        }
        $data = get_post_meta( $data['id'], 'tree', true );
        if ( !isset( $data['family'] ) || isset( $data['family'] ) && !$data['family'] ) {
            return 'No data. Family ID required';
        }
        if ( !isset( $data['root'] ) || isset( $data['root'] ) && !$data['root'] ) {
            $data['root'] = $this->get_default_root( $data['family'] );
        }
        if ( isset( $_GET['root'] ) ) {
            $data['root'] = $_GET['root'];
        }
        if ( !isset( $data['root'] ) || isset( $data['root'] ) && !$data['root'] ) {
            return 'No data, Root ID required';
        }
        if ( $data['root'] ) {
            $root = $data['root'];
        }
        $data = $this->tree_combine_meta( $data );
        $setting = json_decode( json_encode( $data ) );
        return $this->display_tree_style( $root, $setting );
    }
    
    /**
     * Get display tree for shortcode
     *
     * @since    1.0.0
     */
    public function display_tree( $atts, $content = null )
    {
        $data = array(
            'image'           => 'true',
            'layout'          => 'gt-vr',
            'class'           => '',
            'family'          => null,
            'root'            => null,
            'style'           => '1',
            'ajax'            => false,
            'name'            => 'full',
            'birt'            => 'full',
            'birt_hide_alive' => false,
            'deat'            => 'full',
            'gender'          => 'full',
            'sibling_order'   => 'default',
            'generation'      => false,
            'ancestor'        => false,
        );
        $atts = shortcode_atts( $data, $atts );
        $data = $this->tree_combine_meta( $atts );
        if ( isset( $atts['image'] ) && $atts['image'] == 'true' ) {
            $data['thumb']['show'] = true;
        }
        if ( isset( $atts['layout'] ) && $atts['layout'] ) {
            $data['box']['layout'] = $atts['layout'];
        }
        if ( isset( $atts['class'] ) && $atts['class'] ) {
            $data['class'] = $atts['class'];
        }
        if ( gt_fs()->is_not_paying() && !gt_fs()->is_trial() ) {
            $data['ajax'] = false;
        }
        if ( !$data['family'] ) {
            return 'No data';
        }
        if ( $data['family'] ) {
            $root = $this->get_default_root( $data['family'] );
        }
        if ( isset( $_GET['root'] ) ) {
            $data['root'] = $_GET['root'];
        }
        if ( $data['root'] ) {
            $root = $data['root'];
        }
        if ( !$root ) {
            return 'No data';
        }
        $setting = json_decode( json_encode( $data ) );
        return $this->display_tree_style( $root, $setting );
    }
    
    /**
     * Get display members for shortcode
     *
     * @since    1.0.0
     */
    public function display_tree_list( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'family' => null,
        ), $atts );
        if ( !$data['family'] ) {
            return 'No data';
        }
        $family_group_id = $data['family'];
        $tree_root = $this->get_root_by_family_group( $family_group_id );
        if ( !$tree_root['single'] && !$tree_root['dual'] ) {
            return 'No root';
        }
        $post_id = get_term_meta( $family_group_id, 'tree_page', true );
        $html = '';
        $html .= '
		<div class="gt-tree-list-pub">';
        if ( $tree_root['single'] ) {
            foreach ( $tree_root['single'] as $key => $value ) {
                if ( isset( $value['husb'] ) && $value['husb'] ) {
                    $html .= '
					<div>
						<a href="' . get_the_permalink( $post_id ) . '?root=' . $value['husb'] . '">
							' . get_the_title( $value['husb'] ) . '
						</a>
					</div>';
                }
                if ( isset( $value['wife'] ) && $value['wife'] ) {
                    $html .= '
					<div>
						<a href="' . get_the_permalink( $post_id ) . '?root=' . $value['wife'] . '">
							' . get_the_title( $value['wife'] ) . '
						</a>
					</div>';
                }
            }
        }
        if ( $tree_root['dual'] ) {
            foreach ( $tree_root['dual'] as $key => $value ) {
                $html .= '
				<div>
					<a href="' . get_the_permalink( $post_id ) . '?root=' . $value[0] . '">
						' . get_the_title( $value[0] ) . ' and ' . get_the_title( $value[1] ) . '
					</a>
				</div>';
            }
        }
        
        if ( $tree_root['alone'] ) {
            $html .= '<h4>Alone</h4>';
            foreach ( $tree_root['alone'] as $key => $value ) {
                $html .= '
				<div>
					<a href="' . get_the_permalink( $post_id ) . '?root=' . $value . '">
						' . get_the_title( $value ) . '
					</a>
				</div>';
            }
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Get display members for shortcode
     *
     * @since    1.0.0
     */
    public function display_members( $atts, $content = null )
    {
        $html = '';
        $html .= '<div class="gt-members-public">';
        $data = shortcode_atts( array(
            'family' => null,
            'ids'    => null,
        ), $atts );
        
        if ( $data['family'] ) {
            $members = $this->get_all_members_of_family( $data['family'] );
            
            if ( $members ) {
                $html .= '<ul>';
                foreach ( $members as $key => $member ) {
                    $html .= '<li>';
                    $html .= '<h3> # ' . get_the_title( $member ) . '</h3>';
                    $html .= $this->single_member_info( $member );
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        
        }
        
        
        if ( $data['ids'] ) {
            $members = explode( ',', $data['ids'] );
            
            if ( $members ) {
                $html .= '<ul>';
                foreach ( $members as $key => $member ) {
                    $html .= '<li>';
                    $html .= $this->single_member_info( $member );
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        
        }
        
        $html .= '</div>';
        return $html;
    }
    
    /**
     * Get display members for shortcode
     *
     * @since    1.0.0
     */
    public function display_member( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'id' => null,
        ), $atts );
        if ( !$data['id'] ) {
            return 'Member ID required';
        }
        if ( get_post( $data['id'] ) ) {
            return 'Member ID required';
        }
        $member = $data['id'];
        return $this->single_member_info( $member );
    }
    
    /**
     * Sort siblings by birth date
     *
     * @since    1.0.0
     */
    public function sort_siblings( $memberPrev, $memberNext )
    {
        $eventPrev = get_post_meta( $memberPrev, 'event', true );
        $birtPrev = null;
        if ( isset( $eventPrev['birt'] ) && $eventPrev['birt'] ) {
            if ( null !== current( $eventPrev['birt'] ) && current( $eventPrev['birt'] ) ) {
                $birtPrev = current( $eventPrev['birt'] )['date'];
            }
        }
        $eventNext = get_post_meta( $memberNext, 'event', true );
        $birtNext = null;
        if ( isset( $eventNext['birt'] ) && $eventNext['birt'] ) {
            if ( null !== current( $eventNext['birt'] ) && current( $eventNext['birt'] ) ) {
                $birtNext = current( $eventNext['birt'] )['date'];
            }
        }
        $birtPrev = strtotime( $birtPrev );
        $birtNext = strtotime( $birtNext );
        $birtPrev = date( 'Y-d-m', $birtPrev );
        $birtNext = date( 'Y-d-m', $birtNext );
        $memberPrev = explode( '-', $birtPrev );
        $memberNext = explode( '-', $birtNext );
        
        if ( isset( $memberPrev[0] ) ) {
            if ( !is_numeric( $memberPrev[0] ) ) {
                $memberPrev[0] = intval( $memberPrev[0] );
            }
        } else {
            $memberPrev[0] = 0;
        }
        
        
        if ( isset( $memberPrev[1] ) ) {
            if ( !is_numeric( $memberPrev[1] ) ) {
                $memberPrev[1] = intval( $memberPrev[1] );
            }
        } else {
            $memberPrev[1] = 0;
        }
        
        
        if ( isset( $memberPrev[2] ) ) {
            if ( !is_numeric( $memberPrev[2] ) ) {
                $memberPrev[2] = intval( $memberPrev[2] );
            }
        } else {
            $memberPrev[2] = 0;
        }
        
        
        if ( isset( $memberNext[0] ) ) {
            if ( !is_numeric( $memberNext[0] ) ) {
                $memberNext[0] = intval( $memberNext[0] );
            }
        } else {
            $memberNext[0] = 0;
        }
        
        
        if ( isset( $memberNext[1] ) ) {
            if ( !is_numeric( $memberNext[1] ) ) {
                $memberNext[1] = intval( $memberNext[1] );
            }
        } else {
            $memberNext[1] = 0;
        }
        
        
        if ( isset( $memberNext[2] ) ) {
            if ( !is_numeric( $memberNext[2] ) ) {
                $memberNext[2] = intval( $memberNext[2] );
            }
        } else {
            $memberNext[2] = 0;
        }
        
        $yearDistance = $memberPrev[0] - $memberNext[0];
        
        if ( $yearDistance != 0 ) {
            return $yearDistance;
        } else {
            $monthDistance = $memberPrev[1] - $memberNext[1];
            
            if ( $monthDistance != 0 ) {
                return $monthDistance;
            } else {
                $dayDistance = $memberPrev[2] - $memberNext[2];
                return $dayDistance;
            }
        
        }
    
    }
    
    public function gt_user_registration()
    {
        $html = '';
        
        if ( isset( $_POST['gt-ur-submit'] ) ) {
            if ( isset( $_POST['user_login'] ) && is_string( $_POST['user_login'] ) ) {
                $user_login = wp_unslash( $_POST['user_login'] );
            }
            if ( isset( $_POST['user_email'] ) && is_string( $_POST['user_email'] ) ) {
                $user_email = wp_unslash( $_POST['user_email'] );
            }
            $errors = register_new_user( $user_login, $user_email );
            
            if ( is_wp_error( $errors ) && $errors->errors ) {
                $html .= '<div id="login_error">';
                foreach ( $errors->errors as $key => $error ) {
                    $html .= current( $error );
                    $html .= '<br>';
                }
                $html .= '</div>';
            } else {
                $user_id = $errors;
                $html .= '<p id="reg_passmail"> A confirmation email has been sent to your registered email address. </p>';
            }
        
        }
        
        if ( !isset( $user_id ) ) {
            $html .= '
			<form name="registerform" id="registerform" action="" method="post">
				<p>
					<label for="user_login">Username</label>
					<input type="text" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off">
				</p>
				<p>
					<label for="user_email">Email</label>
					<input type="text" name="user_email" id="user_email" class="input" value="" size="25">
				</p>
				<p id="reg_passmail"> Registration confirmation will be emailed to you. </p>
				<br class="clear">
				<input type="hidden" name="role" value="gt_member">
				<p class="submit">
					<input type="submit" name="gt-ur-submit" id="gt-ur-submit" class="button button-primary button-large" value="Register">
				</p>
			</form>';
        }
        return $html;
    }
    
    /**
     * Find families
     *
     * @since    2.1.1
     */
    public function findFamilyNew( $member_id, $key )
    {
        $family_ids = ( get_post_meta( $member_id, $key ) ? get_post_meta( $member_id, $key ) : array() );
        if ( empty($family_ids) ) {
            return array();
        }
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'post__in'       => $family_ids,
        ) );
        return $query->posts;
    }

}