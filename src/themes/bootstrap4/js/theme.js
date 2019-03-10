/*
 * Copyright 2009-2019 OpenEstate.org.
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
                openestate_update_location();
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
                openestate_update_location();
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });

    // Catch events on the language action button.
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('shown.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-filter-form').slideUp();
        jQuery('#' + bodyId + ' .openestate-sort-form').slideUp();

        jQuery('#' + bodyId + ' .openestate-action-filter').removeClass('active');
        jQuery('#' + bodyId + ' .openestate-action-sort').removeClass('active');

        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
    });
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('hidden.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');
    });

    // Enable popups on mouse over in thumbnail view.
    jQuery('#' + bodyId + ' .openestate-listing-thumb').each(function (index) {
        //var image = jQuery(this).find('.openestate-listing-thumb-image');
        var popup = jQuery(this).find('.openestate-listing-thumb-popup');
        var objectId = jQuery(this).data('openestate-object');

        var containerId = 'openestate-listing-thumb-' + objectId + '-' + uid;
        jQuery(this).attr('id', containerId);

        jQuery(this).popover({
            trigger: 'hover',
            placement: 'bottom',
            container: '#' + containerId,
            html: true,
            content: popup
        });
        jQuery(this).on('show.bs.popover', function () {
            popup.show();
        });
    });

    // Enable AJAX favorite links.
    jQuery('#' + bodyId + ' div[data-openestate-object]').each(function (index) {
        var objectContainer = jQuery(this);
        var objectId = objectContainer.data('openestate-object');
        if (objectId === undefined)
            return;

        objectContainer.find('a[data-openestate-fav]').click(function (event) {
            event.preventDefault();

            //console.log('FAV');

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
                openestate_update_location();
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });

    // Catch events on the language action button.
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('shown.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-sort-form').slideUp();
        jQuery('#' + bodyId + ' .openestate-action-sort').removeClass('active');
        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
    });
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('hidden.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');
    });

    // Enable popups on mouse over in thumbnail view.
    jQuery('#' + bodyId + ' .openestate-fav-thumb').each(function (index) {
        //var image = jQuery(this).find('.openestate-fav-thumb-image');
        var popup = jQuery(this).find('.openestate-fav-thumb-popup');
        var objectId = jQuery(this).data('openestate-object');

        var containerId = 'openestate-fav-thumb-' + objectId + '-' + uid;
        jQuery(this).attr('id', containerId);

        jQuery(this).popover({
            trigger: 'hover',
            placement: 'bottom',
            container: '#' + containerId,
            html: true,
            content: popup
        });
        jQuery(this).on('show.bs.popover', function () {
            popup.show();
        });
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

    // Detect available images.
    var minImageId = null;
    var maxImageId = null;
    jQuery('#' + bodyId + ' .openestate-expose-gallery > div').each(function (index) {
        if (minImageId === null || minImageId > index)
            minImageId = index;
        if (maxImageId === null || maxImageId < index)
            maxImageId = index;
    });

    // Select image for full view.
    var currentImageId = null;
    var setCurrentImage = function (id) {
        if (id === null)
            return;

        currentImageId = id;
        var link = jQuery('#' + bodyId + ' .slick-slide[data-slick-index="' + id + '"] > a');
        jQuery('#' + bodyId + ' .openestate-gallery-dialog-title').text(link.attr('title'));
        jQuery('#' + bodyId + ' .openestate-gallery-dialog-image').attr('src', link.attr('href'));
    };

    if (minImageId === null || maxImageId === null || minImageId === maxImageId) {
        jQuery('#openestate-gallery-dialog-previous').hide();
        jQuery('#openestate-gallery-dialog-next').hide();
    } else {
        // Switch to previous image in full view.
        jQuery('#openestate-gallery-dialog-previous').click(function (event) {
            event.preventDefault();
            if (currentImageId === null)
                return;
            if (currentImageId > minImageId)
                setCurrentImage(currentImageId - 1);
            else
                setCurrentImage(maxImageId);
        });

        // Switch to next image in full view.
        jQuery('#openestate-gallery-dialog-next').click(function (event) {
            event.preventDefault();
            if (currentImageId === null)
                return;
            if (currentImageId < maxImageId)
                setCurrentImage(currentImageId + 1);
            else
                setCurrentImage(minImageId);
        });
    }

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
        var index = slide.data('slick-index');

        if (slide.hasClass('slick-current')) {
            setCurrentImage(index);
            jQuery('#' + bodyId + ' .openestate-gallery-dialog').modal('show');
        } else {
            gallery.slick('slickGoTo', slide.data('slick-index'));
        }
    });

    // Catch events on the language action button.
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('shown.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-action-language').toggleClass('active');
    });
    jQuery('#' + bodyId + ' .openestate-action-language').parent().on('hidden.bs.dropdown', function () {
        jQuery('#' + bodyId + ' .openestate-action-language').removeClass('active');
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
                jQuery('#' + bodyId + ' .openestate-expose-contact .openestate-expose-contact-field').removeClass('is-invalid');

                // an error occurred
                if (data !== undefined && 'error' in data) {

                    if ('validation' in data) {
                        //console.log('update validation');

                        // Show validation result.
                        for (var i in data.validation) {
                            //console.log('> ' + i + ' = ' + data.validation[i]);

                            // Highlight invalid field.
                            var field = jQuery('#' + bodyId + ' .openestate-expose-contact-' + i);
                            field.find('.openestate-expose-contact-field').addClass('is-invalid');


                            //field.find('.openestate-expose-contact-field').addClass('openestate-expose-contact-field-invalid');

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
                openestate_update_location();
            })
            .fail(function (data) {
                //console.log('error');
                //console.log(data);
                openestate_progress_hide(uid);
                alert('An error occurred!');
            });
    });
}

/**
 * Reload the current page and add an empty update parameter to the URL.
 */
function openestate_update_location() {
    var query = location.search;
    if (query === null) {
        location.reload(true);
        return;
    }

    if (query.length < 1) {
        location.search += '?update';
        return;
    }

    var vars = query.substring(1).split('&');
    if (vars.includes('update')) {
        location.reload(true);
        return;
    }

    if (location.search.startsWith('?'))
        location.search += '&update';
    else
        location.search += '?update';
}
