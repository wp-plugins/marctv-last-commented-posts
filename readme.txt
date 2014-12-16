=== MarcTV Last Commented Posts ===
Contributors: MarcDK
Tags: marctv, comments, recent comments, last commented post
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 1.4
License: GPLv2

== Description ==

Provides a new function that returns an unordered list of the last commented articles and their comments.

== Installation ==

* Upload the plugin to your blog
* Activate it.
* Use this in your template files:
`
    <?php
      if (function_exists('get_marctv_last_commented_articles')) {
        echo get_last_commented_articles();
      }
     ?>
`
For advanced usage use the first parameter for the number of posts and the second for custom classes.

`
    <?php
      if (function_exists('get_marctv_last_commented_articles')) {
        echo get_last_commented_articles(6, 'container multi nohover showontouch');
      }
     ?>
`


== Changelog ==

= 1.4 =

* Removed unapproved comments from the query.

= 1.2 =

* Added post thumb

= 1.0 =

* First version.

