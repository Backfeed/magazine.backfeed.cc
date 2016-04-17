# Set up

When cloning this repository, make sure git submodules are being cloned as well:

`git clone --recursive https://github.com/Backfeed/magazine.backfeed.cc.git`

Set up a WordPress database and import into it the SQL dump `wordpress.sql` located in this folder.

Create a file `local-config.php` in this folder to teach WordPress how to access the database:

```
<?php
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'OoSF6oT6oo');

/** MySQL hostname */
define('DB_HOST', 'localhost');

define('WP_HOME','http://magazine.local/');
define('WP_SITEURL','http://magazine.local/');

/** These will save your life while debugging. The log is at wp-content/debug.log */
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

/** You must set these so that you don't pollute the production protocol database */
define('BACKFEED_API_URL', 'https://api.backfeed.cc/dmagdev/');
define('BACKFEED_API_KEY', 'a07p7SZET031dlkXGxSTy5jyUUB73uQk1ECAsQXs');
```

Check out `http://magazine.local/` to see if you see the magazine.

You can use the following admin credentials to access the admin dashboard:

```
https://magazine.local/wp-admin/
backfeed
feed4444
````

Follow [the instructions in the plugin repository](https://github.com/Backfeed/d-magazine#install-d-magazine) to configure the plugin for initial use.

Go to the WordPress plugins list at `wp-admin/plugins.php` and make sure `D-Magazine` is activated.

# Use
Few folders are relevant at all for contributers to this repository:

* `wp-content/themes/barcelona-child`: contains all code specific to the BF Magazine theme. Not reusable in any other theme and thus shouldn't be packed with the plugin.
* `wp-content/plugins/d-magazine`: the plugin.


