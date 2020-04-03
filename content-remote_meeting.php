<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">	
			<?php
	
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h1 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h1>' );
			endif;
		
			
			?>
	</header><!-- .entry-header -->
	
	<div class="entry-content">
	
	<!-- custom fields here -->						
<div id="remote-meeting-entry"> 
 
<?php 
    $monday = get_post_meta( get_the_ID(), 'monday', true);
    $tuesday = get_post_meta( get_the_ID(), 'tuesday', true);
    $wednesday = get_post_meta( get_the_ID(), 'wednesday', true);
    $thursday = get_post_meta( get_the_ID(), 'thursday', true);
    $friday = get_post_meta( get_the_ID(), 'friday', true);
    $saturday = get_post_meta( get_the_ID(), 'saturday', true);
    $sunday = get_post_meta( get_the_ID(), 'sunday', true);
	$meeting_arm = get_post_meta( get_the_ID(), 'meeting_name_arm', true);
	$platform = get_post_meta( get_the_ID(), 'platform_arm', true);
	$link_arm = get_post_meta( get_the_ID(), 'link_to_meeting_arm', true);
	$add_access = get_post_meta( get_the_ID(), 'additional_access_details_arm', true);
	$format_spec = get_post_meta( get_the_ID(), 'meeting_format_specifics_arm', true);
	$sev_trad_plat = get_post_meta( get_the_ID(), 'seventh_platform_arm', true);
	$sev_trad = get_post_meta( get_the_ID(), 'seventh_trad_link_arm', true);
	$script_link = get_post_meta( get_the_ID(), 'script_arm', true);
	
	 // taxonomy
                                $term_designation = get_the_terms(get_the_ID(), 'designation');
                                $designation_string = join(', ', wp_list_pluck($term_designation, 'name'));

     // origin
                                $term_origin = get_the_terms(get_the_ID(), 'origin');
                                $origin_string = join(', ', wp_list_pluck($term_origin, 'name'));
 
		if( ! empty( $monday ) ) {
		echo 'Monday: ' . $monday . '</p>';
	}
			if( ! empty( $tuesday) ) {
		echo 'Tuesday: ' . $tuesday. '</p>';
	}
		if( ! empty( $wednesday ) ) {
		echo 'Wednesday: ' . $wednesday. '</p>';
	}
		if( ! empty( $thursday) ) {
		echo 'Thursday: ' . $thursday . '</p>';
	}
		if( ! empty( $friday ) ) {
		echo 'Friday: ' . $friday . '</p>';
	}
		if( ! empty( $saturday ) ) {
		echo 'Saturday: ' . $saturday . '</p>';
	}
		if( ! empty( $sunday ) ) {
		echo 'Sunday: ' . $sunday . '</p>';
	}
	if( ! empty( $platform ) ) {
		echo '<p><label>Platform:</label> ' . $platform . '</p>';
	}
	
	if( ! empty( $link_arm ) ) {
		echo '<p><label>Link to Meeting:</label> <a href="' . $link_arm . '" target="_blank">' . $link_arm . '</a></p>';
	}
	
	if( ! empty( $add_access ) ) {
		echo '<p><label>Access Information:</label> ' . $add_access . '</p>';
	}
	
	if( ! empty( $format_spec ) ) {
		echo '<p><label>Format Information:</label> ' . $format_spec . '</p>';
	}
	
	if( ! empty( $sev_trad_plat ) ) {
		echo '<p><label>Seventh Tradition Platform:</label> ' . $sev_trad_plat. '<label>Link/Handle:</label>' . $sev_trad . '</p>';
	}
	
	if( ! empty( $script_link ) ) {
		echo '<p><label>Script Link:</label> ' . $script_link . '</p>';
	}
 
   if(!empty($designation_string)) {
        echo '<p><label>Designation: </label> ' .$designation_string . '</p>';
    }

   if(!empty($origin_string)) {
        echo '<p><label>Origin: </label> ' .$origin_string . '</p>';
    }
 
?>

<div id="arm-image"><?php twentyfourteen_post_thumbnail(); ?></div>

	</div>

 
<!-- End custom fields -->

<div id="remote-return"><a href="../online-meetings"> < Return to Remote Meetings List</a></div>
	

		</div><!-- .entry-content -->
	

	<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>

</article><!-- #post-<?php the_ID(); ?> -->