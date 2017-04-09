<?php

//load parent style
add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});


//by default, tsml should show meetings in a 1 mile radius
$tsml_defaults['distance'] = 1;


//remove sidebar
add_action('widgets_init', function(){
	unregister_sidebar('sidebar-1');

    register_sidebar(array(
		'name' => 'Home Row Two',
		'id' => 'home-2',
		'description' => 'Widgets for the second row on the home page with two slots',
		'before_widget' => '<div id="%1$s" class="column %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
    ));

    register_sidebar(array(
		'name' => 'Home Row Three',
		'id' => 'home-3',
		'description' => 'Widgets for the third row on the home page with three slots',
		'before_widget' => '<div id="%1$s" class="column %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
    ));

	register_sidebar( array(
		'name'          => 'Service Sidebar',
		'id'            => 'content-service',
		'description'   => 'Additional sidebar that appears on the right of the Service section pages.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
	
}, 11);
