=== Force Domain Redirect ===
Contributors: nimbusdigital
Tags: domain,redirect,301,url,uri,rewrite
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: trunk
License: GP2

Forces your Wordpress site to load on the domain registered in the WP admin.

== Description ==
Redirects http://somedomain.tld to http://someotherdomain.tld

Assuming you have 2 or more domains in use for a single Wordpress site. This plugin forces the use of only one no matter which is entered in the address bar.

If, for some reason, your host won’t automatically redirect from one domain to another, then this plugin will do it. It checks the URL used to access the site and does a 301 redirect if incorrect.

In theory htaccess should do this, but in my case it didn’t. Perhaps because I am using domain aliases (or CNAMEs) and I wanted to insure that the main one was always in use, even when accessed using old links.

== Installation ==
Copy the plugin file to your Wordpress plugins directory. That\'s it. No configuration necessary... just make sure the domain you want to force is listed under SETTINGS > GENERAL > WordPress Address (URL)

== Changelog ==
0.2 Update to handle https
0.1 Initial version. Seems to do what\'s needed.