<?php
namespace mZoo\Instructors;
use mZoo\Instructors as AC;

/**
 * Register a meta box using a class.
 */
 
 abstract class MZOO_Custom_Meta_Box {
 
    public static function add()
    {
        $screens = ['instructor', 'wporg_cpt'];
        $class = get_called_class();
        foreach ($screens as $screen) {
            add_meta_box(
                'hobbies-meta-box',
                __( 'Instructor Details', 'textdomain' ),
                // Use this line while still supporting php5.4, then next line instead
                [$class, 'html'],
                //[self::class, 'html'],   // Content callback, must be of type callable
                $screen,
                'advanced',
                'default',
                'high'
            );
        }
    }
 
    public static function save ( $post_id, $post ) {
        // Add nonce for security and authentication.
        $nonce_name   = isset( $_POST['mzoo_instructor_nonce'] ) ? $_POST['mzoo_instructor_nonce'] : '';
        $nonce_action = 'mzoo_instructor_action';
 
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        // Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        // Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }
	
				// Add values of $events_meta as custom fields
				foreach (AC\MZOO_Custom_Fields::$custom_fields as $key => $value) { // Cycle through the $custom_fields array!
					$value = implode(', ', $value);
					if(get_post_meta($post_id, $key, FALSE)) { // If the custom field already has a value
						update_post_meta($post_id, $key, $_POST[$key]);
					} else { // If the custom field doesn't have a value
						add_post_meta($post_id, $key,  $_POST[$key]);
					}
					if(!$_POST[$key]) delete_post_meta($post_id, $key); // Delete if blank
				}
    }
 
    public static function html($post) {
        // Add nonce for security and authentication.
        wp_nonce_field( 'mzoo_instructor_action', 'mzoo_instructor_nonce' );
	
				// Noncename needed to verify where the data originated
				echo '<input type="hidden" name="eventmeta_noncename" id="eventmeta_noncename" value="' . 
				wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
				$hobbies = get_post_meta($post->ID, '_contact_info', true);
				$years_of_experience = get_post_meta($post->ID, '_certifications', true);
	
				?>
				<label for="hobbies">Contact Info</label>
				<input type="text" name="_contact_info" value="<?=$contact_info?>" class="widefat" id="contact_info" />
				<label for="certifications">Certifications</label>
				<input type="text" name="_certifications" value="<?=$certifications?>" class="widefat" id="certifications" />
				<?php

    }
}


 
?>