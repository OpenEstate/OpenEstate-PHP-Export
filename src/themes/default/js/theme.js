/*
 * Copyright 2009-2018 OpenEstate.org.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
    jQuery('#' + bodyId + ' .openestate-action-filter').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-language-form').hide();
        jQuery('#' + bodyId + ' .openestate-sort-form').hide();

        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');
        jQuery('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-filter').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-filter-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-filter').blur();
    });

    // Submit filter form via AJAX.
    jQuery('#' + bodyId + ' .openestate-filter-form button').click(function () {
        jQuery(this).addClass('clicked');
    });
    jQuery('#' + bodyId + ' .openestate-filter-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        jQuery.ajax({
            url: actionUrl,
            data: openestate_form_data(jQuery(this)),
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
    jQuery('#' + bodyId + ' .openestate-action-sort').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-filter-form').hide();
        jQuery('#' + bodyId + ' .openestate-language-form').hide();

        jQuery('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-sort').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-sort-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-sort').blur();
    });

    // Submit sort form via AJAX.
    jQuery('#' + bodyId + ' .openestate-sort-form button').click(function () {
        jQuery(this).addClass('clicked');
    });
    jQuery('#' + bodyId + ' .openestate-sort-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        jQuery.ajax({
            url: actionUrl,
            data: openestate_form_data(jQuery(this)),
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
    jQuery('#' + bodyId + ' .openestate-action-language').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-filter-form').hide();
        jQuery('#' + bodyId + ' .openestate-sort-form').hide();

        jQuery('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        jQuery('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-language-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-language').blur();
    });

    // Enable popups on mouse over in thumbnail view.
    jQuery('#' + bodyId + ' .openestate-listing-thumb').each(function (index) {
        var image = jQuery(this).find('.openestate-listing-thumb-image');
        var popup = jQuery(this).find('.openestate-listing-thumb-popup');
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

        jQuery(this).hover(
            function () {
                jQuery('#' + bodyId + ' .openestate-listing-thumb-popup').hide();
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
    jQuery('#' + bodyId + ' div[data-openestate-object]').each(function (index) {
        var objectContainer = jQuery(this);
        var objectId = objectContainer.data('openestate-object');
        if (objectId === undefined)
            return;

        objectContainer.find('a[data-openestate-fav]').click(function (event) {
            event.preventDefault();

            var link = jQuery(this);
            if (link.is(':disabled'))
                return;

            var actionData = link.data('openestate-fav');
            if (actionData === undefined)
                return;

            jQuery.ajax({
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
    jQuery('#' + bodyId + ' .openestate-action-sort').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-language-form').hide();
        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-sort').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-sort-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-sort').blur();
    });

    // Submit sort form via AJAX.
    jQuery('#' + bodyId + ' .openestate-sort-form button').click(function () {
        jQuery(this).addClass('clicked');
    });
    jQuery('#' + bodyId + ' .openestate-sort-form').submit(function (event) {
        event.preventDefault();
        openestate_progress_show(uid);

        jQuery.ajax({
            url: actionUrl,
            data: openestate_form_data(jQuery(this)),
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
    jQuery('#' + bodyId + ' .openestate-action-language').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-sort-form').hide();
        jQuery('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-language-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-language').blur();
    });

    // Enable popups on mouse over in thumbnail view.
    jQuery('#' + bodyId + ' .openestate-fav-thumb').each(function (index) {
        var image = jQuery(this).find('.openestate-fav-thumb-image');
        var popup = jQuery(this).find('.openestate-fav-thumb-popup');
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

        jQuery(this).hover(
            function () {
                jQuery('#' + bodyId + ' .openestate-fav-thumb-popup').hide();
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
    var gallery = jQuery('#' + bodyId + ' .openestate-expose-gallery');
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
        var link = jQuery(this);
        var slide = link.parent();

        if (slide.hasClass('slick-current')) {
            jQuery.colorbox({
                href: link.attr('href'),
                maxWidth: '98%',
                top: '0',
                title: link.attr('title')
            });
        } else {
            gallery.slick('slickGoTo', slide.data('slick-index'));
        }
    });

    // Enable language action button.
    jQuery('#' + bodyId + ' .openestate-action-language').click(function (event) {
        event.preventDefault();

        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
        jQuery('#' + bodyId + ' .openestate-language-form').slideToggle();
        jQuery('#' + bodyId + ' .openestate-action-language').blur();
    });

    // Enable AJAX favorite links.
    jQuery('#' + bodyId + ' a[data-openestate-fav]').click(function (event) {
        event.preventDefault();

        var link = jQuery(this);
        if (link.is(':disabled'))
            return;

        var actionData = link.data('openestate-fav');
        if (actionData === undefined)
            return;

        jQuery.ajax({
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
                    jQuery('#' + bodyId + ' .openestate-action-fav-remove').show();
                }
                else if (link.hasClass('openestate-action-fav-remove')) {
                    link.hide();
                    jQuery('#' + bodyId + ' .openestate-action-fav-add').show();
                }
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                alert('An error occurred!');
            });
    });

    // Refresh contact captcha image.
    jQuery('#' + bodyId + ' .openestate-expose-contact-captcha-image a').click(function (event) {
        event.preventDefault();
        var img = jQuery('#' + bodyId + ' .openestate-expose-contact-captcha-image img');
        var url = img.attr('src');
        var pos = url.indexOf('?');
        if (pos > -1) url = url.substr(0, pos);
        img.attr('src', url + '?' + Date.now());
    });

    // Submit contact form.
    jQuery('#' + bodyId + ' .openestate-expose-contact-form').submit(function (event) {
        event.preventDefault();
        //console.log(openestate_form_data(jQuery(this)));

        jQuery('#' + bodyId + ' .openestate-expose-contact-form').hide();
        jQuery('#' + bodyId + ' .openestate-expose-contact-error').hide();
        jQuery('#' + bodyId + ' .openestate-expose-contact-success').hide();
        jQuery('#' + bodyId + ' .openestate-expose-contact-loading').fadeIn();

        jQuery.ajax({
            url: actionUrl,
            data: openestate_form_data(jQuery(this)),
            dataType: 'json',
            cache: false
        })
            .done(function (data) {
                //console.log('success');
                //console.log(data);

                jQuery('#' + bodyId + ' .openestate-expose-contact-loading').hide();
                jQuery('#' + bodyId + ' .openestate-expose-contact-validation').hide();
                jQuery('#' + bodyId + ' .openestate-expose-contact-field').removeClass('openestate-expose-contact-field-invalid');

                // an error occurred
                if (data !== undefined && 'error' in data) {

                    if ('validation' in data) {
                        //console.log('update validation');

                        // Show validation result.
                        for (var i in data.validation) {
                            //console.log('> ' + i + ' = ' + data.validation[i]);

                            // Highlight invalid field.
                            var field = jQuery('#' + bodyId + ' .openestate-expose-contact-' + i);
                            field.find('.openestate-expose-contact-field').addClass('openestate-expose-contact-field-invalid');

                            // Show validation message.
                            var validation = field.find('.openestate-expose-contact-validation');
                            validation.find('.openestate-expose-contact-validation-message').text(data.validation[i]);
                            validation.show();
                        }

                        // Generate a new captcha, if captcha validation failed.
                        if ('captcha' in data.validation) {
                            var img = jQuery('#' + bodyId + ' .openestate-expose-contact-captcha-image img');
                            var url = img.attr('src');
                            var pos = url.indexOf('?');
                            if (pos > -1) url = url.substr(0, pos);
                            img.attr('src', url + '?' + Date.now());
                        }
                    }

                    jQuery('#' + bodyId + ' .openestate-expose-contact-error-message').text(data.error);
                    jQuery('#' + bodyId + ' .openestate-expose-contact-error').fadeIn();
                    jQuery('#' + bodyId + ' .openestate-expose-contact-form').show();
                }

                // mail was sent successfully
                else {
                    jQuery('#' + bodyId + ' .openestate-expose-contact-success').fadeIn();
                }

            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                jQuery('#' + bodyId + ' .openestate-expose-contact-loading').hide();

                if (data !== undefined && 'error' in data)
                    jQuery('#' + bodyId + ' .openestate-expose-contact-error-message').text(data.error);
                else
                    jQuery('#' + bodyId + ' .openestate-expose-contact-error-message').text('');

                jQuery('#' + bodyId + ' .openestate-expose-contact-error').fadeIn();
                jQuery('#' + bodyId + ' .openestate-expose-contact-form').show();
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
    jQuery('#openestate-loading-' + uid).hide();
    jQuery('#openestate-body-' + uid).fadeIn();
}

/**
 * Show progress indicator.
 *
 * @param uid
 * unique identifier of the view
 */
function openestate_progress_show(uid) {
    jQuery('#openestate-body-' + uid).hide();
    jQuery('#openestate-loading-' + uid).fadeIn();
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
        var input = jQuery(this);

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
    jQuery('#' + bodyId + ' a[data-openestate-action]').click(function (event) {
        event.preventDefault();

        var link = jQuery(this);
        if (link.is(':disabled'))
            return;

        var actionData = link.data('openestate-action');
        if (actionData === undefined)
            return;

        openestate_progress_show(uid);
        jQuery.ajax({
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
