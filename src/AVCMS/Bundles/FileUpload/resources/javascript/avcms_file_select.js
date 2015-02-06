avcms = avcms || {};

$(document).ready(function() {

    $('body').on('change', '[data-file-selector-group]', avcms.file_select.changeSelectedClick);
    $('body').on('click', '.grab-file-button', avcms.file_select.grabFile);

    avcms.event.addEvent('page-modified', function () {

        $('[data-file-selector-target]').filter(':visible:checked').each(function() {
            avcms.file_select.doChange($(this));
        });

        var file_selector = $("input.file-selector-dropdown");

        file_selector.each(function() {
            var field_group = $(this).attr('name').substr(0, $(this).attr('name').indexOf('['));

            $(this).select2({
                placeholder: "Find file",
                minimumInputLength: 2,
                ajax: {
                    url: avcms.config.site_url + $(this).data('file-select-url') + '?type='+field_group,
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
                initSelection: function (element, callback) {
                    var id = $(element).val();
                    if (id !== "") {
                        $.ajax(avcms.config.site_url + $(this).data('file-select-url'), {
                            data: {
                                q: id,
                                id_search: 1
                            },
                            dataType: "json"
                        }).done(function (data) {
                            callback(data[0]);
                        });
                    }
                }
            });
        });

        var file_upload = $('form').filter(':visible').find('.file-upload');

        file_upload.each(function() {
            var field_group = $(this).attr('name').substr(0, $(this).attr('name').indexOf('['));
            var file_field = $('[data-file-selector-group="'+field_group+'"]').filter(':visible').data('file-selector-target');

            $(this).fileupload({
                url: avcms.config.site_url+$(this).data('upload-url')+'?type='+field_group,
                dataType: 'json',
                done: function (e, data) {
                    if (data.result.success === true) {
                        $('.file-upload-progress').filter(':visible').html('');

                        $('form').filter(':visible').find('input[name="'+file_field+'"]').val(data.result.file);
                        $('input[name="'+field_group+'[file_type]"][value="'+file_field+'"]').filter(':visible').prop('checked', true).change();
                    }
                    else {
                        alert(data.result.error);
                    }
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('.file-upload-progress').filter(':visible').html('Uploading: '+progress+'%');
                },
                fail: function() {
                    alert('f');
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });
        });

    avcms.event.addEvent('submit-form', function (form, data) {
        var field = form.find('.file-upload');
        var messages = $(form).find('.form-messages');
        var return_val = true;

        if (field.length > 0) {
            field.each(function() {
                var field_group = $(this).attr('name').substr(0, $(this).attr('name').indexOf('['));

                var field_val = $('[data-file-selector-group="'+field_group+'"]').filter(':checked').val();

                if (field_val == field_group+'[upload]' || field_val == field_group+'[grab]') {
                    messages.html('');

                    messages.append('<div class="alert alert-danger animated bounce">'+avcms.general.trans('Please upload the file before saving')+'</div>');

                    return_val = false;
                }

                if (field_val == field_group+'[find]') {
                    var file_field = $('[data-file-selector-group="'+field_group+'"]').filter(':checked').data('file-selector-target');
                    form.find('[name="'+file_field+'"]').val(form.find('[name="'+field_group+'[find]"]').val());
                }
            })
        }

        return return_val;
    });
});

avcms.file_select = {
    changeSelectedClick: function() {
        avcms.file_select.doChange($(this));
    },

    doChange: function(checkbox) {
        var target = checkbox.val();

        var name = checkbox.attr('name');

        var form = checkbox.closest('form');

        form.find('[name="'+name+'"]').each(function() {
            form.find('[name="'+$(this).val()+'"]').parents('.form-group').hide();
        });

        form.find('[name="'+target+'"]').parents('.form-group').show();

        avcms.event.fireEvent('file-select-change', {target: target, name: name, form: form})
    },

    grabFile: function() {
        var urlField = $('.grab-file-field').filter(':visible');
        var btn = $(this);

        var origBtn = $(this).html();
        $(this).html(avcms.general.trans('Downloading...'));

        var field_group = urlField.attr('name').substr(0, urlField.attr('name').indexOf('['));

        $.post(avcms.config.site_url + urlField.data('grab-file-url')+'?type='+field_group, 'file_url='+urlField.val(), function(data) {
            if (data.success === true) {
                var file_field = $('[data-file-selector-group="'+field_group+'"]').filter(':visible').data('file-selector-target');

                $('form').filter(':visible').find('input[name="'+file_field+'"]').val(data.file);
                $('input[name="'+field_group+'[file_type]"][value="'+file_field+'"]').filter(':visible').prop('checked', true).change();
            }
            else {
                alert(data.error);
            }

            btn.html(origBtn);
        }, 'json');
    }
};
