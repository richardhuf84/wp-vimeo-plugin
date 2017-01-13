<?php

/**
 * Add meta box to post edit screen to lookup video via Vimeo
 *
 * Calls the class on the post edit screen.
 *
 * example https://developer.wordpress.org/reference/functions/add_meta_box/
 *
 */
function call_queryGameClass() {
    new queryGameClass();
}

if ( is_admin() ) {
    add_action( 'load-post.php',     'call_queryGameClass' );
    add_action( 'load-post-new.php', 'call_queryGameClass' );
}

/**
 * The Class.
 */
class queryGameClass {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'vimeo-video' );

        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'query_vimeo_video_meta_box',
                __( 'Lookup Video', 'custom_plugin' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['custom_plugin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }

        $nonce = $_POST['custom_plugin_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'custom_plugin_inner_custom_box' ) ) {
            return $post_id;
        }

        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }

        /* OK, it's safe for us to save the data now. */

        // Sanitize the user input.
        $mydata = sanitize_text_field( $_POST['custom_plugin_new_field'] );

        // Update the meta field.
        update_post_meta( $post_id, '_custom_plugin_meta_value_key', $mydata );

    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        // Add an nonce field so we can check for it later.
          wp_nonce_field( 'custom_plugin_inner_custom_box', 'custom_plugin_inner_custom_box_nonce' );

        // Use get_post_meta to retrieve an existing value from the database.
        $value = get_post_meta( $post->ID, '_custom_plugin_meta_value_key', true );

        // Display the form, using the current value.
        ?>
        <label for="custom_plugin_new_field">
            <?php _e( 'Video title', 'custom_plugin' ); ?>
        </label>
        <input type="text" id="custom_plugin_new_field" name="custom_plugin_new_field" value="<?php echo esc_attr( $value ); ?>" size="25" />

        <?php
          submit_button( 'Search', 'primary', 'custom-plugin-submit-query-game' );

    }
}
