<?php

//get latest point tag
$tag = array_pop(get_tags(array(
  'hide_empty' => true,
  'search' => 'Point',
  'number' => 0,
	'orderby' => 'slug',
)));

//start page output
$page = '
<!doctype html>
<html lang="en">
  <head>
    <title>' . $tag->name . '</title>
    ' . wp_head() . '
    <style type="text/css">
      body { background-color: white; padding: 2rem; font-size: 12px; }
      section { margin: 3rem auto; max-width: 800px; }
      header { display:flex;flex-direction:column;align-items:center }
      header a.noslimstat img { margin: 0 !important; }
      header img { max-width: 400px !important; height: auto; }
      header h1 { font-size: 2rem; margin: .5rem; }
      header h5 { margin: 0 0 1rem; }
      #Smallchat { display: none; }
      article .wp-block-image {
        display: block !important;
        float: right;
        margin-left: 20px;
      }
      article .wp-block-image img {
        max-width: 200px;
        max-height: 400px;
        width: auto;
        height: auto;
      }
      .wp-block-image figcaption {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <section>
      <header>';

//front page
$post = get_post(555264);

$page .= '
        <h1>' . $title . '</h1>
        <div>
          ' . $post->post_content . '
        </div>
      </header>';

//get all posts
$posts = $wpdb->get_results('SELECT
    p.ID,
    p.post_date,
    p.post_title,
    p.post_content
  FROM wpaxzy_posts p
  JOIN wpaxzy_term_relationships r ON p.ID = r.object_id
  JOIN wpaxzy_term_taxonomy x ON r.term_taxonomy_id = x.term_taxonomy_id
  WHERE p.post_type = "post"
    AND p.post_status = "publish"
    AND x.term_id = ' . $tag->term_id . '
  ORDER BY p.menu_order');

foreach ($posts as $post) {
  if (!empty(trim(strip_tags($post->post_content)))) {
    $page .= '<article>
    <h2>' . $post->post_title . '</h2>' .
    $post->post_content .
        '</article>';
  }
}

$page .= '</section>
    <script>
      $(function(){
        $(".printfriendly a").click();
      });
    </script>
  </body>
</html>';

echo $page;

///die(get_tag_link($point_term_id));
