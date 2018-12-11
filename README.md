OpenEstate-PHP-Export 1.5.35
============================

OpenEstate-PHP-Export was developed as a part of the freeware real estate
software [OpenEstate-ImmoTool](http://openestate.org/). When a user exports his
properties to his website in PHP format, these scripts are transferred to the
webspace including real estate data in the `data` folder. This solution started
as an experiment, how to publish real estates from
[OpenEstate-ImmoTool](http://openestate.org/) on a website with as minimal
effort as possible for the user.


Warning
-------

This version of OpenEstate-PHP-Export is outdated and not developed any further.
You should update your installation of OpenEstate-ImmoTool to the latest version
and use [OpenEstate-PHP-Export 1.7.x](https://github.com/OpenEstate/OpenEstate-PHP-Export/tree/master-1.7)
or [OpenEstate-PHP-Export 2.x](https://github.com/OpenEstate/OpenEstate-PHP-Export/tree/master).


Features
--------

-   listing view of multiple real estates (see `index.php`)
    -   filter listings by different criterias (see `include/class.filter_*.php`)
    -   order listings by different criterias (see `include/class.order_*.php`)
-   detailled view of a single real estate (see `expose.php`)
    -   image gallery via [Colorbox](http://www.jacklmoore.com/colorbox/) or [Lightbox2](http://www.lokeshdhakar.com/projects/lightbox2/) (see `include/class.gallery_*.php`)
    -   embedded maps via [OpenStreetMap](http://www.openstreetmap.org/) or [Google Maps](https://www.google.com/) (see `include/class.map_*.php`)
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
    -   [OpenEstate-ImmoTool](http://openestate.org/) 0.9.x
-   webspace side
    -   PHP 5 (PHP 4 might work, but is not supported anymore)
    -   [PHP GD extension](http://www.php.net/manual/en/book.image.php)
    -   [PHP mbstring extension](http://www.php.net/manual/en/book.mbstring.php) (optional)
    -   [PHP iconv extension](http://de1.php.net/manual/en/book.iconv.php) (optional)


Limitations
-----------

This solution works entirely without a database. Therefore more complex queries
on the exported real estates may not be possible with acceptable performance.
We're planning a complete revision of the PHP-export for the future, that also
provides capabilities to store exported objects into a database like MySQL.


License
-------

[GNU General Public License 3](http://www.gnu.org/licenses/gpl-3.0-standalone.html)


Further information
-------------------

-   [*OpenEstate-PHP-Export* at GitHub](https://github.com/OpenEstate/OpenEstate-PHP-Export)
-   [Releases of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/releases)
-   [Changelog of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/blob/master-1.5/CHANGELOG.md)
-   [API documentation of *OpenEstate-PHP-Export*](https://media.openestate.org/apidocs/OpenEstate-PHP-Export/)
