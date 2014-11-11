<?php
/*
  Plugin Name: MarcTV Last Commented Posts
  Plugin URI: http://www.marctv.de/blog/marctv-wordpress-plugins/
  Description: Displays the last commented posts.
  Version: 1.4
  Author: MarcDK
  Author URI: http://www.marctv.de
  License: GPL2
 */


/**
 *
 * Display articles with last commented articles
 *
 * @param int $limit
 * @param string $ul_classes
 * @return string HTML unorded list
 */

function get_last_commented_articles($limit = 6, $ul_classes = '') {
    $results = query_posts_with_recent_comments($limit);
    $html = format_last_commented_list($results, $ul_classes);

    return $html;
}

/**
 *
 * Returns the first approved comment of a post id.
 *
 * @param $post_id
 * @return comment object
 */

function get_first_approved_comment($post_id) {
    $comments = get_comments(array('status' => 'approve', 'post_id' => $post_id, 'number' => 1));
    $comment = $comments[0];

    return $comment;
}

/**
 * Query for posts sorted by the last approved comment without password protected posts and pingbacks.
 *
 * @param $limit
 * @return mixed
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
      AND comment_approved = 1
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

/**
 * @param $results
 * @param $classes
 * @return string
 */
function format_last_commented_list($results, $classes) {
    $html = '<ul class="' . $classes . '">';

    $key = 0;

    foreach ($results as $result) {

        /* first-last classes. I know this could be done better. Don't talk about it. */
        if ($key == 0) {
            $html .= '<li class="box first">';
        } else if ($key == 5) {
            $html .= '<li class="box last">';
        } else if ($key == 3) {
            $html .= '<li class="box multi-last">';
        } else if ($key == 2) {
            $html .= '<li class="box multi-first">';
        } else {
            $html .= '<li class="box">';
        }
        $key++;

        $comment = get_first_approved_comment($result->ID);

        $comment_url = get_comment_link($comment->comment_ID);
        $authorname = $comment->comment_author;

        if (strlen($authorname) > 12) {
            $authorname = substr($authorname, 0, 9) . '...';
        }
        if (has_post_thumbnail($result->ID)) {
            $img_html = wp_get_attachment_image(get_post_thumbnail_id($result->ID), 'medium');
            $teaser_img = preg_replace('/(height)=\"\d*\"\s/', "", $img_html);
        }

        $comment_user = '<a class="comment-teaser inverted" href="' . $comment_url . '">' . '<strong class="fn">' . $authorname . ' </strong> in ' . $teaser_img . ' <div class="title"> ' . get_the_title($result->ID) . ' </div></a>';

        $html .= $comment_user;

        $html .= '</li>';
        if ($key == 6) {
            $html .= '</ul><ul class="' . $classes . '">';
            $key = 0;
        }
    }

    $html .= '</ul>';

    return $html;
}
