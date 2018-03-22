<?php
use mZoo\Instructors as AC;

get_header(); 
while ( have_posts() ) : the_post(); ?>
  <div id="content-wrap" class="container clr">
		<div id="primary" class="content-area clr">
			<div id="content" class="site-content clr">
				<div id="single-blocks" class="wpex-clr">
					<article class="single-staff-content entry clr" itemprop="text">
						<nav class="site-breadcrumbs wpex-clr">
							<span class="breadcrumb-trail">
								<span class="trail-begin" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">
									<a href="<?php echo home_url( '/featured-instructors/' )/* get_post_type_archive_link( 'instructors' ); */ ?>" title="Instructors" rel="home" itemprop="url"><span class="fa fa-long-arrow-left"></span> Instructors</a>
								</span>
							</span>
						</nav>
						<div class="single-page-post-heading">
							<h1><?php the_title(); ?></h1>
						</div>
						<div class="content-here">
							<div class="thumbnail" style="float:right">
								<?php
								// php 5.4 shim
								$thumbnailID = get_post_thumbnail_id();
								$thing = wp_get_attachment_url( $thumbnailID );
								if (!empty($thing)):
									the_post_thumbnail('medium', array(
													//'class' => 'alignright',
													'alt'    => the_title_attribute(array( 'after' => ' Portrait', 'echo' => false)),
												) );
								else: ?>
									<img src="<?= MZOO_INSTRUCTOR_URL ?>img/default-person.png" alt="<?php the_title(); ?>" />
								<?php
								endif;
								?>
							</div>
							<?php the_content(); ?>
						</div>
						<?php
						$content = get_the_content();
						?>
						<script type="application/ld+json">
                        {
                            "@context": "http://schema.org/",
                            "@type": "Person",
                            "name": "<?php the_title(); ?>",
                            "description": "<?php echo wp_filter_nohtml_kses($content); ?>",
                            "worksFor": {
                                "@type": "Organization",
                                "name": "mZoo Home Health"
                            }
                        }
                    </script>
						<div>
						<?php
						$post_custom_fields = get_post_custom($post->ID);
						// Loop through our custom fields and for each that matches
						// add it to an array of custom field values for this post
						$cf_values = array();
						foreach (AC\MZOO_Custom_Fields::$custom_fields as $cf_key => $cf_val):
							foreach ($post_custom_fields as $pcf_key => $pcf_val):
								if($pcf_key == $cf_key):
									$cf_values[$pcf_key]['content'] = $pcf_val;
          				$cf_values[$pcf_key]['display_name'] = $cf_val['display_name'];
								endif;
							endforeach;
						endforeach;
						foreach($cf_values as $key=>$val): ?>
							<span><?php echo $val['display_name']; ?>: <?php echo join(', ', $val['content']) ?></span><br />
						<?php endforeach; ?>
						<?php
						echo AC\MZOO_Custom_Taxonomies::show_tax('languages_tag', 'Languages Spoken: ', '', '1', ', ');
						?> <br /> <?php
						echo AC\MZOO_Custom_Taxonomies::show_tax('specializations_tag', 'Specializations: ', '', '1', ', ');
						?>
						</div>
					</div>
				</div>
			</div>
		</div>


<?php endwhile; ?>

<?php get_footer(); ?>

               