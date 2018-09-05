/*
 * PHP-Export scripts of OpenEstate-ImmoTool
 * Copyright (C) 2009-2018 OpenEstate.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Install listing view.
 *
 * @param uid
 * unique identifier of the view
 *
 * @param actionUrl
 * URL for calling AJAX actions
 */
function openestate_install_listing(uid, actionUrl) {
    var bodyId = 'openestate-body-' + uid;

    // Enable filter action button.
    $('#' + bodyId + ' .openestate-action-filter').click(function (event) {
        event.preventDefault();

        $('#' + bodyId + ' .openestate-language-form').hide();
        $('#' + bodyId + ' .openestate-sort-form').hide();

        $('#' + bodyId + ' .openestate-action-language').removeClass('active');
        $('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        $('#' + bodyId + ' .openestate-action-filter').toggleClass('active');
        $('#' + bodyId + ' .openestate-filter-form').slideToggle();
        $('#' + bodyId + ' .openestate-action-filter').blur();
    });

    // Submit filter form via AJAX.
    $('#' + bodyId + ' .openestate-filter-form button').click(function () {
        $(this).addClass('clicked');
    });
    $('#' + bodyId + ' .openestate-filter-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        $.ajax({
            url: actionUrl,
            data: openestate_form_data($(this)),
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);
                location.reload(true);
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });

    });

    // Enable sort action button.
    $('#' + bodyId + ' .openestate-action-sort').click(function (event) {
        event.preventDefault();

        $('#' + bodyId + ' .openestate-filter-form').hide();
        $('#' + bodyId + ' .openestate-language-form').hide();

        $('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        $('#' + bodyId + ' .openestate-action-language').removeClass('active');

        $('#' + bodyId + ' .openestate-action-sort').toggleClass('active');
        $('#' + bodyId + ' .openestate-sort-form').slideToggle();
        $('#' + bodyId + ' .openestate-action-sort').blur();
    });

    // Submit sort form via AJAX.
    $('#' + bodyId + ' .openestate-sort-form button').click(function () {
        $(this).addClass('clicked');
    });
    $('#' + bodyId + ' .openestate-sort-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        $.ajax({
            url: actionUrl,
            data: openestate_form_data($(this)),
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);
                location.reload(true);
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });

    // Enable language action button.
    $('#' + bodyId + ' .openestate-action-language').click(function (event) {
        event.preventDefault();

        $('#' + bodyId + ' .openestate-filter-form').hide();
        $('#' + bodyId + ' .openestate-sort-form').hide();

        $('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        $('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        $('#' + bodyId + ' .openestate-action-language').toggleClass('active');
        $('#' + bodyId + ' .openestate-language-form').slideToggle();
        $('#' + bodyId + ' .openestate-action-language').blur();
    });

    // Enable popups on mouse over in thumbnail view.
    $('#' + bodyId + ' .openestate-listing-thumb').each(function (index) {
        var image = $(this).find('.openestate-listing-thumb-image');
        var popup = $(this).find('.openestate-listing-thumb-popup');
        var popper = new Popper(image, popup, {
            placement: 'bottom-start',
            modifiers: {
                offset: {
                    enabled: true,
                    offset: '25%, -75%'
                },
                flip: {
                    enabled: false
                }
            }
        });

        $(this).hover(
            function () {
                $('#' + bodyId + ' .openestate-listing-thumb-popup').hide();
                if (!popup.is(':visible')) {
                    popper.scheduleUpdate();
                    popup.fadeIn();
                }
            }, function () {
                popup.hide();
            }
        );
    });

    // Enable AJAX favorite links.
    $('#' + bodyId + ' div[data-openestate-object]').each(function (index) {
        var objectContainer = $(this);
        var objectId = objectContainer.data('openestate-object');
        if (objectId === undefined)
            return;

        objectContainer.find('a[data-openestate-fav]').click(function (event) {
            event.preventDefault();

            var link = $(this);
            if (link.is(':disabled'))
                return;

            var actionData = link.data('openestate-fav');
            if (actionData === undefined)
                return;

            $.ajax({
                url: actionUrl,
                data: actionData,
                dataType: 'json',
                cache: false
            })
                .done(function (data) {
                    //console.log('success');
                    //console.log(data);
                    if (link.hasClass('openestate-action-fav-add')) {
                        link.hide();
                        objectContainer.find('.openestate-action-fav-remove').show();
                    }
                    else if (link.hasClass('openestate-action-fav-remove')) {
                        link.hide();
                        objectContainer.find('.openestate-action-fav-add').show();
                    }
                })
                .fail(function (data) {
                    //console.log('error');
                    //console.log(data);
                    alert('An error occurred!');
                });
        });
    });

    // Enable general AJAX action links.
    openestate_ajax_links(uid, actionUrl);
}

/**
 * Install favorite view.
 *
 * @param uid
 * unique identifier of the view
 *
 * @param actionUrl
 * URL for calling AJAX actions
 */
function openestate_install_favorite(uid, actionUrl) {
    var bodyId = 'openestate-body-' + uid;

    // Enable sort action button.
    $('#' + bodyId + ' .openestate-action-sort').click(function (event) {
        event.preventDefault();

        $('#' + bodyId + ' .openestate-filter-form').hide();
        $('#' + bodyId + ' .openestate-language-form').hide();

        $('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        $('#' + bodyId + ' .openestate-action-language').removeClass('active');

        $('#' + bodyId + ' .openestate-action-sort').toggleClass('active');
        $('#' + bodyId + ' .openestate-sort-form').slideToggle();
        $('#' + bodyId + ' .openestate-action-sort').blur();
    });

    // Submit sort form via AJAX.
    $('#' + bodyId + ' .openestate-sort-form button').click(function () {
        $(this).addClass('clicked');
    });
    $('#' + bodyId + ' .openestate-sort-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        $.ajax({
            url: actionUrl,
            data: openestate_form_data($(this)),
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);
                location.reload(true);
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });

    // Enable language action button.
    $('#' + bodyId + ' .openestate-action-language').click(function (event) {
        event.preventDefault();

        $('#' + bodyId + ' .openestate-filter-form').hide();
        $('#' + bodyId + ' .openestate-sort-form').hide();

        $('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        $('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        $('#' + bodyId + ' .openestate-action-language').toggleClass('active');
        $('#' + bodyId + ' .openestate-language-form').slideToggle();
        $('#' + bodyId + ' .openestate-action-language').blur();
    });

    // Enable popups on mouse over in thumbnail view.
    $('#' + bodyId + ' .openestate-fav-thumb').each(function (index) {
        var image = $(this).find('.openestate-fav-thumb-image');
        var popup = $(this).find('.openestate-fav-thumb-popup');
        var popper = new Popper(image, popup, {
            placement: 'bottom-start',
            modifiers: {
                offset: {
                    enabled: true,
                    offset: '25%, -75%'
                },
                flip: {
                    enabled: false
                }
            }
        });

        $(this).hover(
            function () {
                $('#' + bodyId + ' .openestate-fav-thumb-popup').hide();
                if (!popup.is(':visible')) {
                    popper.scheduleUpdate();
                    popup.fadeIn();
                }
            }, function () {
                popup.hide();
            }
        );
    });

    // Enable general AJAX action links.
    openestate_ajax_links(uid, actionUrl);
}

/**
 * Install expose view.
 *
 * @param uid
 * unique identifier of the view
 *
 * @param actionUrl
 * URL for calling AJAX actions
 */
function openestate_install_expose(uid, actionUrl) {
    var bodyId = 'openestate-body-' + uid;

    // Install slick gallery.
    var gallery = $('#' + bodyId + ' .openestate-expose-gallery');
    gallery.slick({
        accessibility: true,
        swipe: false,
        arrows: true,
        prevArrow: '<button type="button" class="openestate-expose-gallery-prev"><i class="openestate-icon-left"></i></button>',
        nextArrow: '<button type="button" class="openestate-expose-gallery-next"><i class="openestate-icon-right"></i></button>',
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: true,
        variableWidth: true,
        zIndex: 10,
        autoplay: true,
        autoplaySpeed: 3000
    });

    // Handle clicks on slick gallery images.
    gallery.find('a').click(function (event) {
        event.preventDefault();
        gallery.slick('slickPause');
        var link = $(this);
        var slide = link.parent();

        if (slide.hasClass('slick-current')) {
            $.colorbox({
                href: link.attr('href'),
                maxWidth: '98%',
                top: '0',
                title: link.attr('title')
            });
        } else {
            gallery.slick('slickGoTo', slide.data('slick-index'));
        }
    });

    // Refresh contact captcha image.
    $('#' + bodyId + ' .openestate-expose-contact-captcha-image a').click(function (event) {
        event.preventDefault();
        var img = $('#' + bodyId + ' .openestate-expose-contact-captcha-image img');
        var url = img.attr('src');
        var pos = url.indexOf('?');
        if (pos > -1) url = url.substr(0, pos);
        img.attr('src', url + '?' + Date.now());
    });

    // Submit contact form.
    $('#' + bodyId + ' .openestate-expose-contact-form').submit(function (event) {
        event.preventDefault();
        //console.log(openestate_form_data($(this)));

        $('#' + bodyId + ' .openestate-expose-contact-form').hide();
        $('#' + bodyId + ' .openestate-expose-contact-error').hide();
        $('#' + bodyId + ' .openestate-expose-contact-success').hide();
        $('#' + bodyId + ' .openestate-expose-contact-loading').fadeIn();

        $.ajax({
            url: actionUrl,
            data: openestate_form_data($(this)),
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);

                $('#' + bodyId + ' .openestate-expose-contact-loading').hide();

                $('#' + bodyId + ' .openestate-expose-contact-validation').each(function( index ) {
                    $( this ).hide();
                });

                $('#' + bodyId + ' .openestate-expose-contact-field').each(function( index ) {
                    $( this ).removeClass('openestate-expose-contact-field-invalid');
                });

                // an error occurred
                if (data !== undefined && 'error' in data) {

                    if ('validation' in data) {
                        //console.log('update validation');

                        // Show validation result.
                        for (var i in data.validation) {
                            //console.log('> ' + i + ' = ' + data.validation[i]);

                            // Highlight invalid field.
                            var field = $('#' + bodyId + ' .openestate-expose-contact-' + i);
                            field.find('.openestate-expose-contact-field').addClass('openestate-expose-contact-field-invalid');

                            // Show validation message.
                            var validation = field.find('.openestate-expose-contact-validation');
                            validation.find('.openestate-expose-contact-validation-message').text(data.validation[i]);
                            validation.show();
                        }

                        // Generate a new captcha, if captcha validation failed.
                        if ('captcha' in data.validation) {
                            var img = $('#' + bodyId + ' .openestate-expose-contact-captcha-image img');
                            var url = img.attr('src');
                            var pos = url.indexOf('?');
                            if (pos > -1) url = url.substr(0, pos);
                            img.attr('src', url + '?' + Date.now());
                        }
                    }

                    $('#' + bodyId + ' .openestate-expose-contact-error-message').text(data.error);
                    $('#' + bodyId + ' .openestate-expose-contact-error').fadeIn();
                    $('#' + bodyId + ' .openestate-expose-contact-form').show();
                }

                // mail was sent successfully
                else {
                    $('#' + bodyId + ' .openestate-expose-contact-success').fadeIn();
                }

            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                $('#' + bodyId + ' .openestate-expose-contact-loading').hide();

                if (data !== undefined && 'error' in data)
                    $('#' + bodyId + ' .openestate-expose-contact-error-message').text(data.error);
                else
                    $('#' + bodyId + ' .openestate-expose-contact-error-message').text('');

                $('#' + bodyId + ' .openestate-expose-contact-error').fadeIn();
                $('#' + bodyId + ' .openestate-expose-contact-form').show();
            });

    });

    // Enable general AJAX action links.
    openestate_ajax_links(uid, actionUrl);
}

/**
 * Hide progress indicator.
 *
 * @param uid
 * unique identifier of the view
 */
function openestate_progress_hide(uid) {
    $('#openestate-loading-' + uid).hide();
    $('#openestate-body-' + uid).fadeIn();
}

/**
 * Show progress indicator.
 *
 * @param uid
 * unique identifier of the view
 */
function openestate_progress_show(uid) {
    $('#openestate-body-' + uid).hide();
    $('#openestate-loading-' + uid).fadeIn();
}

/**
 * Collect input fields from a form.
 *
 * @param form
 * form container
 *
 * @return
 * javascript object with form data
 */
function openestate_form_data(form) {
    var data = {};
    form.find('input, select, textarea, button.clicked').each(function (index) {
        var input = $(this);

        var name = input.attr('name');
        if (name === undefined)
            return;

        var type = input.attr('type');
        if (type !== undefined)
            type = type.toLowerCase();

        if (type === 'checkbox') {
            if (input.is(':checked'))
                data[name] = input.val();
            else
                data[name] = '';
        }
        else if (type === 'radio') {
            if (input.is(':checked'))
                data[name] = input.val();
        }
        else {
            data[name] = input.val();
        }
    });
    return data;
}

/**
 * Install AJAX event handling on links with "data-openestate-action" attribute.
 *
 * @param uid
 * unique identifier of the view
 *
 * @param actionUrl
 * URL for calling AJAX actions
 */
function openestate_ajax_links(uid, actionUrl) {
    var bodyId = 'openestate-body-' + uid;
    $('#' + bodyId + ' a[data-openestate-action]').click(function (event) {
        event.preventDefault();

        var link = $(this);
        if (link.is(':disabled'))
            return;

        var actionData = link.data('openestate-action');
        if (actionData === undefined)
            return;

        openestate_progress_show(uid);
        $.ajax({
            url: actionUrl,
            data: actionData,
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);
                location.reload(true);
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });
}
