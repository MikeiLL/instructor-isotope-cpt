<?php
namespace mZoo\Instructors;

class MZOO_Instructors {
	
	/**
	 * Register Instructor post type.
	 *
	 * @since 1.0.0
	 */
	public static function register_post_type() {

		// Get values and sanitize
		$name          = 'Instructors';
		$singular_name = 'Instructor';
		$slug          = 'instructors';
		$menu_icon     = 'groups';

		// Declare args and apply filters
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $singular_name,
				'add_new' => __( 'Add New', 'mz_instructors' ),
				'add_new_item' => __( 'Add New Item', 'mz_instructors' ),
				'edit_item' => __( 'Edit Item', 'mz_instructors' ),
				'new_item' => __( 'Add New Instructor Item', 'mz_instructors' ),
				'view_item' => __( 'View Item', 'mz_instructors' ),
				'search_items' => __( 'Search Items', 'mz_instructors' ),
				'not_found' => __( 'No Items Found', 'mz_instructors' ),
				'not_found_in_trash' => __( 'No Items Found In Trash', 'mz_instructors' )
			),
			'public' => true,
			'supports' => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				// 'comments',
				// 'custom-fields',
				'revisions',
				'author',
				'page-attributes',
			),
			'capability_type' => 'post',
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
			'has_archive' => 'instructors',
			'menu_icon' => 'dashicons-'. $menu_icon,
			'menu_position' => 20,
			'taxonomies' => array('languages_tag'),
		);

		// Register the post type
		register_post_type( 'instructor', $args );

	}

	/**
	 * Register Instructor tags.
	 *
	 * @since 1.0.0
	 */
	public static function register_tags() {

		// Define and sanitize options
		$name = __( 'Instructor Class Types', 'mz_instructors' );
		$slug = 'instructor-class-types';

		// Define args and apply filters for child theming
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => __( 'Search Instructor Tags', 'mz_instructors' ),
				'popular_items' => __( 'Popular Instructor Tags', 'mz_instructors' ),
				'all_items' => __( 'All Instructor Tags', 'mz_instructors' ),
				'parent_item' => __( 'Parent Instructor Tag', 'mz_instructors' ),
				'parent_item_colon' => __( 'Parent Instructor Tag:', 'mz_instructors' ),
				'edit_item' => __( 'Edit Instructor Tag', 'mz_instructors' ),
				'update_item' => __( 'Update Instructor Tag', 'mz_instructors' ),
				'add_new_item' => __( 'Add New Instructor Tag', 'mz_instructors' ),
				'new_item_name' => __( 'New Instructor Tag Name', 'mz_instructors' ),
				'separate_items_with_commas' => __( 'Separate instructor tags with commas', 'mz_instructors' ),
				'add_or_remove_items' => __( 'Add or remove instructor tags', 'mz_instructors' ),
				'choose_from_most_used' => __( 'Choose from the most used instructor tags', 'mz_instructors' ),
			),
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => false,
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
			'query_var' => true
		);

		// Register the instructor tag taxonomy
		register_taxonomy( 'instructor_class_types', array( 'instructor' ), $args );

	}		
	
		/**
	 * Register Instructor category.
	 *
	 * @since 1.0.0
	 */
	public static function register_categories() {

		// Define and sanitize options
		$name = __( 'Instructor Categories', 'mz_instructors' );
		$slug = 'instructor-category';

		// Define args and apply filters for child theming
		$args = array(
			'labels' => array(
				'name' => $name,
				'singular_name' => $name,
				'menu_name' => $name,
				'search_items' => __( 'Search','mz_instructors' ),
				'popular_items' => __( 'Popular', 'mz_instructors' ),
				'all_items' => __( 'All', 'mz_instructors' ),
				'parent_item' => __( 'Parent', 'mz_instructors' ),
				'parent_item_colon' => __( 'Parent', 'mz_instructors' ),
				'edit_item' => __( 'Edit', 'mz_instructors' ),
				'update_item' => __( 'Update', 'mz_instructors' ),
				'add_new_item' => __( 'Add New', 'mz_instructors' ),
				'new_item_name' => __( 'New', 'mz_instructors' ),
				'separate_items_with_commas' => __( 'Separate with commas', 'mz_instructors' ),
				'add_or_remove_items' => __( 'Add or remove', 'mz_instructors' ),
				'choose_from_most_used' => __( 'Choose from the most used', 'mz_instructors' ),
			),
			'public' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => $slug, 'with_front' => false ),
			'query_var' => true
		);

		// Register the instructor category taxonomy
		register_taxonomy( 'instructor_category', array( 'instructor' ), $args );

	}


	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 1.0.0
	 */
	public static function edit_columns( $columns ) {
		if ( taxonomy_exists( 'instructor_category' ) ) {
			$columns['instructor_category'] = esc_html__( 'Category', 'mz_instructors' );
		}
		if ( taxonomy_exists( 'instructor_class_types' ) ) {
			$columns['instructor_class_types'] = esc_html__( 'Tags', 'mz_instructors' );
		}
		return $columns;
	}


	/**
	 * Adds columns to the WP dashboard edit screen.
	 *
	 * @since 1.0.0
	 */
	public static function column_display( $column, $post_id ) {

		switch ( $column ) :

			// Display the instructor categories in the column view
			case 'instructor_category':

				if ( $category_list = get_the_term_list( $post_id, 'instructor_category', '', ', ', '' ) ) {
					echo $category_list;
				} else {
					echo '&mdash;';
				}

			break;

			// Display the instructor tags in the column view
			case 'instructor_class_types':

				if ( $tag_list = get_the_term_list( $post_id, 'instructor_class_types', '', ', ', '' ) ) {
					echo $tag_list;
				} else {
					echo '&mdash;';
				}

			break;

		endswitch;

	}
	
	/**
	 * Adds taxonomy filters to the instructor admin page.
	 *
	 * @since 2.0.0
	 */
	public static function tax_filters() {
		global $typenow;
		$taxonomies = array( 'instructor_category', 'instructor_class_types' );
		if ( 'instructor' == $typenow ) {
			foreach ( $taxonomies as $tax_slug ) {
				if ( ! taxonomy_exists( $tax_slug ) ) {
					continue;
				}
				$current_tax_slug = isset( $_GET[$tax_slug] ) ? $_GET[$tax_slug] : false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms($tax_slug);
				if ( count( $terms ) > 0) {
					echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
					echo "<option value=''>$tax_name</option>";
					foreach ( $terms as $term ) {
						echo '<option value=' . $term->slug, $current_tax_slug == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
					}
					echo "</select>";
				}
			}
		}
	}

	/**
	 * Flush re-write rules
	 *
	 * @since 1.0.0
	 * Not Using at the moment
	 */
	public static function flush_rewrite_rules() {
		$screen = get_current_screen();
		echo $screen . "<br / >";
		die();
		if ( $screen->id == 'instructor_page_mzoo-instructor-editor' ) {
			flush_rewrite_rules();
		}

	}

	/**
	 * Pull in our archive template
	 *
	 * @since 1.0.0
	 * source: http://wordpress.stackexchange.com/a/89832/48604
	 */	

	public static function get_custom_template($template) {
			global $wp_query;
			if (is_post_type_archive('instructor')) {
					$templates[] = 'archive-instructors.php';
			} elseif (is_singular('instructor')) {
					$templates[] = 'instructor.php';
			} else {
				// do nothing
				return;
			}
			$template = self::locate_plugin_template($templates);
			return $template;
	}
	
	/**
	 * Search theme for template, then use the plugin on if not found.
	 *
	 * @since 1.0.0
	 * source: http://wordpress.stackexchange.com/a/89832/48604
	 */	
	public static function locate_plugin_template($template_names, $load = false, $require_once = true ) {
    if (!is_array($template_names)) {
        return '';
    }
    $located = '';  
    foreach ( $template_names as $template_name ) {
        if ( !$template_name )
            continue;
        if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
            $located = STYLESHEETPATH . '/' . $template_name;
            break;
        } elseif ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
            $located = TEMPLATEPATH . '/' . $template_name;
            break;
        } elseif ( file_exists( MZOO_INSTRUCTOR_DIR . 'templates/' . $template_name) ) {
            $located =  MZOO_INSTRUCTOR_DIR . 'templates/' . $template_name;
            break;
        }
    }
    if ( $load && $located != '' ) {
        load_template( $located, $require_once );
    }
    return $located;
	}
	
	/**
	 * Search theme for template, then use the plugin on if not found.
	 *
	 * TODO: Consider making this true.
	 *
	 * @since 1.0.0
	 * 
	 */	
	public static function instructor_template($single) {
    global $wp_query, $post;
    /* Checks for single template by post type */
    if ($post->post_type == "instructor"){
        if(file_exists(MZOO_INSTRUCTOR_DIR . 'templates/instructor.php'))
            return MZOO_INSTRUCTOR_DIR . 'templates/instructor.php';
    }
    return $single;
	}
	
	/**
	 * Display slider in a post or page
	 *
	 * @since 1.0.0
	 *
	 * This is mainly just a place to store this code block, which isn't being
	 * used at this time.
	 * 
	 */	
	public static function display_slider() {
        $args = array(
        'post_type' => 'instructors', 
        'post_status' => 'publish',
        'fields' => 'id',
        'posts_per_page' => -1
        );
        $query = new WP_Query($args); 
        ?>
        <div id="meet-our-instructors" class="homepage-block clr">
        <h2>Meet Our Instructors now</h2>
            <div class="instructor-wrapper homepage-block clr">
                <div class="instructor-slider">
                    <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                        <span>
                            <a href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute(array('echo' => false)); ?>" >
                            <?php
                            $link = get_category_link( $term->term_id );
                            $title = the_title_attribute(array('echo' => false));
                            $thumbnail_id = wpex_get_term_thumbnail_id($term->term_id);
                            // Source: https://wpexplorer-themes.com/total/docs/total-post-thumbnail-featured-image-helper-functions/
                                // Make this work with php5.4
                                $thing = wpex_get_post_thumbnail( array('attachment' => $thumbnail_id));
                                if (!empty($thing)):
                                    $thumbnail = wpex_get_post_thumbnail( array(
                                                'attachment'    => $thumbnail_id,  // Int: Image post ID
                                                'size'          => 'cpt_image_size',                    // Str: WP defined image size
                                                'width'         => '',                               // Int: Custom image width
                                                'height'        => '',                               // Int: Custom image height
                                                'crop'          => 'center-center',                     // Str: Crop location
                                                'alt'           => $title,                              // Str: Custom alt tag for image
                                                'class'         => 'cpt-entry-media-link',              // Str: Add custom classes
                                                'return'        => 'html',                              // Str: Return html or src
                                                'style'         => '',                                  // Str: Adds inline styles
                                                'retina'        => true,                                // Bool: Check if retina is enabled
                                            ) );
                                        else: ?>
                                            <img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/default-person.png" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"/>
                                        <?php
                                        endif;
                                        ?>
                                    </a>
                                </span>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </div>
    <?php
    }
    
    /**
	 * Add & remove image sizes from the "Image Sizes" panel
	 *
	 * @since 1.0.0
	 * source: https://wpexplorer-themes.com/total/snippets/addremove-image-sizes/
	 */	
    public static function instructor_image_sizes( $sizes ) {
        
        // Add new image size "my_image_sizes"
        $sizes['instructor_image_size'] = array(
            'label'     => __( 'Image sizes for Instructors Page', 'wpex' ), // Label
            'width'     => 'instructor_image_size_width', // id for theme_mod width
            'height'    => 'instructor_image_size_height', // id for theme_mod height
            'crop'      => 'instructor_image_size_crop', // id for theme_mod crop
        );

        // Return sizes
        return $sizes;

    }


} // EOF MZOO_Instructors
?>