<?php
namespace Zqe;

class Wp_Term_Meta {

    private $taxonomy;
    private $post_type;
    private $fields = [];

    public function __construct( $taxonomy, $post_type, $fields = [] ) {
        
        $this->taxonomy  = $taxonomy;
        $this->post_type = $post_type;
        $this->fields    = $fields;

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

        add_action( 'init', [ $this, 'register_term_meta' ] );

        add_action( sprintf( '%s_add_form_fields', $this->taxonomy ) , [ $this, 'add_form_fields' ], 10, 1 );
        add_action( sprintf( '%s_edit_form_fields', $this->taxonomy ) , [ $this, 'edit_form_fields' ], 10, 2 );
        
        add_filter( sprintf( 'manage_edit-%s_columns', $this->taxonomy ), [ $this, 'manage_edit_taxonomy_columns' ] );
        add_filter( sprintf( 'manage_%s_custom_column', $this->taxonomy ), [ $this, 'manage_taxonomy_custom_column' ], 10, 3 );

        add_action( sprintf( 'create_%s',  $this->taxonomy ), [ $this, 'save' ], 10, 2 );
        add_action( sprintf( 'edit_%s', $this->taxonomy ), [ $this, 'save' ], 10, 2 );
        add_action( sprintf( 'delete_%s', $this->taxonomy ),  [ $this, 'delete' ], 5, 4);

    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function enqueue_scripts() {

        wp_enqueue_media();
        wp_enqueue_style( 'zqe-term-meta', plugin_dir_url( __FILE__ ) . 'css/zqe-term-meta.css', array( 'wp-color-picker' ), '1.0.0', 'all' );
        wp_enqueue_style( 'zqe-from-field-dependency', plugin_dir_url( __FILE__ ) . 'css/zqe-from-field-dependency.css', array(), '1.0.0', 'all' );
        wp_enqueue_script( 'zqe-from-field-dependency', plugin_dir_url( __FILE__ ) . 'js/zqe-from-field-dependency.js', array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'zqe-term-meta', plugin_dir_url( __FILE__ ) . 'js/zqe-term-meta.js', array( 'jquery', 'wp-color-picker', 'zqe-from-field-dependency' ), '1.0.0', true );
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function register_term_meta() {

        foreach ( $this->fields as $key => $field ) {
            register_term_meta( $this->taxonomy, $field['id'], [
                'sanitize_callback' => 'sanitize',
                'type' => $field['type'],
                'single' => true,
            ] );
        }

    }

    /**
     * Fires after the Add Term form fields.
     *
     * @since 1.0.0
     *
     * @param string $taxonomy The taxonomy slug.
     */
    public function add_form_fields( $taxonomy ) {

        $this->generate_fields();

    }
    /**
     * Fires after the Edit Term form fields are displayed.
     *
     * @since 1.0.0
     *
     * @param WP_Term $term      Current taxonomy term object.
     * @param string  $taxonomy Current taxonomy slug.
     */
    public function edit_form_fields( $term, $taxonomy ) {

        $this->generate_fields( $term );

    }
    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    private function generate_fields( $term = false ) {

        $screen = get_current_screen();
        if ( ( $screen->post_type == $this->post_type ) and ( $screen->taxonomy == $this->taxonomy ) ) {
            self::generate_form_fields( $this->fields, $term );
        }

    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public static function generate_form_fields( $fields, $term ) {
        
        if ( empty( $fields ) ) {
            return;
        }

        wp_nonce_field( basename( __FILE__ ), 'term_meta_text_nonce' );

        foreach ( $fields as $field ) {
            $field['term']          = $term;
            $field['id']                = esc_html($field['id']);

            if ( ! $term ) {
                $field['value']         = isset($field['default']) ? $field['default'] : '';
            } else {
                $field['value']         = get_term_meta($term->term_id, $field['id'], true);
            }

            $field['size']              = isset($field['size']) ? $field['size'] : '40';
            $field['required']          = (isset($field['required']) and $field['required'] == true) ? ' aria-required="true"' : '';
            $field['placeholder']       = (isset($field['placeholder'])) ? ' placeholder="' . $field['placeholder'] . '" data-placeholder="' . $field['placeholder'] . '"' : '';
            $field['desc']              = (isset($field['desc'])) ? $field['desc'] : '';
            $field['dependency']        = (isset($field['dependency'])) ? $field['dependency'] : array();

            self::field_start($field, $term);
            switch ($field['type']) {
                case 'text':
                case 'url':
                    echo self::text($field);
                break;
                case 'color':
                    echo self::color($field);
                break;
                case 'textarea':
                    echo self::textarea($field);
                break;
                case 'editor':
                    echo self::editor($field);
                break;
                case 'select':
                case 'select2':
                    echo self::select($field);
                break;
                case 'image':
                    echo self::image($field);
                break;
                case 'checkbox':
                    echo self::checkbox($field);
                break;
                case 'callback':
                    echo self::field_callback($field);
                break;
                default:
                break;
            }
            self::field_end($field, $term);
        }
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function field_start( $field, $term ) {
        $dependency = empty($field['dependency']) ? '' : "data-dependency='" . wc_esc_json(wp_json_encode($field['dependency'])) . "'";
        ob_start();
        if (!$term) {
            ?>
            <div <?php echo $dependency ?> class="form-field <?php echo esc_attr($field['id']) ?> <?php echo empty($field['required']) ? '' : 'form-required' ?>">
            <?php if ($field['type'] !== 'checkbox') { ?>
                <label for="<?php echo esc_attr($field['id']) ?>"><?php echo $field['label'] ?></label>
                <?php
            }
        } else {
            ?>
            <tr <?php echo $dependency ?> class="form-field  <?php echo esc_attr($field['id']) ?> <?php echo empty($field['required']) ? '' : 'form-required' ?>">
            <th scope="row">
                <label for="<?php echo esc_attr($field['id']) ?>"><?php echo $field['label'] ?></label>
            </th>
            <td>
            <?php
        }
        echo ob_get_clean();
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function field_end( $field, $term ) {
        ob_start();
        if (!$term) {
            ?>
            <p><?php echo $field['desc'] ?></p>
            </div>
            <?php
        } else {
            ?>
            <p class="description"><?php echo $field['desc'] ?></p></td>
            </tr>
            <?php
        }
        echo ob_get_clean();
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function manage_edit_taxonomy_columns( $columns ) {
        return apply_filters('zqe_manage_edit_taxonomy_columns', $columns );
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function manage_taxonomy_custom_column( $columns, $column, $term_id ) {
        return apply_filters('zqe_manage_taxonomy_custom_column', $columns, $column, $term_id );
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function save( $term_id, $tt_id ) {
        
        if ( ! isset( $_POST['term_meta_text_nonce'] ) || ! wp_verify_nonce( $_POST['term_meta_text_nonce'], basename( __FILE__ ) ) ){
            return;
        }

        foreach ( $this->fields as $field ) {
            foreach ( $_POST as $post_key   => $value ) {
                if ( $field['id'] == $post_key ) {
                    update_term_meta( $term_id, $field['id'], self::sanitize( $field['type'], $value ) );
                }
            }
        }
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public static function sanitize( $type, $value ) {
        switch ($type) {
            case 'text':
            case 'color':
                return esc_html($value);
            break;
            case 'url':
                return esc_url($value);
            break;
            case 'image':
                return absint($value);
            break;
            case 'textarea':
                return esc_textarea($value);
            break;
            case 'editor':
                return wp_kses_post($value);
            break;
            case 'select':
            case 'select2':
                return sanitize_key($value);
            break;
            case 'checkbox':
                return sanitize_key($value);
            break;
            default:
            break;
        }
        return sanitize_text_field($value);
    }

    /**
     * Register term meta
     *
     * @since    1.0.0
     *
     * @param
     */
    public function delete( $term_id, $tt_id, $taxonomy, $deleted_term ) {
        global $wpdb;
        $term_id = absint( $term_id );
        if ( $term_id and $taxonomy == $this->taxonomy ) {
            $wpdb->delete( $wpdb->termmeta, [ 'term_id' => $term_id ], [ '%d' ] );
        }
    }

    /**
     * text
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function text( $field ) {
        ob_start();
        ?>
        <input 
            name="<?php echo $field['id'] ?>" 
            id="<?php echo $field['id'] ?>" 
            type="<?php echo $field['type'] ?>" 
            value="<?php echo $field['value'] ?>" 
            size="<?php echo $field['size'] ?>" 
            <?php echo $field['required'] ?> 
            <?php echo $field['placeholder'] ?>>
        <?php
        return ob_get_clean();
    }

    /**
     * color
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function color( $field ) {
        ob_start();
        ?>
        <input 
            name="<?php echo $field['id'] ?>" 
            id="<?php echo $field['id'] ?>" 
            type="text"
            class="zqe-term-meta-color-picker" 
            value="<?php echo $field['value'] ?>"
            data-default-color="<?php echo $field['value'] ?>"
            size="<?php echo $field['size'] ?>" <?php echo $field['required'] ?> 
            <?php echo $field['placeholder'] ?>>
        <?php
        return ob_get_clean();
    }

    /**
     * textarea
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function textarea( $field ) {
        ob_start();
        ?>
        <textarea 
            name="<?php echo $field['id'] ?>" 
            id="<?php echo $field['id'] ?>" 
            rows="5"
            cols="<?php echo $field['size'] ?>" 
            <?php echo $field['required'] ?> 
            <?php echo $field['placeholder'] ?>>
            <?php echo $field['value'] ?>
        </textarea>
        <?php
        return ob_get_clean();
    }

    /**
     * editor
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function editor( $field ) {
        $field['settings'] = isset($field['settings']) ? $field['settings'] : [
            'textarea_rows' => 8,
            'quicktags' => false,
            'media_buttons' => false
        ];
        ob_start();
        wp_editor($field['value'], $field['id'], $field['settings']);
        return ob_get_clean();
    }

    /**
     * select
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function select( $field ) {
        $field['options'] = isset($field['options']) ? $field['options'] : array();
        $field['multiple'] = isset($field['multiple']) ? ' multiple="multiple"' : '';
        $css_class = ($field['type'] == 'select2') ? 'wc-enhanced-select' : '';
        ob_start();
        ?>
        <select 
            name="<?php echo $field['id'] ?>" 
            id="<?php echo $field['id'] ?>"
            class="<?php echo $css_class ?>" 
            <?php echo $field['multiple'] ?>>
            <?php
            foreach ($field['options'] as $key => $option) {
                echo '<option' . selected($field['value'], $key, false) . ' value="' . $key . '">' . $option . '</option>';
            }
            ?>
        </select>
        <?php
        return ob_get_clean();
    }

    /**
     * image
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function image( $field ) {
        ob_start();
        ?>
        <div class="zqe-term-meta-image-field-wrapper">
            <div class="zqe-term-meta-image-field-preview">
                <img data-placeholder="<?php echo esc_url(self::placeholder_img_src()); ?>" src="<?php echo esc_url(self::get_img_src($field['value'])); ?>" width="100px" height="100px"/>
            </div>
            <div class="zqe-term-meta-image-field-button-wrapper">
                <input type="hidden" id="<?php echo $field['id'] ?>" name="<?php echo $field['id'] ?>" value="<?php echo esc_attr($field['value']) ?>"/>
                <button type="button" class="zqe-term-meta-image-field-upload-button button button-primary button-small">
                    <?php esc_html_e('Upload', 'woo-variation-swatches'); ?> 
                </button>
                <button type="button" class="zqe-term-meta-image-field-remove-button button button-danger button-small" style="<?php echo (empty($field['value']) ? 'display:none' : '') ?>" >
                    <?php esc_html_e('Remove', 'woo-variation-swatches'); ?> 
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * checkbox
     *
     * @since    1.0.0
     *
     * @param
     */
    private static function checkbox( $field ) {
        ob_start();
        ?>
        <label for="<?php echo esc_attr($field['id']) ?>">
            <input 
            name="<?php echo $field['id'] ?>" 
            id="<?php echo $field['id'] ?>" 
            type="<?php echo $field['type'] ?>" 
            value="yes" <?php echo $field['required'] ?> 
            <?php echo $field['placeholder'] ?> 
            <?php checked($field['value'], 'yes') ?>>
            <?php echo $field['label'] ?>
        </label>
        <?php
        return ob_get_clean();
    }
    
    /**
     *
     * @since    1.0.0
     */
    private static function field_callback($field) {
        return call_user_func_array($field['callback'], [(array) $field]);
    }
    /**
     *
     * @since    1.0.0
     */
    private static function get_img_src( $thumbnail_id = false ) {
        if ( ! empty( $thumbnail_id ) ) {
            $image = wp_get_attachment_thumb_url( $thumbnail_id );
        } else {
            $image = self::placeholder_img_src();
        }
        return $image;
    }
    
    /**
     *
     * @since    1.0.0
     */
    private static function placeholder_img_src() {
        return plugin_dir_url( __FILE__ ) . 'imgs/placeholder.png';
    }
}

