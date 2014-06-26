<?php
/*
  Plugin Name: MarcTV Last Commented Posts
  Plugin URI: http://www.marctv.de/blog/marctv-wordpress-plugins/
  Description: Displays the last commented posts.
  Version: 1.2.1
  Author: MarcDK
  Author URI: http://www.marctv.de
  License: GPL2
 */


/**
 * Display articles with last commented articles
 *
 */

function get_last_commented_articles($limit = 6 , $ul_classes = '') {

    $results = query_posts_with_recent_comments($limit);
    $html = format_last_commented_list($results, $ul_classes);

  return $html;
}

/**
 * Query for posts sorted by the last approved comment without password protected posts and pingbacks.
 *
 */

function query_posts_with_recent_comments($limit) {

  global $wpdb;

  $query = "select
   wp_posts.*,
   coalesce((
   select
      max(comment_date)
   from
      $wpdb->comments wpc
   where
      wpc.comment_post_id = wp_posts.id
      and comment_approved = '1'
      AND comment_type = ''
      AND post_password = ''                  ),
   wp_posts.post_date  ) as mcomment_date
from
   $wpdb->posts wp_posts
where
   post_type = 'post'
   and post_status = 'publish'
   and comment_count > '0'
order by
   mcomment_date desc  limit $limit";

  $query_result = $wpdb->get_results($query);

  return $query_result;
}

function format_last_commented_list($results, $classes) {
  $html = '<ul class="' . $classes . '">';

  $key = 0;

  foreach ($results as $result) {

    /* first-last classes. I know this could be done better. Don't talk about it. */
    if ($key == 0) {
      $html.= '<li class="box first">';
    }
    else if ($key == 5) {
      $html.= '<li class="box last">';
    }
    else if ($key == 3) {
      $html.= '<li class="box multi-last">';
    }
    else if ($key == 2) {
      $html.= '<li class="box multi-first">';
    }
    else {
      $html.= '<li class="box">';
    }
    $key++;

    $comments = get_comments(array('post_id' => $result->ID, 'number' => 1));

    foreach ($comments as $comment) {
      $comment_url = get_comment_link($comment->comment_ID);
      $authorname = $comment->comment_author;

      if (strlen($authorname) > 12) {
        $authorname = substr($authorname, 0, 9) . '...';
      }
      if (has_post_thumbnail($result->ID)) {
    $img_html = wp_get_attachment_image(get_post_thumbnail_id($result->ID), 'medium');
    $teaser_img = preg_replace('/(height)=\"\d*\"\s/', "", $img_html);


    }
      $comment_user = '<a class="inverted" href="' . $comment_url . '" class="comment-teaser">' . '<strong class="fn">' . $authorname . ' </strong> in ' . $teaser_img . ' <div class="title"> ' . get_the_title($result->ID) . ' </div></a>';
    }

    $html.= $comment_user;

    $html.= '</li>';
    if ($key == 6) {
      $html .= '</ul><ul class="' . $classes . '">';
      $key = 0;
    }
  }

  $html.= '</ul>';

  return $html;
}
