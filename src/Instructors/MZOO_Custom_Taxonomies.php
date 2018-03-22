<?php
namespace mZoo\Instructors;
use mZoo\Instructors as AC;

/**
 * Register a meta box using a class.
 */
		
class MZOO_Custom_Taxonomies {
 
    /**
     * Constructor.
     */
    public function __construct() {
    	
    }
    
		/**
		 * Add custom taxonomy for Languages Spoken
		 *
		 * @since 1.0.0
		 * source: https://wordpress.stackexchange.com/a/57515/48604
		 */		
		public static function languages_init() {

			// create a new taxonomy
			register_taxonomy(
				'specializations_tag',
				'instructor',
				array(
					'label' => __( 'Specializations' ),
					'rewrite' => array( 'slug' => 'specializations' ),
					'meta_box_cb'	=> array('lexweb\\AMERICARE\\MZOO_Custom_Taxonomies', 'specializations_metabox_cb'),
					/*'capabilities' => array(
						'assign_terms' => 'edit_guides',
						'edit_terms' => 'publish_guides'
					)*/
				)
			);
		}
		
		
		/**
		 * Add Custom Text Below Metabox
		 *
		 * @since 1.0.0
		 * source: https://daronspence.com/2016/02/11/modifying-custom-taxonomy-forms-in-wordpress/
		 *         https://wordpress.stackexchange.com/a/148965/48604
		 */		
		public static function specializations_metabox_cb( $post, $box ){
	
			// Render the default hierarchical meta box form
			post_tags_meta_box( $post, $box );
			// Add all of our new stuff!
			echo "<p><strong>Note: </strong> Use double-dash ('--') in place of comma to create item which includes a comma. eg: 'Small--red fox'</p>";
		}

		/**
		 * Display taxonomy without links
		 *
		 * @since 1.0.0
		 * source: https://wordpress.stackexchange.com/a/154152/48604
		 */				
		public static function show_tax($taxname, $title, $title_tag, $raw, $separator){
			$terms = get_the_terms($post->ID, $taxname);
			if(empty($terms)) return false;
			$out = '';
			if (!empty($title)){
					if(empty($title_tag)){
							$title_tag = 'span';
						 }
							$out .= '<'.$title_tag.'>'.$title.'</'.$title_tag.'>';
					}
			if (!empty($raw)){
									$count = count($terms);
									foreach($terms as $term){
										$out .= $term->name;
										$count--;
										if($count > 0) $out .= ', ';
									}
					}else{
							$out .= '<ul>';
									foreach ( $terms as $term ){
															$out .='<li>'.$term->name.'</li> ';
															}
									$out .= '</ul>';
							}       
					return $out;
		}
		
		
		/**
		 * Filter for tags with comma
		 * replace '--' with ', ' in the output - allow tags with comma this way
		 * e.g. save tag as "Fox--Peter" but display thx 2 filters like "Fox, Peter"
		 * 
		 * source: https://wordpress.stackexchange.com/a/55296/48604
		 */
			
			public static function comma_tag_filter($tag_arr){
						$tag_arr_new = $tag_arr;
						if(in_array($tag_arr->taxonomy, array('post_tag', 'instructor_tag', 'languages_tag', 'specializations_tag')) && strpos($tag_arr->name, '--')){
								$tag_arr_new->name = str_replace('--',', ',$tag_arr->name);
						}
						return $tag_arr_new;    
				}

		/**
		 * Return filtered Tags when terms are requested
		 * 
		 * Call the above function for each term
		 * 
		 * source: as above
		 */
				public static function comma_tags_filter($tags_arr){
						$tags_arr_new = array();
						foreach($tags_arr as $tag_arr){
								$tags_arr_new[] = self::comma_tag_filter($tag_arr);
						}
						return $tags_arr_new;
				}
}
 
?>