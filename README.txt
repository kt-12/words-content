=== Word's Content ===
Contributors: thekt12, tanmaynikam, antonjustin
Donate link: https://kt12.in/donate/
Tags: reduce bounce rate, blogs, content, engagement module, word highlight, analytic, leads,bounce rate
Requires PHP: 5.5 and above
Requires at least: 4.0
Tested up to: 4.9.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Word's Content plugin helps you explain things better and increases reader's engagement.

== Description ==

This plugin helps you mark any word in your post and add some engaging content to it.
With this plugin &mdash;
*   You can explain your words better than ever before.
*   Add highlighting points to validate your facts, add a supporting story to a scenario or an image gallery.
*   With advanced support for shortcodes, you can add related posts, related products, offers &mdash; everything that you can imagine.
*   Support for Contact Form 7 makes it easy to get direct leads from almost every single page.
*   Increase engagement, reduce bounce rate, increase leads through your blog.

== Installation ==

This section describes how to install the plugin and get it working.

1. Follow plugin [installation guidelines](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)
2. Activate `Word's Content by thekt12` plugin through the `Plugins` menu in WordPress
3. In the menu you will now see `Word's Content` menu. Go to `Word's Content` page and click on the `Add New` to add a new word.
4. You can add a group of words or a single word. The word will be saved as lower case in database, though all instance of the word is scanned in the fontend irrespective of the case.
5. You can find `Word's Content` settings page inside ,`Word's content` menu.
6. In setting page you can change the number of repetition of a word to be marked, footer content of the sidebar and also custom css field(if you wish to change to look of the current sidebar).


== Features ==
* **Vue.js For Fast Rendering**- The plugin uses Vue.js for the rendering logic. The sidebar renders almost instantaneously once the data is received. Caching of ajax responses helps ensure that a ajax call is made only once in the frontend.
* **Support for shortcodes**- The content of the words is rendered in the same way a post content is rendered. This allows you to put the same content on the sidebar that you can put on your page or post.
* **Preview as you type**- Word Preview is available in the Add, Edit , List page, which makes it easy to see your output while you create the word.
   > Note: Sidebar's content inherits css of the page, the preview at the backend page will differ from that in the fontend.
* **Support For Contact Form 7**- The plugin also supports CF7 scripts. This allows you to put contact form 7 in any word's content and get leads within any page/post.


== Future Plan ==
* Improve Vue.js part of code.
* Add support for page builder like Visual Composer, Elementor etc.
* International language support.
* Add unit test for both js and php code.
* Setup a build process in GitHub for public contribution.

== Frequently Asked Questions ==

= Is it there on git =

It will soon be, with proper build process in place.

= Which Post Types are supported? =

All public post types are supported. It can also scan descriptions of WooCommerce and other plugin which uses custom post type. This make it a very use powerful tool for creating user engagement strategies.
You can also restrict any post type from being scanned from the settings page.

== Screenshots ==

1. Words Content list page.
2. Creating a new word.
3. Preview in Admin Page.
4. Settings page.
5. Demo page.
6. Markup in Frontend.
7. Sidebar Frontend.
