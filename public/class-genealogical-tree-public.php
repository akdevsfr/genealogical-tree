<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wordpress.org/plugins/genealogical-tree
 * @since      1.0.0
 *
 * @package    Genealogical_Tree
 * @subpackage Genealogical_Tree/public
 */
namespace Zqe;

use function  GuzzleHttp\json_decode ;
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
    use  \Zqe\Traits\Genealogical_Tree_Style_1 ;
    use  \Zqe\Traits\Genealogical_Tree_Style_2 ;
    use  \Zqe\Traits\Genealogical_Tree_Style_3 ;
    use  \Zqe\Traits\Genealogical_Tree_Style_4 ;
    use  \Zqe\Traits\Genealogical_Tree_Style_5 ;
    use  \Zqe\Traits\Genealogical_Tree_Single_Member_Info ;
    use  \Zqe\Traits\Genealogical_Tree_Ind_Style ;
    use  \Zqe\Traits\Genealogical_Tree_Ind_Style_Unknown ;
    use  \Zqe\Traits\Genealogical_Tree_Style_Alter ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      Genealogical_Tree  $plugin  plugin.
     */
    private  $plugin ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin     The name of the plugin.
     */
    public function __construct( $plugin )
    {
        $this->plugin = $plugin;
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
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'css/genealogical-tree-public.min.css',
            array(),
            $this->plugin->version,
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
            $this->plugin->name . '-panzoom',
            plugin_dir_url( __FILE__ ) . 'js/panzoom.min.js',
            array( 'jquery' ),
            $this->plugin->version,
            true
        );
        wp_enqueue_script(
            $this->plugin->name,
            plugin_dir_url( __FILE__ ) . 'js/genealogical-tree-public.min.js',
            array( 'jquery' ),
            $this->plugin->version,
            true
        );
        $gt_plan = 'free';
        $gt_obj = array(
            'gt_dir_url'  => GENEALOGICAL_TREE_DIR_URL,
            'gt_dir_path' => GENEALOGICAL_TREE_DIR_PATH,
            'gt_site_url' => site_url(),
            'gt_rest_url' => rest_url(),
            'gt_ajax_url' => admin_url( 'admin-ajax.php' ),
            'gt_plan'     => $gt_plan,
            'GEN'         => __( 'GEN:', 'genealogical-tree' ),
        );
        if ( defined( 'GENEALOGICAL_DEV' ) ) {
            $gt_obj['dev'] = true;
        }
        wp_localize_script( $this->plugin->name, 'gt_obj', $gt_obj );
    }
    
    /**
     * Function for `gt_member_gallery_images`
     *
     * @param int $post_id post_id.
     *
     * @since    2.1.1
     */
    public function gt_member_gallery_images( $post_id )
    {
        $value = get_post_meta( $post_id, 'some_custom_gallery', true );
        $images = get_posts( array(
            'post_type'      => 'attachment',
            'orderby'        => 'post__in',
            'order'          => 'ASC',
            'post__in'       => explode( ',', $value ),
            'numberposts'    => -1,
            'post_mime_type' => 'image',
        ) );
        
        if ( !empty($images) ) {
            ?>
			<div>
				<ul class="gt-member-gallery-images">
					<?php 
            foreach ( $images as $image ) {
                ?>
					<li data-id="<?php 
                echo  esc_attr( $image->ID ) ;
                ?>">
						<span>
							<img src="<?php 
                echo  esc_attr( wp_get_attachment_image_src( $image->ID, array( 80, 80 ) )[0] ) ;
                ?>">
						</span>
					</li>
					<?php 
            }
            ?>
				</ul>
				<div style="clear:both"></div>
			</div>
			<?php 
        }
    
    }
    
    /**
     * Function for `get_tree_link`
     *
     * @param  mixed $post_id post_id.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function get_tree_link( $post_id )
    {
        $gt_family_group = get_the_terms( $post_id, 'gt-family-group' );
        if ( $gt_family_group && !is_wp_error( $gt_family_group ) ) {
            if ( $gt_family_group ) {
                foreach ( $gt_family_group as $key => $term ) {
                    $tree_page = get_term_meta( $term->term_id, 'tree_page', true );
                    
                    if ( get_post( $tree_page ) ) {
                        ?>
						<a class="tree_link" title="Family Tree – <?php 
                        echo  esc_attr( $term->name ) ;
                        ?>" href="<?php 
                        echo  esc_attr( get_the_permalink( $tree_page ) ) ;
                        ?>?root=<?php 
                        echo  esc_attr( $post_id ) ;
                        ?>">
							<img style="display:inline-block; width: 20px;" src="<?php 
                        echo  esc_attr( GENEALOGICAL_TREE_DIR_URL ) ;
                        ?>public/img/family-tree.svg">
						</a>
						<?php 
                    }
                
                }
            }
        }
    }
    
    /**
     * It returns an array of all the members in a family group, and whether they are alone, single, or
     * dual
     *
     * @param  mixed $family_group The ID of the family group you want to get the root members for.
     *
     * @return array An array of arrays.
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
        $alone_array = array();
        $final_array = array();
        $getdual_new = array();
        if ( $query->posts ) {
            foreach ( $query->posts as $keym => $member ) {
                $famc = ( get_post_meta( $member->ID, 'famc' ) ? get_post_meta( $member->ID, 'famc' ) : array() );
                $fams = ( get_post_meta( $member->ID, 'fams' ) ? get_post_meta( $member->ID, 'fams' ) : array() );
                /* Removing all the elements from the array that are not arrays. */
                foreach ( $fams as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset( $fams[$key] );
                    }
                }
                /* Removing all the elements from the array that are not arrays. */
                foreach ( $fams as $key => $value ) {
                    if ( !is_array( $value ) ) {
                        unset( $fams[$key] );
                    }
                }
                /* Checking if the member has a spouse and if the spouse has a family. */
                if ( !empty($fams) && empty($famc) ) {
                    foreach ( $fams as $fam ) {
                        $husb = (int) get_post_meta( $fam['fams'], 'husb', true );
                        $wife = (int) get_post_meta( $fam['fams'], 'wife', true );
                        
                        if ( $wife && $husb ) {
                            $spouse = ( $wife === (int) $member->ID ? $husb : $wife );
                            $famc = ( get_post_meta( $spouse, 'famc' ) ? get_post_meta( $spouse, 'famc' ) : array() );
                            
                            if ( empty($famc) ) {
                                $getdual_new[$husb . $wife][0] = $husb;
                                $getdual_new[$husb . $wife][1] = $wife;
                            }
                        
                        } else {
                            if ( $husb ) {
                                $final_array[$husb]['husb'] = $husb;
                            }
                            if ( $wife ) {
                                $final_array[$wife]['wife'] = $wife;
                            }
                        }
                    
                    }
                }
                /*
                Checking if the family (parent) ID is empty and if the family (spouse) ID is empty. If both are empty, it
                adds the member ID to the .
                */
                if ( empty($famc) && empty($fams) ) {
                    $alone_array[] = $member->ID;
                }
            }
        }
        $data = array(
            'alone'  => array_unique( $alone_array ),
            'single' => $final_array,
            'dual'   => $getdual_new,
        );
        return $data;
    }
    
    /**
     * It returns the default root of a family tree
     *
     * @param int $family the family ID.
     *
     * @return mixed The root of the tree.
     *
     * @since    1.0.0
     */
    public function get_default_root( $family )
    {
        $tree_root = $this->get_root_by_family_group( $family );
        if ( $tree_root['single'] ) {
            foreach ( $tree_root['single'] as $key => $value ) {
                if ( isset( $value['husb'] ) && $value['husb'] ) {
                    return $value['husb'];
                }
                if ( isset( $value['wife'] ) && $value['wife'] ) {
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
     * It gets all the families for a given root person
     *
     * @param  mixed $root The ID of the person you want to get the families for.
     * @param  mixed $setting This is an array of settings that you can pass to the function.
     *
     * @return array An array of families.
     *
     * @since    1.0.0
     */
    public function get_families_by_root( $root, $setting = array() )
    {
        $fams = ( get_post_meta( $root, 'fams' ) ? get_post_meta( $root, 'fams' ) : array() );
        foreach ( $fams as $key => $value ) {
            if ( isset( $value['fams'] ) && $value['fams'] && is_array( $value['fams'] ) ) {
                $value['fams'] = $value['fams']['fams'];
            }
            
            if ( isset( $value['fams'] ) && $value['fams'] ) {
                $fams[] = $value['fams'];
                unset( $fams[$key] );
            }
        
        }
        $fams = array_unique( $fams );
        if ( empty($fams) ) {
            return array();
        }
        $query = new \WP_Query( array(
            'post_type'      => 'gt-family',
            'posts_per_page' => -1,
            'post__in'       => $fams,
        ) );
        $families = $query->posts;
        foreach ( $families as $keyx => $family ) {
            $family->husb = (int) get_post_meta( $family->ID, 'husb', true );
            $family->wife = (int) get_post_meta( $family->ID, 'wife', true );
            $family->root = (int) $root;
            $family->spouse = ( (int) $root === (int) $family->husb ? $family->wife : (( $family->husb ? $family->husb : false )) );
            $chill_ids = ( get_post_meta( $family->ID, 'chil' ) ? get_post_meta( $family->ID, 'chil' ) : array() );
            sort( $chill_ids );
            if ( isset( $setting->sibling_order ) && 'oldest' === (string) $setting->sibling_order ) {
                uasort( $chill_ids, array( $this->plugin->helper, 'sort_siblings' ) );
            }
            
            if ( isset( $setting->sibling_order ) && 'youngest' === (string) $setting->sibling_order ) {
                uasort( $chill_ids, array( $this->plugin->helper, 'sort_siblings' ) );
                $chill_ids = array_reverse( $chill_ids );
            }
            
            $family->chil = $chill_ids;
            if ( !$family->chil && !$family->spouse ) {
                unset( $families[$keyx] );
            }
        }
        return $families;
    }
    
    /**
     * It returns an array of all the member IDs that are in a given family
     *
     * @param  mixed $family_id The ID of the family group.
     *
     * @return array An array of post IDs.
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
     * It removes all whitespace from a string
     *
     * @param  string $inline_css The CSS you want to clean up.
     *
     * @return string the trimmed inline_css.
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
        $inline_css = preg_replace( '/\\s+/', ' ', $inline_css );
        return trim( $inline_css );
    }
    
    /**
     * It generates a random string of a given length
     *
     * @param int    $length The length of the random string.
     * @param string $random_string The string that will be returned.
     *
     * @return string A random string of 10 characters.
     *
     * @since    1.0.0
     */
    public function generate_random_string( $length = 10, $random_string = '' )
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_length = strlen( $characters );
        for ( $i = 0 ;  $i < $length ;  $i++ ) {
            $random_string .= $characters[wp_rand( 0, $characters_length - 1 )];
        }
        return $random_string;
    }
    
    /**
     * If the post type is gt-member or gt-tree, and the post is not password protected, then return the
     * shortcode for that post type
     *
     * @param  string $content The content of the post.
     *
     * @return string The content of the post.
     *
     * @since    1.0.0
     */
    public function data_in_single_page( $content )
    {
        global  $post ;
        $post_id = $post->ID;
        if ( 'gt-member' === (string) $post->post_type ) {
            return do_shortcode( '[gt-member id=' . $post_id . ']' ) . $content;
        }
        if ( 'gt-tree' === (string) $post->post_type ) {
            if ( !is_archive() ) {
                return do_shortcode( '[tree id=' . $post_id . ']' ) . $content;
            }
        }
        if ( post_password_required() ) {
            return $content;
        }
        return $content;
    }
    
    /**
     * Display tree style.
     *
     * @param  mixed $tree tree.
     * @param  mixed $setting setting.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function display_tree_style( $tree, $setting )
    {
        include plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-meta-tree-google-fonts.php';
        $class = '';
        $html = '';
        $base = $this->plugin->helper->tree_default_meta();
        $setting = $this->plugin->helper->tree_merge( $base, $setting );
        // $setting = $this->plugin->helper->tree_combine_meta( $setting );
        $setting = \json_decode( \wp_json_encode( $setting ) );
        $rand_id = $this->generate_random_string();
        include plugin_dir_path( __FILE__ ) . 'partials/genealogical-tree-settings.php';
        if ( isset( $inline_style ) ) {
            $html .= $inline_style;
        }
        $html .= '
		<div class="gt-zoom-contorl">
			<button class="gt-expand-compress">
				<img class="gt-expand-icon" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/expand-arrows-alt.svg">
				<img class="gt-compress-icon" src="' . GENEALOGICAL_TREE_DIR_URL . 'public/img/compress-arrows-alt.svg">
			</button>
			<button class="gt-desk-zoom">SHIFT + MOUSEWHEEL to ZOOM</button>
		</div>
		<input class="current-ratio" type="hidden" >
		';
        if ( isset( $setting->layout ) && $setting->layout ) {
            $class .= ' gt-tree-' . $setting->layout . ' ';
        }
        if ( isset( $setting->popup ) && 'on' === (string) $setting->popup ) {
            $class .= ' gt-tree-popup ';
        }
        
        if ( isset( $setting->ajax ) && 'on' === (string) $setting->ajax ) {
            $ajax_html = ' data-setting=\'' . wp_json_encode( $setting ) . '\' data-id="' . $tree . '"';
        } else {
            $ajax_html = '';
        }
        
        $html .= '<div id="famTree" class="gt-tree-collapse ' . $rand_id . ' gt-tree ' . $class . '" ' . $ajax_html . '>';
        $generation = (( $setting->generation_start_from ? $setting->generation_start_from : 1 )) - 1;
        if ( !$ajax_html ) {
            if ( '1' === (string) $setting->style ) {
                $html .= $this->display_tree_style1( $tree, $setting, $generation );
            }
        }
        $html .= '</div>';
        if ( gt_fs()->is_not_paying() && !gt_fs()->is_trial() ) {
            $html .= '
			<div style="padding:10px;text-align:right;position: absolute;right: 0;bottom: 0;">
				Powered By <a href="https://www.devs.family/genealogical-tree">Genealogical Tree</a>
			</div>';
        }
        $container_background_color = $setting->container->background->color;
        $container_background_image = $setting->container->background->image;
        $container_background_size = $setting->container->background->size;
        $container_border_width = $setting->container->border->width;
        $container_border_style = $setting->container->border->style;
        $container_border_color = $setting->container->border->color;
        $container_border_radius = $setting->container->border->radius;
        $style = '
		background-color:' . $container_background_color . '; 
		background-image:' . $container_background_image . '; 
		background-size:' . $container_background_size . '; 
		border:' . $container_border_width . ' ' . $container_border_style . ' ' . $container_border_color . '; 
		border-radius:' . $container_border_radius . ';
		';
        $gtx = '';
        if ( isset( $_GET['expand'] ) ) {
            $gtx = 'gt-expand-compress-toggle';
        }
        return '
		<div id="gt-container" class="gt-container">
			<div id="gt-content" class="gt-content ' . $gtx . '" style="' . $style . '">
				' . $html . '
			</div>
		</div>';
    }
    
    /**
     * Get display tree for shortcode.
     *
     * @param  mixed $atts atts.
     * @param  mixed $content content.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function tree_shortcode( $atts, $content = null )
    {
        $opt = array(
            'id' => null,
        );
        $data = shortcode_atts( $opt, $atts );
        if ( !$data['id'] ) {
            return __( 'No Data. Tree ID required.', 'genealogical-tree' );
        }
        $data = get_post_meta( $data['id'], 'tree', true );
        if ( !isset( $data['family'] ) || isset( $data['family'] ) && !$data['family'] ) {
            return __( 'No data. Family ID required', 'genealogical-tree' );
        }
        $family_group_id = $data['family'];
        unset( $data['family'] );
        $is_default = false;
        if ( empty($data) ) {
            $is_default = true;
        }
        $base = $this->plugin->helper->tree_default_meta();
        $data = $this->plugin->helper->tree_merge( $base, $data, $is_default );
        $data['family'] = $family_group_id;
        if ( !isset( $data['root'] ) || isset( $data['root'] ) && !$data['root'] ) {
            $data['root'] = $this->get_default_root( $data['family'] );
        }
        if ( isset( $_GET['root'] ) ) {
            $data['root'] = $_GET['root'];
        }
        if ( !isset( $data['root'] ) || isset( $data['root'] ) && !$data['root'] ) {
            return __( 'No data, Root ID required', 'genealogical-tree' );
        }
        if ( $data['root'] ) {
            $root = $data['root'];
        }
        return $this->display_tree_style( $root, $data );
    }
    
    /**
     * Get display tree for shortcode
     *
     * @param  mixed $atts atts.
     * @param  mixed $content content.
     *
     * @return mixed
     *
     * @since    1.0.0
     */
    public function gt_tree_shortcode( $atts, $content = null )
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
        $base = $this->plugin->helper->tree_default_meta();
        $data = $this->plugin->helper->tree_merge( $base, $atts );
        if ( isset( $atts['image'] ) && 'true' === $atts['image'] ) {
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
            return __( 'No data', 'genealogical-tree' );
        }
        return $this->display_tree_style( $root, $data );
    }
    
    /**
     * It takes a family group ID, finds the root of the tree, and then displays a list of links to the
     * tree for each root
     *
     * @param  mixed $atts The attributes of the shortcode.
     * @param  mixed $content The attributes of the shortcode.
     *
     * @return mixed the HTML for the tree list.
     *
     * @since    1.0.0
     */
    public function gt_tree_list_shortcode( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'family' => null,
        ), $atts );
        if ( !$data['family'] ) {
            return esc_html__( 'No data', 'genealogical-tree' );
        }
        $family_group_id = $data['family'];
        $tree_root = $this->get_root_by_family_group( $family_group_id );
        if ( !$tree_root['single'] && !$tree_root['dual'] ) {
            return esc_html__( 'No root', 'genealogical-tree' );
        }
        $post_id = get_term_meta( $family_group_id, 'tree_page', true );
        ob_start();
        ?>
		<div class="gt-tree-list-pub">
		<?php 
        if ( $tree_root['single'] ) {
            foreach ( $tree_root['single'] as $value ) {
                
                if ( isset( $value['husb'] ) && $value['husb'] ) {
                    ?>
					<div>
						<a href="<?php 
                    echo  esc_attr( get_the_permalink( $post_id ) ) ;
                    ?>?root=<?php 
                    echo  esc_attr( $value['husb'] ) ;
                    ?>">
							<?php 
                    echo  esc_html( get_the_title( $value['husb'] ) ) ;
                    ?>
						</a>
					</div>
					<?php 
                }
                
                
                if ( isset( $value['wife'] ) && $value['wife'] ) {
                    ?>
					<div>
						<a href="<?php 
                    echo  esc_attr( get_the_permalink( $post_id ) ) ;
                    ?>?root=<?php 
                    echo  esc_attr( $value['wife'] ) ;
                    ?>">
							<?php 
                    echo  esc_html( get_the_title( $value['wife'] ) ) ;
                    ?>
						</a>
					</div>
					<?php 
                }
            
            }
        }
        if ( $tree_root['dual'] ) {
            foreach ( $tree_root['dual'] as $value ) {
                ?>
				<div>
					<a href="<?php 
                echo  esc_attr( get_the_permalink( $post_id ) ) ;
                ?>?root=<?php 
                echo  esc_attr( $value[0] ) ;
                ?>">
						<?php 
                echo  esc_html( get_the_title( $value[0] ) ) ;
                ?> and <?php 
                echo  esc_html( get_the_title( $value[1] ) ) ;
                ?>
					</a>
				</div>
				<?php 
            }
        }
        
        if ( $tree_root['alone'] ) {
            ?>
			<h4><?php 
            echo  esc_html__( 'Alone', 'genealogical-tree' ) ;
            ?></h4>
			<?php 
            foreach ( $tree_root['alone'] as $value ) {
                ?>
				<div>
					<a href="<?php 
                echo  esc_attr( get_the_permalink( $post_id ) ) ;
                ?>?root=<?php 
                echo  esc_attr( $value ) ;
                ?>">
						<?php 
                echo  esc_html( get_the_title( $value ) ) ;
                ?>
					</a>
				</div>
				<?php 
            }
        }
        
        ?>
		</div>
		<?php 
        return ob_get_clean();
    }
    
    /**
     * It takes the ID of a member and returns the HTML for that member's information
     *
     * @param  mixed $atts The attributes passed to the shortcode.
     * @param  mixed $content The content of the shortcode.
     *
     * @return mixed The single_member_info function is being returned.
     *
     * @since    1.0.0
     */
    public function gt_member_shortcode( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'id' => null,
        ), $atts );
        if ( !$data['id'] ) {
            return esc_html__( 'Member ID required', 'genealogical-tree' );
        }
        if ( !get_post( $data['id'] ) ) {
            return esc_html__( 'Member Not Found', 'genealogical-tree' );
        }
        return $this->single_member_info( $data['id'] );
    }
    
    /**
     * It takes the shortcode attributes, and if there's a family attribute, it gets all the members of
     * that family and displays them. If there's an ids attribute, it gets the members with those ids and
     * displays them
     *
     * @param  mixed $atts The attributes passed to the shortcode.
     * @param  mixed $content The content of the shortcode.
     *
     * @return mixed The output of the function.
     *
     * @since    1.0.0
     */
    public function gt_members_shortcode( $atts, $content = null )
    {
        $data = shortcode_atts( array(
            'family' => null,
            'ids'    => null,
        ), $atts );
        $members = array();
        if ( $data['family'] ) {
            $members = $this->get_all_members_of_family( $data['family'] );
        }
        if ( $data['ids'] ) {
            $members = explode( ',', $data['ids'] );
        }
        ob_start();
        
        if ( is_array( $members ) && !empty($members) ) {
            ?>
			<div class="gt-members-public">
				<ul>
				<?php 
            foreach ( $members as $key => $member ) {
                ?>
					<li>
					<h3> # <?php 
                echo  esc_html( get_the_title( $member ) ) ;
                ?></h3>
					<?php 
                $this->single_member_info( $member );
                ?>
					</li>
				<?php 
            }
            ?>
				</ul>
			</div>
			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * If the nonce is valid, then register the user and display a confirmation message.
     *
     * @return void
     *
     * @since    2.1.1
     */
    public function process_registration_post()
    {
        $gt_registration_nonce = ( isset( $_POST['_gt_registration_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['_gt_registration_nonce'] ) ) : '' );
        
        if ( $gt_registration_nonce && wp_verify_nonce( $gt_registration_nonce, '_gt_registration_nonce_action' ) ) {
            $user_login = ( isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ) ) : '' );
            $user_email = ( isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '' );
            $user_id = register_new_user( $user_login, $user_email );
            if ( $user_id && !is_wp_error( $user_id ) ) {
                update_option( 'gt_registration_confirmation', true );
            }
            if ( is_wp_error( $user_id ) && !empty($user_id->errors) ) {
                update_option( 'gt_registration_validation', $user_id->errors );
            }
        }
    
    }
    
    /**
     * It checks if the user is logged in, and if not, it displays the registration form.
     *
     * @return mixed
     *
     * @since    2.1.1
     */
    public function gt_user_registration_shortcode()
    {
        ob_start();
        $errors = get_option( 'gt_registration_validation' );
        $confirmation = get_option( 'gt_registration_confirmation' );
        ?>
		<?php 
        
        if ( !is_user_logged_in() ) {
            ?>
			<div id="register_error">
			<?php 
            foreach ( $errors as $error ) {
                ?>
				<?php 
                echo  wp_kses( current( $error ), array(
                    'strong' => array(),
                ) ) ;
                ?>
				<br>
				<?php 
            }
            update_option( 'gt_registration_validation', array() );
            ?>
			</div>
			<?php 
            
            if ( $confirmation ) {
                ?>
				<p id="reg_passmail">
					<?php 
                esc_html_e( 'A confirmation email has been sent to your registered email address.', 'genealogical-tree' );
                ?> 
				</p>
				<?php 
            }
            
            update_option( 'gt_registration_confirmation', false );
            ?>
			<form name="register-form" id="register-form" action="" method="post">
				<?php 
            wp_nonce_field( '_gt_registration_nonce_action', '_gt_registration_nonce' );
            ?>
				<p>
					<label for="user_login">
						<?php 
            esc_html_e( 'Username', 'genealogical-tree' );
            ?>
					</label>
					<input type="text" name="user_login" id="user_login" class="input" value="" size="20" autocapitalize="off">
				</p>
				<p>
					<label for="user_email">
						<?php 
            esc_html_e( 'Email', 'genealogical-tree' );
            ?>
					</label>
					<input type="text" name="user_email" id="user_email" class="input" value="" size="25">
				</p>
				<p id="reg_passmail">
					<?php 
            esc_html_e( 'Registration confirmation will be emailed to you.', 'genealogical-tree' );
            ?>
				</p>
				<br class="clear">
				<input type="hidden" name="role" value="gt_member">
				<p class="submit">
					<input type="submit" name="gt-user-submit" id="gt-user-submit" class="button button-primary button-large" value="Register">
				</p>
			</form>
		<?php 
        }
        
        ?>

		<?php 
        
        if ( is_user_logged_in() ) {
            ?>
			<p> <?php 
            esc_html_e( 'You allready registered and loged in. Logout to register new user account.', 'genealogical-tree' );
            ?> </p>
		<?php 
        }
        
        ?>
		<?php 
        return ob_get_clean();
    }
    
    /**
     * It creates a shortcode that can be used to display a login form on any page.
     *
     * @return string
     *
     * @since    2.1.1
     */
    public function gt_user_login_shortcode()
    {
        $host = ( isset( $_SERVER['HTTP_HOST'] ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' );
        $uri = ( isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' );
        $args = array(
            'echo'           => true,
            'remember'       => true,
            'redirect'       => (( is_ssl() ? 'https://' : 'http://' )) . $host . $uri,
            'form_id'        => 'loginform',
            'id_username'    => 'user_login',
            'id_password'    => 'user_pass',
            'id_remember'    => 'rememberme',
            'id_submit'      => 'wp-submit',
            'label_username' => __( 'Username or Email Address' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in'   => __( 'Log In' ),
            'value_username' => '',
            'value_remember' => false,
        );
        ob_start();
        
        if ( !is_user_logged_in() ) {
            wp_login_form( $args );
        } else {
            ?>
			<center>
				<?php 
            echo  esc_html__( 'Already logged in.', 'genealogical-tree' ) ;
            ?>
				<a href="<?php 
            echo  esc_attr( admin_url() ) ;
            ?>">
					<?php 
            echo  esc_html__( 'Dashboard', 'genealogical-tree' ) ;
            ?>
				</a>
				|
				<a href="<?php 
            echo  esc_attr( wp_logout_url( home_url() ) ) ;
            ?>">
					<?php 
            echo  esc_html__( 'Logout', 'genealogical-tree' ) ;
            ?>
				</a>
			</center>
			<?php 
        }
        
        return ob_get_clean();
    }
    
    /**
     * It adds a link to the lost password page to the login form
     *
     * @return string a string of HTML.
     *
     * @since    2.1.1
     */
    public function add_lost_password_link()
    {
        return '
		<a href="' . esc_attr( wp_lostpassword_url() ) . '">
			' . esc_html__( 'Forgot Your Password?', 'genealogical-tree' ) . '
		</a>
		';
    }
    
    /**
     * If the query is for an author archive, then change the post type to include both the `gt-member` and
     * `gt-family` post types
     *
     * @param  mixed $query The WP_Query object.
     *
     * @return void
     *
     * @since    2.1.1
     */
    public function pre_get_posts( $query )
    {
        if ( is_admin() || !$query->is_main_query() ) {
            return;
        }
        if ( $query->is_author() ) {
            $query->set( 'post_type', array( 'gt-member', 'gt-family' ) );
        }
    }

}