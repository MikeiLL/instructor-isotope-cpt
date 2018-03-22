<?php 
use mZoo\Instructors as AC;
/*
Template Name: Instructor
*/
get_header(); ?>

<?php



$wrap_classes       = array( 'vcex-module', 'vcex-staff-grid-wrap', 'clr' );
$filter_classes = 'vcex-staff-filter vcex-filter-links clr';
$wrap_classes  = implode( ' ', $wrap_classes );


$terms = get_terms( array(
    'taxonomy' => 'instructor_class_types',
    'hide_empty' => false,
) );


$args = array(
'post_type' => 'instructor', 
'post_status' => 'publish',
'fields' => 'id',
'posts_per_page' => -1
);
$query = new WP_Query($args);

// Initialize array to hold instructor IDs.
$instructor_ids = array();


if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); 
		$instructor_ids[] += $post->ID;
		AC\MZOO_Custom_Fields::two_versions($post->ID);
	endwhile; 
	?>
	
	<div id="page" class="<?=$wrap_classes?> " style="margin: 3em;">
	<h1 class="post-archive__title service-areas__title">Our <?php post_type_archive_title( '', true ); ?></h1>
	<?php the_archive_description(); ?>
	<script type="application/ld+json">
        {
          "@context": "http://schema.org/",
          "@type": "CollectionPage",
          "name": "<?php post_type_archive_title( '', true ); ?>",
          "description": "<?php echo the_archive_description(); ?>"
        }
    </script>
    <ul id="filters" class="<?=$filter_classes?>">
        <?php
            $count = count($terms);
                echo '<li style="float:left;" class="active"><a class="theme-button minimal-border" href="javascript:void(0)" title="" data-filter=".all">All</a></li>';
            if ( $count > 0 ){
 
                foreach ( $terms as $term ) {
 
                    $termname = strtolower($term->name);
                    $termname = str_replace(' ', '-', $termname);
                    echo '<li style="float:left;" ><a class="theme-button minimal-border" href="javascript:void(0)" title="" data-filter=".'.$termname.'">'.$term->name.'</a></li>';
                }
            }
        ?>
    </ul>
 
		<div class="wpex-row clr" id="checkboxes">
  
		<?php
		// How many columns do we need?
		$column_count = 3;
		?>				
				<div class="col span_1_of_<?=$column_count?> clr">
					<h3>Class Types</h3>
					<?php
					// Get all the tags associated with set of instructor IDs
					$instructor_tags_array =  wp_get_object_terms($instructor_ids, "instructor_class_types", array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all'));
					?>
					<ul>
					<?php
		
					foreach($instructor_tags_array as $term_obj):
						$term_name = isset($term_obj->name) ? $term_obj->name : ''; 
					?>
							<li><label><input type="checkbox" value=".<?=$term_obj->slug?>" /><?=$term_obj->name?></label></li>
					<?php endforeach; ?>
						</ul>
					</div>
					
		</div> <?php //wpex-row ?>
 		<p id="selected"></p>
 		
    <div id="instructors">
 
       <?php 
			
       while ( $query->have_posts() ) : $query->the_post(); 
                            $title = the_title_attribute(array('echo' => false)); 
          $terms =  get_the_terms( $post->ID, 'instructor_category' );
          $tags = get_the_terms( $post->ID, 'instructor_class_types' );
          // Begin building a list of class values for Isotope filtering
          $tax = mzoo_css_string_from_term_items($terms);	
          $tax .= ' ' . mzoo_css_string_from_term_items($tags);
          // Get all the custom fields for the post
          $post_custom_fields = get_post_custom($post->ID);
          // Loop through our custom fields and for each that matches
          // add it to an array of custom field values for this post
          $cf_values = array();
          foreach (AC\MZOO_Custom_Fields::$custom_fields as $cf_key => $cf_val):
          	foreach ($post_custom_fields as $pcf_key => $pcf_val):
          		if($pcf_key == $cf_key):
          				// populate the custom fields array's content
          				$cf_values[$pcf_key]['content'] = $pcf_val[0];
          				$cf_values[$pcf_key]['display_name'] = $cf_val['display_name'];
          				$css_class_names = array_map( function ($item) {
																						return strtolower(sanitize_html_class(str_replace(' ', '-', $item)));
																					}, explode(', ', $pcf_val[0]));
									// Add sanitized versions to CSS class list
          				foreach($css_class_names as $name):
          					$tax .= ' ' . $name;
          				endforeach;
          		endif;
          	endforeach;
          endforeach;
          ?>
 
          <div class="all instructor-item <?=$tax?>">
                        <?php 
                            $title = the_title_attribute(array('echo' => false)); 
                            $thumbnail_id = get_post_thumbnail_id();
                        ?>
						<a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(array('echo' => true)); ?>" >
						<div class="thumbnail ">
							<?php
							if (!empty(get_the_post_thumbnail( array('attachment' => $thumbnail_id)))):
                                $image_url = get_the_post_thumbnail( array(
                                        'attachment'    => $thumbnail_id,  // Int: Image post ID
                                        'size'          => 'instructor_image_size',                    // Str: WP defined image size
                                        'width'         => '',                               // Int: Custom image width
                                        'height'        => '',                               // Int: Custom image height
                                        'crop'          => 'center-center',                     // Str: Crop location
                                        'alt'           => $title,                              // Str: Custom alt tag for image
                                        'class'         => 'instructor-thumbnail',              // Str: Add custom classes
                                        'return'        => 'html',                              // Str: Return html or src
                                        'style'         => '',                                  // Str: Adds inline styles
                                        'retina'        => true,                                // Bool: Check if retina is enabled
                                    ) );
                                echo $image_url;
							else: ?>
							    <?php $image_url = MZOO_INSTRUCTOR_URL . 'img/default-person.png'; ?>
								<img width="300" height="300" src="<?= MZOO_INSTRUCTOR_URL ?>img/default-instructor.png" alt="<?php the_title(); ?>" />
							<?php
							endif;
							?>
						</div>
						</a>
						<h2 class="staff-entry-title entry-title" style="font-weight:400;text-align:center"><?php the_title(); ?></h2>
						<script type="application/ld+json">
                        {
                         "@context": "http://schema.org",
                         "@type": "Person",
                         "name": "<?php the_title(); ?>",
                         "worksFor": {
                         "@type": "Organization",
                         "name": "mZoo Home Health"
                         }
                        }
                        </script>
						<?php /* ?>
						<div style="max-width:300px;"
							<span><?php echo the_excerpt(); ?></span><br />
							<?php
							foreach($cf_values as $key=>$val): ?>
								<span><?php echo $val['display_name'] . ": " . $val['content']; ?></span><br />
							<?php endforeach; ?>
						</div>
						<?php */ ?>
          </div> 
          
          <?php
      endwhile; 
      
      //reset the global post data
			wp_reset_postdata();
	?>
 
   </div><!-- #instructors -->

</div><!-- #page -->
<?php else: ?>

 <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>

<?php endif;  ?>



<?php
// TODO: Consider proper location for these functions.
function mzoo_sanitize_array($array){
	return array_map( function($item) {
			return strtolower(sanitize_html_class(str_replace(' ', '-', $item)));
		}, $array);
}

function mzoo_css_string_from_term_items($post_object) {
	if ( $post_object && ! is_wp_error( $post_object ) ) : 

			$links = '';

			foreach ( $post_object as $term ) {
					$links .= ' ' . $term->slug;
			}
			return $links;					
	endif; 
	return '';
}

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function get_image_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();

	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
			$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
			$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
				'height' => $_wp_additional_image_sizes[ $_size ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
			);
		}
	}
	return $sizes;
}
?>
<?php get_footer(); ?>
