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

        if (avcms.general.isMobile() === false) {
            $("select:not(.no_select2)").select2({
                minimumResultsForSearch: 10
            });
        }

        $(".nano").nanoScroller({ iOSNativeScrolling: false });

        //icons select2
        $('select.icon-selector').select2({
            formatResult: avcms.admin.iconSelectFormat,
            formatSelection: avcms.admin.iconSelectFormat,
            escapeMarkup: function(m) { return m; }
        });

        // fix forms in IE9 & other browsers that don't support the history API
        if (!window.history || !window.history.pushState) {
            $('form:not([action]), form.form-fix').each(function () {
                $(this).attr('action', avcms.nav.getCurrentUrl());
                $(this).addClass('form-fix');
            });
        }
    });

    var body = $('body');

    body.on('keyup', '[data-slug-target]', avcms.admin.generateSlugDelay);

    body.on('change', '[name=slug]', avcms.admin.disableAutoGenerateSlug);

    body.on('click', '.slug_refresh_button', avcms.admin.generateSlugButton);

    body.on('submit', 'form:not(.no-ajax)', avcms.form.submitForm);
    body.on('click', '.reset-button', avcms.form.resetForm);

    body.tooltip({
        selector: '[data-toggle="tooltip"]'
    });

    $(document).ajaxSuccess(function(event, data) {
        if (data.responseJSON !== undefined) {
            if (data.responseJSON.error !== undefined) {
                console.log(data.responseJSON.error);
            }
        }
    });
})

avcms.admin = {
    typingTimer: null,
    slugInput: null,

    autoGenerateSlug: function() {
        var input_field = avcms.admin.slugInput;

        if (!input_field.val()) {
            return;
        }

        var input = encodeURIComponent(input_field.val());
        var target_field_name = input_field.data('slug-target');
        var target_field = input_field.closest('form').find('[name='+target_field_name+']');

        var button = $('.slug_refresh_button').filter(':visible');

        var button_html = button.html();

        button.html('<img src="'+avcms.config.site_url+'web/resources/CmsFoundation/images/loader-round.gif" width="14">');

        $.ajax({
            url: avcms.config.site_url+"admin/generate_slug/" + input,
            dataType: 'json',
            success: function(data) {
                button.html(button_html);

                target_field.val(data.slug);
            }
        });
    },

    mainLoaderOn: function() {
        $('.admin-header-logo').hide();
        $('.admin-header-logo-loader').show();
    },

    mainLoaderOff: function() {
        $('.admin-header-logo-loader').fadeOut(400, function() {
            $('.admin-header-logo').fadeIn();
        });
    },

    generateSlugDelay: function() {
        avcms.admin.slugInput = $(this);
        var input_field = $(this);
        var target_field_name = input_field.data('slug-target');
        var target_field = input_field.closest('form').find('[name='+target_field_name+']');

        if (target_field.attr('value') == null && target_field.data('modified') == null) {
            if (avcms.admin.typingTimer) {
                clearTimeout(avcms.admin.typingTimer);
            }
            avcms.admin.typingTimer = setTimeout(avcms.admin.autoGenerateSlug, 600);
        }

        return true;
    },

    generateSlugButton: function() {
        avcms.admin.slugInput = $(this).closest('form').find('[data-slug-target]');
        avcms.admin.autoGenerateSlug();
    },

    disableAutoGenerateSlug: function() {
        $(this).data('modified', 'true');
    },

    iconSelectFormat: function(state) {
        if (!state.id) return state.text; // optgroup
        return "<span class='" + state.id + "'/></span> " + state.text;
    },

    showServerException: function(jqXHR, textStatus, errorThrown) {
        alert("Error: " + avcms.admin.getServerException(jqXHR, textStatus, errorThrown));
    },

    getServerException: function(jqXHR, textStatus, errorThrown) {
        var exception = $($.parseHTML(jqXHR.responseText)).find('.error-message, .exc-message');

        if (exception.length > 0) {
            errorThrown = exception.text();
        }

        return errorThrown;
    },

    reloadMenu: function() {
        $.get(avcms.config.site_url + 'admin/main-menu', function(data) {
            $('#avcms-admin-menu-items').html(data);
            avcms.nav.onPageModified();
        });
    }
};
