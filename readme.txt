=== WP EDD Addons API ===
Author URI: http://alessandrotesoro.me
Plugin URI: http://alessandrotesoro.me
Contributors: alessandro.tesoro
Tags: edd, easy digital downloads, rest, rest api, freemium addons
Requires at least: 4.3
Tested up to: 4.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.0.0

Provides the API required to expose EDD products marked as add-ons through the REST API.

== Description ==

This is a plugin built specifically for developers that use Easy Digital Downloads to sell add-ons for freemium WordPress plugins.

It's often the case that freemium plugins have an "add-ons" page within the wp-admin panel that shows a list of all add-ons available for a plugin. In most cases the list is retrieved from an RSS or json feed.

WP EDD Addons API provides an easy way to mark EDD products as "add-ons" and exposes all of them through a new route within the WordPress REST API without the need to manually code the feed.

Once installed, all products marked as add-ons are available through the `wp-json/wp/v2/edd-addons` route.

Example:
`http://example.com/wp-json/wp/v2/edd-addons`

= Some info for developers =

* All add-ons are cached into the `wp_edd_addons_api_cached` transient. The transient is deleted each time a download is updated or created.
* You can modify the query $args for the REST API through the filter `wp_edd_addons_api_query`.
* I've also built an example of class to build the add-ons page within a plugin, you can find it here [https://github.com/alessandrotesoro/wp-edd-addons-page](https://github.com/alessandrotesoro/wp-edd-addons-page)

== Installation ==

* Install the WP REST API plugin first.
* Install the plugin like any other WP plugin.
* Create or edit a download and enable the option "Set this product as an addon".

All products marked as add-ons are available through the `wp-json/wp/v2/edd-addons` route.

Example:
`http://example.com/wp-json/wp/v2/edd-addons`

== Frequently Asked Questions ==

None yet.

== Screenshots ==

1. json output example from the api.
2. Option to set downloads as add-ons.

== Changelog ==

= 1.0.0 =
Just released ;)
