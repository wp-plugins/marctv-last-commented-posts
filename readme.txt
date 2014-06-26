=== MarcTV Last Commented Post ===
Contributors: MarcDK
Tags: marctv, comments, recent comments, last commented post
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: 1.2
License: GPLv2

== Description ==

Displays the last commented posts as a unordered list.

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

= 1.2 =

* Added post thumb

= 1.0 =

* First version.

