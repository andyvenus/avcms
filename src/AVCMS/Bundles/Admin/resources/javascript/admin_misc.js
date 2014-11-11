$(document).ready(function() {
    avcms.event.addEvent('page-modified', function() {
        $(".user_selector").filter(':visible').select2({
            placeholder: "Find user",
            minimumInputLength: 2,
            ajax: {
                url: avcms.config.site_url+"admin/username_suggest",
                dataType: 'json',
                data: function (term, page) {
                    return {
                        q: term
                    };
                },
                results: function (data, page) { // parse the results into the format expected by Select2.
                    return {results: data};
                }
            },
            initSelection: function(element, callback) {
                var id=$(element).val();
                if (id!=="") {
                    $.ajax(avcms.config.site_url+"admin/username_suggest", {
                        data: {
                            q: id,
                            id_search: 1
                        },
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            }
        });


        $('[name=publish_date], [data-date-selector]').datetimepicker({
            format: 'Y-m-d H:i',
            defaultDate: new Date()
        });

        var tags_field = $("[name='tags']");

        if (tags_field.length > 0) {
            tags_field.select2({
                tags: [],
                tokenSeparators: [","],
                width: '100%'
            });

            if (avcms.misc.tagsCache === null) {
                $.post(avcms.config.site_url+'tags/suggestions', null, function(data) {
                    avcms.misc.tagsCache = data;

                    tags_field.select2({
                        tags: avcms.misc.tagsCache,
                        tokenSeparators: [","],
                        width: '100%'
                    });

                }, 'json');
            }
        }
    });

    var body = $('body');

    body.on('keyup', '[data-slug-target]', avcms.misc.generateSlugDelay);

    body.on('change', '[name=slug]', avcms.misc.disableAutoGenerateSlug);

    body.on('click', '.slug_refresh_button', avcms.misc.generateSlugButton);

    body.on('click', '#menu_toggle, .admin-menu a', avcms.misc.toggleMenu);

    body.on('submit', 'form', avcms.form.submitForm);
    body.on('click', '.reset-button', avcms.form.resetForm);

    $(document).ajaxSuccess(function(event, data) {
        if (data.responseJSON !== undefined) {
            if (data.responseJSON.error !== undefined) {
                console.log(data.responseJSON.error);
            }
        }
    });
})

avcms.misc = {
    typingTimer: null,
    slugInput: null,
    tagsCache: null,

    autoGenerateSlug: function() {
        var input_field = avcms.misc.slugInput;

        if (!input_field.val()) {
            return;
        }

        var input = encodeURIComponent(input_field.val());
        var target_field_name = input_field.data('slug-target');
        var target_field = input_field.closest('form').find('[name='+target_field_name+']');

        $.ajax({
            url: avcms.config.site_url+"admin/generate_slug/" + input,
            dataType: 'json',
            success: function(data) {
                target_field.val(data.slug);
            }
        });
    },

    mainLoaderOn: function() {
        $('.lightbar').show();
    },

    mainLoaderOff: function() {
        $('.lightbar').fadeOut(1000);
    },

    generateSlugDelay: function() {
        avcms.misc.slugInput = $(this);
        var input_field = $(this);
        var target_field_name = input_field.data('slug-target');
        var target_field = input_field.closest('form').find('[name='+target_field_name+']');

        if (target_field.attr('value') == null && target_field.data('modified') == null) {
            if (avcms.misc.typingTimer) {
                clearTimeout(avcms.misc.typingTimer);
            }
            avcms.misc.typingTimer = setTimeout(avcms.misc.autoGenerateSlug, 600);
        }

        return true;
    },

    generateSlugButton: function() {
        avcms.misc.slugInput = $(this).closest('form').find('[data-slug-target]');
        avcms.misc.autoGenerateSlug();
    },

    disableAutoGenerateSlug: function() {
        $(this).data('modified', 'true');
    },

    toggleMenu: function() {
        $('.admin-menu').toggleClass('admin-menu-focused');
    }
}