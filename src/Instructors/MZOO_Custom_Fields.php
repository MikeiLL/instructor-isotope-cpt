<?php
namespace mZoo\Instructors;

/**
 * Holder for all our Custom Fields.
 */
		
class MZOO_Custom_Fields {
 
 		// An array of all custom fields we have defined, mapped to
 		// their display name and an array of all content from any published post
 		public static $custom_fields;
 		
    /**
     * Constructor.
     *
     * Get an array of the custom fields we want, mapped to their dislay names.
     * Return an array with underscored_keys mapped to an array containing the
     * display name and the content.
     */
    public function __construct($fields) {
    	$hash = array();
    	foreach($fields as $field => $display_name):
    		// Prepend with underscore
    		$hash['_'.$field] = array('display_name' => $display_name, 'content' => array());
    	endforeach;
 			self::$custom_fields = $hash;
    }
    
    /**
     * Get a $post-ID and return a multidimensional array of custom fields 
     * to populate with arrays of comma separated items
		 * for the 'raw' and 'class-sanitized' versions we use in displaying 
		 * the HTML.
     */
    public static function two_versions($id) {
    	// Loop through our defined custom fields (TODO, make admin configurable?)
    	foreach(self::$custom_fields as $meta => $value):
				// Make two version of custom field data. One is for the raw text, the other to use
				// in the CSS/JS class.
				$raw = explode(', ', get_post_meta($id, $meta, true));
				// Make each item an array with $raw and sanitized
				$field_values = array_map( function ($item) {
					return array('raw' => $item, 'sanitized' => strtolower(sanitize_html_class(str_replace(' ', '-', $item))));
				}, $raw);
				// Merge in custom_field array from current instructor to the 'content' array
				self::$custom_fields[$meta]['content'] = array_merge(self::$custom_fields[$meta]['content'], $field_values);
			endforeach;
    	return true;
    }
 
    
}
 
?>