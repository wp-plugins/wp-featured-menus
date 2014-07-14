=== WP Featured Menus ===
Contributors: topher1kenobe
Tags: posts, pages, menus, featured
Requires at least: 3.0
Tested up to: 3.9.1
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides a metabox on posts and pages listing existing WordPress Menus.

== Description ==

This plugin provides a metabox on posts and pages listing existing WordPress Menus.  The end user is allowed to choose one and make it associated with the post or page via meta data.

Practically speaking, Featured Menus work exactly like Featured Images.  The Post or Page and Featured Menu are merely attached, and you must use a template tag or WordPress functions to render the Menu.

Please see <a href="http://wordpress.org/plugins/wp-featured-menus/other_notes/">Other Notes</a> for examples.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the `wp-featured-menus` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create new or Edit a Post.

== Usage ==

Page or Post meta has a key called `_wp_featured_menu`.  A very simple way to render the menu is like this:

`<?php
	$meta = get_post_custom();
	wp_nav_menu( array( 'menu' => $meta['_wp_featured_menu'][0] ) );
?>`

A better way might be to test for the value first:

`<?php
	$meta = get_post_custom();
	if ( is_numeric( $meta['_wp_featured_menu'][0] ) ) {
		wp_nav_menu( array( 'menu' => $meta['_wp_featured_menu'][0] ) );
	}
?>`

== Frequently Asked Questions ==

= Why don't you have more questions here? =

I haven't been asked any yet.  :)

== Screenshots ==

1. The Featured Menus meta box when you *do not* have any Menus created in WordPress.

2. The Featured Menus meta box when you *do* have Menus created in WordPress.

== Changelog ==

= 1.2 =
* ONE MORE variable change.  Last one, I promise.

= 1.1 =
* change meta key name to not start with _wp
* change main class name to not start with WP

= 1.0 =
* Initial release.
