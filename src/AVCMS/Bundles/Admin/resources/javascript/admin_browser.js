var avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('change', '.ajax-editor form', avcms.browser.editorFormChanged);
    body.on('change', 'form[name="filter_form"] select', avcms.browser.changeFinderFilters);
    body.on('keyup', 'form[name="filter_form"] input', avcms.browser.changeFinderFilters);
    body.on('click', '.clear-search', avcms.browser.clearSearch);

    body.on('click', '.select-all-button', avcms.browser.selectAllFinderResults);
    body.on('click', '.deselect-all-button', avcms.browser.deselectAllFinderResults);

    body.on('click', '.finder-item-checkbox-container :checkbox', avcms.browser.checkFinderChecked);

    body.on('click', '[data-bulk-delete-url]', avcms.browser.deleteCheckedResults);
    body.on('click', '[data-toggle-published-url], [data-toggle-unpublished-url]', avcms.browser.togglePublishedCheckedResults);

    body.on('click', '.avcms-delete-item', avcms.browser.deleteItemButton);

    body.on('click', '.avcms-toggle-published', avcms.browser.togglePublishedButton);

    avcms.event.addEvent('page-modified', avcms.browser.setBrowserFocus);
    avcms.browser.setBrowserFocus();

    avcms.event.addEvent('submit-form-complete', avcms.browser.browserFormSubmitted)

    avcms.event.addEvent('submit-form-success', avcms.browser.editorAddItemFormSubmitEvent);

    avcms.event.addEvent('page-modified', function() {
        $('.nano-content').on('scroll', avcms.browser.finderLoadMore);
        $(window).on('scroll', avcms.browser.finderLoadMore);

        avcms.browser.finder_loading = 0;
    });

});

avcms.browser = {
    finder_loading: 0,

    browserFormSubmitted: function(form, response_data) {
        if ($('.browser').length < 1) {
            return;
        }

        if (typeof(response_data) !== 'undefined' && response_data.form.has_errors === false) {
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

                    $('[data-id="'+id+'"][data-field="'+field_name+'"], [data-id="'+id+'"] [data-field="'+field_name+'"]').text(field_value).text();
                });

                if (id != 0) {
                    $('.header-label-container:visible').html('<span class="label label-success animated flipInY">Saved</span>');
                }

                $('.browser-finder-item[data-id="'+id+'"]').addClass('finder-item-saved').removeClass('finder-item-edited');
            }

            var finder_inner = $('.finder-ajax > [data-url]');
            var finder_url = finder_inner.data('url');

            $.get(finder_url + '?id=' + response_data.id , function(data) {
                if (data) {
                    if (id != 0) {
                        var item_html = $($.parseHTML(data)).filter('[data-id="'+id+'"]').html();
                        finder_inner.find('[data-id="'+id+'"]').html(item_html);
                    }
                    else {
                        $('.remove-header').remove();
                        var processed_data = $($.parseHTML(data));
                        processed_data.filter('.browser-finder-header').addClass('remove-header');
                        processed_data.find('.page').html('New');
                        finder_inner.prepend(processed_data);
                    }
                }
            })
        }
    },

    setBrowserFocus: function() {
        var finder = $('.browser-finder');
        var editor = $('.browser-editor');
        if ($('.ajax-editor-inner').children().filter(':visible').length < 1) {
            if (!finder.hasClass('finder-has-focus')) {
                finder.addClass('finder-has-focus');
                editor.removeClass('editor-has-focus');
            }
        }
        else {

            if (!editor.hasClass('editor-has-focus')) {
                finder.removeClass('finder-has-focus');
                editor.addClass('editor-has-focus');
            }
        }
    },

    finderLoadMore: function() {
        if ($('.browser-finder').length < 1) {
            return;
        }

        var finder_div;
        var scroll_height;
        if (!$(this).hasClass('nano-content')) {
            //console.log('loading more');
            finder_div = $('.browser-finder-results').find('.nano-content');
            scroll_height = $(document).height();
        }
        else {
            finder_div = $(this);
            scroll_height = $(this)[0].scrollHeight;
        }

        if(($(this).scrollTop() + ($(this).innerHeight() + 300) >= scroll_height) && (window.avcms.browser.finder_loading != 1)) {
            avcms.browser.finder_loading = 1;

            if (avcms.browser.finder_page === undefined) {
                avcms.browser.finder_page = 1;
            }

            var current_page = finder_div.data('page');

            if (!current_page) {
                current_page = 1;
            }

            var new_page = current_page + 1;

            var form_serial = $('form[name="filter_form"]').serialize() + '&page=' + new_page;

            var finder = finder_div.find('[data-url]');

            avcms.admin.mainLoaderOn();
            $.get(finder.data('url') + '?' + form_serial, function(data) {
                if (data) {
                    if(data.indexOf("NORESULTS") <= 0) {
                        finder.append(data);
                        avcms.browser.finder_loading = 0;
                        finder_div.data('page', new_page);

                        $('.nano-pane').hide();
                        $(".nano").nanoScroller({ iOSNativeScrolling: false });
                        $('.nano-pane').show();
                    }
                }
                avcms.admin.mainLoaderOff();
            })
        }
    },

    changeFinderFilters: function() {
        var form_serial = $('form[name="filter_form"]').serialize();

        var finder = $('.finder-ajax').find('[data-url]');
        avcms.browser.finder_loading = 1;

        avcms.admin.mainLoaderOn();
        $.get(finder.data('url') + '?' + form_serial, function(data) {
            if (data) {
                finder.html(data);
                avcms.browser.finder_loading = 0;
                $('.finder-ajax').parents('.nano-content').data('page', 1);

                $(".nano").nanoScroller({ iOSNativeScrolling: false });
            }
            avcms.admin.mainLoaderOff();
        })
    },

    editorFormChanged: function() {
        var id = $(this).attr('data-item-id');

        var finder_item = $('.browser-finder-item[data-id="'+id+'"]');
        var header_label_container = $('.header-label-container:visible');

        if (id != 0 && header_label_container.find('.label-warning').length < 1) {
            header_label_container.html('<span class="label label-warning animated flipInY">Edited</span>');
            finder_item.addClass('finder-item-edited');
            finder_item.removeClass('finder-item-saved');
        }
    },

    // Clear the add item form
    editorAddItemFormSubmitEvent: function(form, response_data) {
        var item_id = form.data('item-id');

        if (item_id === 0) {
            form[0].reset();
            form.find('.form-messages').html('');
        }
    },

    clearSearch: function() {
        $(this).closest('.input-group').find('input').val('');
        avcms.browser.changeFinderFilters();
    },

    checkFinderChecked: function() {
        var total_checked = $('.finder-item-checkbox-container :checkbox:checked').length;
        var checked_options_height = $('.browser-finder-checked-options').outerHeight();

        if (total_checked) {
            $('.browser-finder-checked-options').show();
            $('.finder-ajax').css('padding-bottom', checked_options_height);
        }
        else {
            $('.browser-finder-checked-options').hide();
            $('.finder-ajax').css('padding-bottom', 0);
        }

        $('.selected-count').text(total_checked);
        avcms.browser.getFinderSelectedIds()
    },

    selectAllFinderResults: function() {
        $('.finder-item-checkbox-container :checkbox').prop('checked', true);
        avcms.browser.checkFinderChecked();
    },

    deselectAllFinderResults: function() {
        $('.finder-item-checkbox-container :checkbox').prop('checked', false);
        avcms.browser.checkFinderChecked();
    },

    getFinderSelectedIds: function() {
        var selected_ids = [];
        $('.finder-item-checkbox-container :checkbox:checked').each(function() {
            selected_ids.push($(this).parents('.browser-finder-item').data('id'));
        });

        return selected_ids;
    },

    deleteItemButton: function() {
        if (confirm('Are you sure you want to delete this item?')) {
            var button = $(this);
            var id = button.parents('[data-id]').data('id');

            $.ajax({
                type: "POST",
                url: $(this).data('delete-url'),
                data: {'ids': id},
                dataType: 'json',
                success: function(data) {
                    if (data.success == 0) {
                        alert('Error: '+data.error);
                    }
                    else {
                        button.parents('[data-id]').remove();
                        avcms.browser.checkFinderChecked();

                        var current_editor_url = $('.ajax-editor-inner').filter(':visible').data('ajax-url');
                        if (current_editor_url.indexOf(id) > -1) {
                            avcms.nav.goToPage($('.expand-browser > a').attr('href'));
                        }
                    }
                }
            })
        }
    },

    deleteCheckedResults: function() {
        if (confirm('Are you sure you want to delete these '+$('.selected-count').text()+' items?')) {
            var url = $(this).data('bulk-delete-url');

            $.ajax({
                type: "POST",
                url: url,
                data: {'ids': avcms.browser.getFinderSelectedIds()},
                dataType: 'json',
                success: function(data) {
                    $('.finder-item-checkbox-container :checkbox:checked').each(function() {
                        $(this).parents('.browser-finder-item').remove();
                        avcms.browser.checkFinderChecked();
                    });
                }
            });
        }
    },

    togglePublishedButton: function() {
        var button = $(this);
        var id = button.parents('[data-id]').data('id');

        if (id == undefined) {
            id = button.parents('[data-item-id]').data('item-id');
        }

        $(this).children('.glyphicon').toggleClass("glyphicon-eye-open glyphicon-eye-close");
        $(this).toggleClass("btn-default btn-danger");

        var published;
        if ($(this).hasClass('btn-danger')) {
            published = 0;
        }
        else {
            published = 1;
        }

        console.log(id);

        $.ajax({
            type: "POST",
            url: $(this).data('toggle-publish-url'),
            data: {'id': id, 'published': published},
            dataType: 'json',
            success: function(data) {
                var form = $('form[data-item-id="'+id+'"');
                form.find('[name="published"][value="'+published+'"]').prop("checked", true);
            }
        })
    },

    togglePublishedCheckedResults: function() {
        var url;
        if ($(this).data('toggle-published-url')) {
            url = $(this).data('toggle-published-url');
            published = 1;
        }
        else {
            url = $(this).data('toggle-unpublished-url');
            published = 0;
        }

        var ids = avcms.browser.getFinderSelectedIds();

        $.ajax({
            type: "POST",
            url: url,
            data: {'ids': ids, 'published': published},
            dataType: 'json',
            success: function(data) {
                $.each(ids, function(key, id) {
                    var form = $('form[data-item-id="'+id+'"');
                    form.find('[name="published"][value="'+published+'"]').prop("checked", true);

                    var finder_item = $('.browser-finder-item[data-id='+id+']');
                    var published_button = finder_item.find('.avcms-toggle-published');

                    if (published === 1) {
                        published_button.removeClass('btn-danger');
                        published_button.addClass('btn-default');
                        published_button.children('.glyphicon').removeClass('glyphicon-eye-close');
                        published_button.children('.glyphicon').addClass('glyphicon-eye-open');
                    }
                    else {
                        published_button.addClass('btn-danger');
                        published_button.removeClass('btn-default');
                        published_button.children('.glyphicon').addClass('glyphicon-eye-close');
                        published_button.children('.glyphicon').removeClass('glyphicon-eye-open');
                    }
                })
            }
        });
    }
}
