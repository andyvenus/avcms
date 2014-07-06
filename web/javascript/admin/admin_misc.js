$(document).ready(function() {
    avcms.event.addEvent('page-modified', function() {
        $(".user_selector").select2({
            placeholder: "Find user",
            minimumInputLength: 2,
            ajax: {
                url: "http://localhost:8888/avcms/admin/username_suggest",
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
                    $.ajax("http://localhost:8888/avcms/admin/username_suggest", {
                        data: {
                            q: id,
                            id_search: 1
                        },
                        dataType: "json"
                    }).done(function(data) { callback(data[0]); });
                }
            }
        });


        $('[name=publish_date]').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            defaultDate: new Date()
        });

        $("[name='tags']").select2({
            tags:[],
            tokenSeparators: [","],
            width: '100%',
            dropdownCssClass: 'select2-display-none'
        });

    });

    $('body').on('keyup', '[data-slug-target]', avcms.misc.generateSlugDelay);

    $('body').on('change', '[name=slug]', avcms.misc.disableAutoGenerateSlug);

    $('body').on('click', '.slug_refresh_button', avcms.misc.generateSlugButton);
})

avcms.misc = {
    typingTimer: null,
    slugInput: null,

    autoGenerateSlug: function() {
        var input_field = avcms.misc.slugInput;
        var input = encodeURIComponent(input_field.val());
        var target_field_name = input_field.data('slug-target');
        var target_field = input_field.closest('form').find('[name='+target_field_name+']');

        $.ajax({
            url: 'http://localhost:8888/avcms/admin/generate_slug/' + input,
            dataType: 'json',
            success: function(data) {
                target_field.val(data.slug);
            }
        });
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
    }
}