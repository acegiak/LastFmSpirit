=== LastFmSpirit ===
Contributors: acegiak, tijsverkoyen
Donate link: http://acegiak.net/2014/07/16/last-fm-wordpress-plugin/
Tags: last.fm,music,charts,api
Requires at least: 2.0.2
Tested up to: 3.5
Stable tag: trunk

Use Wordpress Shortcodes to display data from the Last.Fm api in charts.

== Description ==

LastFmSpirit is a free Plugin for Wordpress.

It allows users to use Wordpress Shortcodes to create tables and charts of data from the Last.Fm Api: http://www.last.fm/api

It is written by Ashton McAllan: http://acegiak.net

It is based on the LastFm library by tijsverkoyen: https://github.com/tijsverkoyen/LastFM

== Usage == 

To use the plugin you will need to enter your Last.Fm Api Key and Secret on the LastFmSpirit options page.<br/>
You can get these by registering your application here: http://www.last.fm/api/accounts

Shortcodes take the form: [LastFmChart attributeName="value"]apifunction[/LastFmChart]
EG: [LastFmChart user="acegiak"]user.getWeeklyTrackChart[/LastFmChart]

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Unzip and upload the files to the `/wp-content/plugins/LastFmSpirit/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your API Key and Secret on the LastFmSpirit Options Page. You can get these from : http://www.last.fm/api/accounts


== Frequently Asked Questions ==

= Why does it complain about the Api Key Or Secret? =

In the options page enter your API Key and Secret on the LastFmSpirit Options Page. You can get these from : http://www.last.fm/api/accounts

== Changelog ==

= 1.0 =
* Initial Release
