<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content">

	<div id="primary" class="content-area">
		<div id="content" role="main">
		
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>

			<div class="hentry">
				<div class="widgets">
						
					<div class="home-column-row two-columns">
						<?php dynamic_sidebar('home-2')?>
					</div>
					
					<div class="home-column-row three-columns">
						<?php dynamic_sidebar('home-3')?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
get_sidebar();
get_footer();
