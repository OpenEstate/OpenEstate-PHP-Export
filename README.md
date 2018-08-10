OpenEstate-PHP-Export 1.7.x
===========================

OpenEstate-PHP-Export was developed as a part of the freeware real estate
software [OpenEstate-ImmoTool](http://openestate.org/). When a user exports his
properties to his website in PHP format, these scripts are transferred to the
webspace including real estate data in the `data` folder. This solution started
as an experiment, how to publish real estates from
[OpenEstate-ImmoTool](http://openestate.org/) on a website with as minimal
effort as possible for the user.


Features
--------

-   listing view of multiple real estates (see `index.php`)
    -   filter listings by different criterias (see `include/class.filter_*.php`)
    -   order listings by different criterias (see `include/class.order_*.php`)
-   detailled view of a single real estate (see `expose.php`)
    -   image gallery via [Colorbox](http://www.jacklmoore.com/colorbox/) or [Lightbox2](http://www.lokeshdhakar.com/projects/lightbox2/) (see `include/class.gallery_*.php`)
    -   embedded maps via [OpenStreetMap](http://www.openstreetmap.org/) or [Google Maps](https://www.google.com/) (see `include/class.map_*.php`)
    -   embedded videos via [YouTube.com](http://www.youtube.com/) and some other providers (see `include/class.video_*.php`)
    -   contact form
-   a lot of configuration options (see `config.php` & `myconfig.php`)
-   open source wrapper modules for
    [WordPress](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-WordPress),
    [CMS made simple](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-CMSms),
    [WebsiteBaker / BlackCat CMS / LEPTON CMS](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-WebsiteBaker) &
    [Joomla](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-Joomla)
    available


Requirements
------------

-   client side
    -   [OpenEstate-ImmoTool](http://openestate.org/) 1.0-beta
-   webspace side
    -   PHP 5.6 or newer
    -   [PHP GD extension](http://www.php.net/manual/en/book.image.php)
    -   [PHP mbstring extension](http://www.php.net/manual/en/book.mbstring.php) (optional)
    -   [PHP iconv extension](http://de1.php.net/manual/en/book.iconv.php) (optional)


Third party components
----------------------

-   [PHPMailer](https://github.com/PHPMailer/PHPMailer) v6.0.5
-   [Gettext](https://github.com/oscarotero/Gettext) v4.6.0
-   [Gettext CLDR data](https://github.com/mlocati/cldr-to-gettext-plural-rules) v2.4.0
-   [Punycode](https://github.com/true/php-punycode) v2.1.1


Limitations
-----------

This solution works entirely without a database. Therefore more complex queries
on the exported real estates may not be possible with acceptable performance.
We're planning a complete revision of the PHP-export for the future, that also
provides capabilities to store exported objects into a database like MySQL.


License
-------

[GNU General Public License 3](http://www.gnu.org/licenses/gpl-3.0-standalone.html)


Todo
----

-   complete API documentation in english language
-   make API documentation publicly available (via
    [phpDocumentor](http://www.phpdoc.org/) or [apigen](http://apigen.org/))
