OpenEstate-PHP-Export 2.0-beta2
===============================

*OpenEstate-PHP-Export* is developed as a part of the freeware real estate
software [*OpenEstate-ImmoTool*](https://openestate.org/). When a user exports
his properties to his website in PHP format, these scripts are transferred to
the webspace including real estate data in the [`data`](src/data) folder.


Features
--------

-   listing view of multiple real estates
    (see [`index.php`](src/index.php))
-   detailled view of a single real estate
    (see [`expose.php`](src/expose.php))
-   visitors may manage their favored real estates
    (see [`fav.php`](src/fav.php))
-   real estate listings may be filtered by different criteria
    (see [`\OpenEstate\PhpExport\Filter`](src/include/OpenEstate/PhpExport/Filter))
-   real estate listings may be ordered by different criteria
    (see [`\OpenEstate\PhpExport\Order`](src/include/OpenEstate/PhpExport/Order))
-   a basic contact form is available
    (see [`\OpenEstate\PhpExport\Action\Contact`](src/include/OpenEstate/PhpExport/Action/Contact.php))
-   generated output is fully customizable with [themes](src/themes)
-   a lot of configuration options are available
    (see [`\OpenEstate\PhpExport\Config`](src/include/OpenEstate/PhpExport/Config.php)
    and [`config.php`](src/config.php))
-   available in multiple languages (English by default, see
    [current translation progress](https://i18n.openestate.org/projects/openestate-php-export/#languages))
-   open source modules are available for
    [*WordPress*](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-WordPress),
    [*CMS made simple*](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-CMSms),
    [*WBCE*](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-WBCE) &
    [*Joomla*](https://github.com/OpenEstate/OpenEstate-PHP-Wrapper-Joomla)


Requirements
------------

-   client side (real estate agency / website owner)
    -   [*OpenEstate-ImmoTool*](https://openestate.org/) 1.0.0 or later
-   webspace side
    -   PHP 5.6 or newer
    -   [PHP *GD* extension](https://secure.php.net/manual/en/book.image.php)
        (optional, but recommended)
    -   [PHP *mbstring* extension](https://secure.php.net/manual/en/book.mbstring.php)
        (optional)
    -   [PHP *iconv* extension](https://secure.php.net/manual/en/book.iconv.php)
        (optional)


Third party components
----------------------

The following third party components are provided by *OpenEstate-PHP-Export*:

-   [PHPMailer](https://github.com/PHPMailer/PHPMailer) v6.0.6
    (license: [LGPL 2.1](https://github.com/PHPMailer/PHPMailer/blob/master/LICENSE))
-   [Gettext](https://github.com/oscarotero/Gettext) v4.6.1
    (license: [MIT](https://github.com/oscarotero/Gettext/blob/master/LICENSE))
-   [Gettext CLDR data](https://github.com/mlocati/cldr-to-gettext-plural-rules) v2.5.0
    (license: [MIT](https://github.com/mlocati/cldr-to-gettext-plural-rules/blob/master/LICENSE))
-   [Punycode](https://github.com/true/php-punycode) v2.1.1
    (license: [MIT](https://github.com/true/php-punycode/blob/master/LICENSE))
-   [jQuery](https://jquery.com/) v3.3.1
    (license: [MIT](https://jquery.org/license/))
-   [slick](https://kenwheeler.github.io/slick/) v1.8.1
    (license: [MIT](https://github.com/kenwheeler/slick/blob/master/LICENSE))
-   components used by the [*default* theme](src/themes/default)
    -   [Pure.CSS](https://purecss.io/) v1.0.0
        (license: [BSD](https://github.com/pure-css/pure/blob/master/LICENSE))
    -   [Colorbox](https://www.jacklmoore.com/colorbox/) v1.6.4
        (license: [MIT](https://github.com/jackmoore/colorbox/blob/master/LICENSE.md))
    -   [Popper.js](https://popper.js.org/) v1.14.5
        (license: [MIT](https://github.com/FezVrasta/popper.js/blob/master/LICENSE.md))
-   components used by the [*bootstrap3* theme](src/themes/bootstrap3)
    -   [Bootstrap](https://getbootstrap.com/) v3.3.7
        (license: [MIT](https://github.com/twbs/bootstrap/blob/master/LICENSE))
-   components used by the [*bootstrap4* theme](src/themes/bootstrap4)
    -   [Bootstrap](https://getbootstrap.com/) v4.1.3
        (license: [MIT](https://github.com/twbs/bootstrap/blob/master/LICENSE))
        bundled with [Popper.js](https://popper.js.org/)
-   aggregated icons (generated with [*fontello.com*](http://fontello.com/))
    -   [Font Awesome](https://fontawesome.com/)
        (license: [SIL](https://fontawesome.com/license/free))
    -   [Fontelico](https://github.com/fontello/fontelico.font)
        (license: [SIL](https://scripts.sil.org/cms/scripts/page.php?site_id=nrsi&id=OFL))


Limitations
-----------

This solution works entirely without a database. Therefore more complex queries
on the exported real estates may not be possible with acceptable performance.


License
-------

This library is licensed under the terms of
[Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0.html).
Take a look at the provided [`LICENSE.txt`](LICENSE.txt) for the license text.


Further information
-------------------

-   [*OpenEstate-PHP-Export* at GitHub](https://github.com/OpenEstate/OpenEstate-PHP-Export)
-   [Releases of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/releases)
-   [Changelog of *OpenEstate-PHP-Export*](https://github.com/OpenEstate/OpenEstate-PHP-Export/blob/develop/CHANGELOG.md)
-   [API documentation of *OpenEstate-PHP-Export*](https://media.openestate.org/apidocs/OpenEstate-PHP-Export/)
