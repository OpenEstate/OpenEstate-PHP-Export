OpenEstate-PHP-Export 1.7.x
===========================

OpenEstate-PHP-Export was developed as a part of the freeware real estate
software [OpenEstate-ImmoTool](https://openestate.org/). When a user exports his
properties to his website in PHP format, these scripts are transferred to the
webspace including real estate data in the `data` folder. This solution started
as an experiment, how to publish real estates from
[OpenEstate-ImmoTool](https://openestate.org/) on a website with as minimal
effort as possible for the user.


Features
--------

-   listing view of multiple real estates (see `index.php`)
    -   filter listings by different criteria (see `include/class.filter_*.php`)
    -   order listings by different criteria (see `include/class.order_*.php`)
-   detailed view of a single real estate (see `expose.php`)
    -   image gallery via [Colorbox](https://www.jacklmoore.com/colorbox/) or [Lightbox2](https://www.lokeshdhakar.com/projects/lightbox2/) (see `include/class.gallery_*.php`)
    -   embedded maps via [OpenStreetMap](https://www.openstreetmap.org/) or [Google Maps](https://www.google.com/) (see `include/class.map_*.php`)
    -   embedded videos via [YouTube.com](https://www.youtube.com/) and some other providers (see `include/class.video_*.php`)
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
    -   [OpenEstate-ImmoTool](https://openestate.org/) 1.0-beta
-   webspace side
    -   PHP 5.6 or newer
    -   [PHP GD extension](https://secure.php.net/manual/en/book.image.php)
    -   [PHP mbstring extension](https://secure.php.net/manual/en/book.mbstring.php) (optional)
    -   [PHP iconv extension](https://secure.php.net/manual/en/book.iconv.php) (optional)


Limitations
-----------

This solution works entirely without a database. Therefore more complex queries
on the exported real estates may not be possible with acceptable performance.
We're planning a complete revision of the PHP-export for the future, that also
provides capabilities to store exported objects into a database like MySQL.


License
-------

This library is licensed under the terms of
[Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0.html).
Take a look at the provided [`LICENSE.txt`](LICENSE.txt) for the license text.


Further information
-------------------

-   [*OpenEstate-PHP-Export* at GitHub](https://github.com/OpenEstate/OpenEstate-PHP-Export)
-   [Releases of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/releases)
-   [Changelog of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/blob/develop-1.7/CHANGELOG.md)
-   [API documentation of *OpenEstate-PHP-Export*](https://media.openestate.org/apidocs/OpenEstate-PHP-Export/)
