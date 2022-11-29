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

trait Genealogical_Tree_Style_3
{
    /**
     * Function for `tree_style3__childs`.
     *
     * @param  mixed $chills chills.
     * @param  mixed $setting setting.
     * @param  mixed $gen gen.
     * @param  mixed $checker checker.
     * @param  mixed $collapsible collapsible.
     *
     * @return void
     *
     * @since    1.0.0
     */
    public function tree_style3__childs(
        $chills,
        $setting,
        $gen = 0,
        $checker = array(),
        $collapsible = null
    )
    {
        ?>
		<ul class="childs" style="<?php 
        echo  esc_attr( $collapsible ) ;
        ?>">
			<?php 
        foreach ( $chills as $key => $chill ) {
            ?>
				<?php 
            
            if ( !in_array( $chill, $checker, true ) ) {
                ?>
					<?php 
                array_push( $checker, $chill );
                ?>
					<?php 
                $this->tree_style3__premium_only(
                    $chill,
                    $setting,
                    $gen,
                    $checker
                );
                ?>
				<?php 
            }
            
            ?>
			<?php 
        }
        ?>
		</ul>
		<?php 
    }

}