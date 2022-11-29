(function($) {
    'use strict';
    $(window).on('load', function() {
        //fix_ver_upgrade
        $(document).ready(function() {
            // $('.gta-container .select2').select2();
        });
        $(function() {
            $('.gt-color-field').wpColorPicker();
        });
        $('.fix_ver_upgrade').click(function() {
            $.post(ajaxurl, {
                action: 'fix_ver_upgrade_ajax',
            }).done(function(data) {
                window.location.reload();
            }).fail(function(data) {});
        })

        $('#Upgrade-Genealogical-Tree-Database').click(function() {
            $(this).find('span').addClass('spinner is-active')
            $.post(ajaxurl, {
                action: 'fix_ver_upgrade_ajax',
                _gt_version_fixed_through_notice: true

            }).done(function(data) {
                window.location.reload();
            }).fail(function(data) {});

            return false;
        })
        

        $('#birth-sex').change(function() {
            var gt_sex = $('#birth-sex').val();
            if (!gt_sex) {
                $('tr.tr-husb').show();
                $('tr.tr-wife').show();
            }
            if (gt_sex === 'F') {
                $('tr.tr-wife').hide();
                $('tr.tr-husb').show();
            }
            if (gt_sex === 'M') {
                $('tr.tr-husb').hide();
                $('tr.tr-wife').show();
            }
        })
        $('.generate_default_tree').click(function() {
            var family_id = $(this).data('id');
            $.post(ajaxurl, {
                action: 'generate_default_tree',
                'family_id': family_id,
                nonce: ajax_var.nonce,   // pass the nonce here
            }).done(function(data) {
                window.location.reload();
            }).fail(function(data) {});
            return false;
        })
        


    })

    function isInArray(value, array) {
        for (var i = 0; i < array.length; i++) {
            if (array[i] == value) {
                return true
            }
        }
        return false;
    }
    /*
     * A custom function that checks if element is in array, we'll need it later
     */
    function in_array(el, arr) {
        for (var i in arr) {
            if (arr[i] == el) return true;
        }
        return false;
    }
    jQuery(function($) {
        /*
         * Sortable images
         */
        $('ul.gt-member-gallery-images').sortable({
            items: 'li',
            cursor: '-webkit-grabbing',
            /* mouse cursor */
            scrollSensitivity: 40,
            /*
            You can set your custom CSS styles while this element is dragging
            start:function(event,ui){
            	ui.item.css({'background-color':'grey'});
            },
            */
            stop: function(event, ui) {
                ui.item.removeAttr('style');
                var sort = new Array(),
                    /* array of image IDs */
                    gallery = $(this); /* ul.gt-member-gallery-images */
                /* each time after dragging we resort our array */
                gallery.find('li').each(function(index) {
                    sort.push($(this).attr('data-id'));
                });
                /* add the array value to the hidden input field */
                gallery.parent().next().val(sort.join());
                /* console.log(sort); */
            }
        });
        /*
         * Multiple images uploader
         */
        $('.misha_upload_gallery_button').click(function(e) {
            /* on button click*/
            e.preventDefault();
            var button = $(this),
                hiddenfield = button.prev(),
                hiddenfieldvalue = hiddenfield.val().split(","),
                /* the array of added image IDs */
                custom_uploader = wp.media({
                    title: 'Insert images',
                    /* popup title */
                    library: {
                        type: 'image'
                    },
                    button: {
                        text: 'Use these images'
                    },
                    /* "Insert" button text */
                    multiple: true
                }).on('select', function() {
                    var attachments = custom_uploader.state().get('selection').map(function(a) {
                            a.toJSON();
                            return a;
                        }),
                        thesamepicture = false,
                        i;
                    /* loop through all the images */
                    for (i = 0; i < attachments.length; ++i) {
                        /* if you don't want the same images to be added multiple time */
                        if (!in_array(attachments[i].id, hiddenfieldvalue)) {
                            /* add HTML element with an image */
                            $('ul.gt-member-gallery-images').append('<li data-id="' + attachments[i].id + '">\
							<span style="background-image:url(' + attachments[i].attributes.url + ')">v\
							<img src="' + attachments[i].attributes.url + '">\
							</span><a href="#" class="misha_gallery_remove">×</a>\
							</li>');
                            /* add an image ID to the array of all images */
                            hiddenfieldvalue.push(attachments[i].id);
                        } else {
                            thesamepicture = true;
                        }
                    }
                    /* refresh sortable */
                    $("ul.gt-member-gallery-images").sortable("refresh");
                    /* add the IDs to the hidden field value */
                    hiddenfield.val(hiddenfieldvalue.join());
                    /* you can print a message for users if you want to let you know about the same images */
                    if (thesamepicture == true) alert('The same images are not allowed.');
                }).open();
        });
        /*
         * Remove certain images
         */
        $('body').on('click', '.misha_gallery_remove', function() {
            var id = $(this).parent().attr('data-id'),
                gallery = $(this).parent().parent(),
                hiddenfield = gallery.parent().next(),
                hiddenfieldvalue = hiddenfield.val().split(","),
                i = hiddenfieldvalue.indexOf(id);
            $(this).parent().remove();
            /* remove certain array element */
            if (i != -1) {
                hiddenfieldvalue.splice(i, 1);
            }
            /* add the IDs to the hidden field value */
            hiddenfield.val(hiddenfieldvalue.join());
            /* refresh sortable */
            gallery.sortable("refresh");
            return false;
        });
        /*
         * Selected item
         */
        $('body').on('mousedown', 'ul.gt-member-gallery-images li', function() {
            var el = $(this);
            el.parent().find('li').removeClass('misha-active');
            el.addClass('misha-active');
        });
    });
})(jQuery);
