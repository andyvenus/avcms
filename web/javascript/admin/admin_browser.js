var avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('change', '.ajax-editor form', avcms.browser.editorFormChanged);
    body.on('change', 'form[name="filter_form"] select', avcms.browser.changeFinderFilters);
    body.on('keyup', 'form[name="filter_form"] input', avcms.browser.changeFinderFilters);
    body.on('click', '.clear-search', avcms.browser.clearSearch);

    avcms.event.addEvent('page-modified', avcms.browser.setBrowserFocus);
    avcms.browser.setBrowserFocus();

    avcms.event.addEvent('submit-form-complete', avcms.browser.browserFormSubmitted)

    avcms.event.addEvent('submit-form-success', avcms.browser.editorAddItemFormSubmitEvent);
});

avcms.browser = {
    finder_loading: 0,

    browserFormSubmitted: function(form, response_data) {
        if (response_data.form.has_errors === false) {
            var id = form.attr('data-item-id')
            if (id !== undefined) {
                $(form).find('*').filter(':input:not(button)').each(function(i, form_field){
                    form_field = $(form_field);
                    var field_name = form_field.attr('name');

                    if (form_field.prop('tagName') != 'SELECT') {
                        var field_value = form_field.val();
                    }
                    else {
                        var field_value = form_field.find(":selected").text();
                    }

                    $('[data-id="'+id+'"][data-field='+field_name+'], [data-id="'+id+'"] [data-field='+field_name+']').text(field_value).text();
                });

                if (id != 0) {
                    $('.header-label-container:visible').html('<span class="label label-success animated flipInY">Saved</span>');
                }

                $('.browser-finder-item[data-id="'+id+'"]').addClass('finder-item-saved').removeClass('finder-item-edited');
            }
        }
    },

    setBrowserFocus: function() {
        if ($('.ajax-editor-inner').children().filter(':visible').length < 1) {
            $('.browser-finder').addClass('finder-has-focus');
            $('.browser-editor').removeClass('editor-has-focus');
        }
        else {
            $('.browser-finder').removeClass('finder-has-focus');
            $('.browser-editor').addClass('editor-has-focus');
        }
    },

    finderLoadMore: function() {
        if(($(this).scrollTop() + ($(this).innerHeight() + 300) >= $(this)[0].scrollHeight) && (window.avcms.browser.finder_loading != 1)) {
            avcms.browser.finder_loading = 1;

            if (avcms.browser.finder_page === undefined) {
                avcms.browser.finder_page = 1;
            }

            var new_page = avcms.browser.finder_page + 1;

            var form_serial = $('form[name="filter_form"]').serialize() + '&page=' + new_page;

            var finder = $(this).find('[data-url]');

            $.get(finder.data('url') + '?' + form_serial, function(data) {
                if (data) {
                    if(data.indexOf("NORESULTS") <= 0) {
                        finder.append(data);
                        avcms.browser.finder_loading = 0;
                        avcms.browser.finder_page = new_page;

                        $(".nano").nanoScroller({ iOSNativeScrolling: true });
                    }
                }
            })
        }
    },

    changeFinderFilters: function() {
        var form_serial = $('form[name="filter_form"]').serialize();

        var finder = $('.finder-ajax').find('[data-url]');
        avcms.browser.finder_loading = 1;

        $.get(finder.data('url') + '?' + form_serial, function(data) {
            if (data) {
                finder.html(data);
                avcms.browser.finder_loading = 0;
                avcms.browser.finder_page = 1;
            }
        })
    },

    editorFormChanged: function() {
        var id = $(this).attr('data-item-id');

        if (id != 0) {
            $('.header-label-container:visible').html('<span class="label label-warning animated flipInY">Edited</span>');
            $('.browser-finder-item[data-id="'+id+'"]').addClass('finder-item-edited');
            $('.browser-finder-item[data-id="'+id+'"]').removeClass('finder-item-saved');
        }
    },

    // Clear the add item form
    editorAddItemFormSubmitEvent: function(form, response_data) {
        var item_id = form.data('item-id');

        if (item_id === 0) {
            form[0].reset();
            form.find('.form-errors').html('');
        }
    },

    clearSearch: function() {
        $(this).closest('.input-group').find('input').val('');
        avcms.browser.changeFinderFilters();
    }
}