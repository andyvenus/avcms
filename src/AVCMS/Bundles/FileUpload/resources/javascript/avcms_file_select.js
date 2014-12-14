avcms = avcms || {};

$(document).ready(function() {

    $('body').on('change', '[name=file_type]', avcms.file_select.changeSelectedClick);

    avcms.event.addEvent('page-modified', function () {

        avcms.file_select.doChange($('[name=file_type]').filter(':visible:checked'));

        var file_selector = $(".file_selector_dropdown").filter(':visible');

        file_selector.filter(':visible').select2({
            placeholder: "Find file",
            minimumInputLength: 2,
            ajax: {
                url: avcms.config.site_url + file_selector.data('file-select-url'),
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
                    $.ajax(avcms.config.site_url + file_selector.data('file-select-url'), {
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

    var file_upload = $('.file_upload').filter(':visible');

    file_upload.fileupload({
        url: avcms.config.site_url+file_upload.data('upload-url'),
        dataType: 'json',
        done: function (e, data) {
            $('.file-upload-progress').filter(':visible').html('');

            $.each(data.result.files, function (index, file) {
                $("input[name=file_type][value=file]").filter(':visible').prop('checked', true).change();
            });
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

avcms.file_select = {
    changeSelectedClick: function() {
        avcms.file_select.doChange($(this));
    },

    doChange: function(checkbox) {
        var target = checkbox.val();

        var name = checkbox.attr('name');

        var form = checkbox.closest('form');

        form.find('[name='+name+']').each(function() {
            form.find('[name='+$(this).val()+']').parents('.form-group').hide();
        });

        form.find('[name='+target+']').parents('.form-group').show();

        avcms.event.fireEvent('file-select-change', {target: target, name: name, form: form})
    }
};
