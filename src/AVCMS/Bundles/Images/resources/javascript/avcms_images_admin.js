avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-add-image-file', function() {
        avcms.images.addImageFile($(this).parents('form'));
    });
    body.on('click', '.avcms-remove-image-file', avcms.images.removeImageFile);

    avcms.event.addEvent('page-modified', function() {
        var container = $('.avcms-image-files').filter(':visible');
        if (container.length != 0 && container.find('.panel').length == 0) {
            var form = $('form[data-item-id]').filter(':visible');

            avcms.images.addImageFile(form);
        }

        $('body').on('change', '.avcms-file-path-field, .file-selector-dropdown', function() {
            $(this).parents('.panel').find('.avcms-image-filename').html($(this).val());
        });

        $('.avcms-image-files').sortable();

        avcms.images.loadBulkUpload();
    });
});

avcms.images = {
    newFileCount: 0,
    bulkUploadText: null,

    addImageFile: function(form) {
        var file_fields = form.find('.avcms-new-image').first().html();

        avcms.images.newFileCount++;

        $('.avcms-image-files').filter(':visible').append(file_fields.split('new-files').join('new-'+avcms.images.newFileCount+''));

        avcms.nav.onPageModified();

        return avcms.images.newFileCount;
    },

    removeImageFile: function() {
        if ($('.avcms-image-files').filter(':visible').find('.panel').length == 1) {
            alert('There must be at least one image file');
            return;
        }

        $(this).parents('.panel').remove();
    },

    loadBulkUpload: function() {
        var file_upload = $('form').filter(':visible').find('.avcms-images-bulk-upload');

        file_upload.fileupload({
            url: avcms.config.site_url + 'admin/images/upload',
            dataType: 'json',
            done: function (e, data) {
                if (data.result.success === true) {
                    var id = avcms.images.addImageFile($('form[data-item-id]').filter(':visible'));
                    $('input[name="images[new-'+id+'][file]"]').val(data.result.file).change();
                }
                else {
                    alert(data.result.error);
                }
            },
            progressall: function (e, data) {
                var button = $('.fileinput-button').filter(':visible');
                if (avcms.images.bulkUploadText === null) {
                    avcms.images.bulkUploadText = button.html();
                }

                var progress = parseInt(data.loaded / data.total * 100, 10);
                button.html('Uploading: '+progress+'%');
            },
            fail: function() {
                alert('Something went wrong uploading the file');
            },
            stop: function (e) {
                $('.fileinput-button').filter(':visible').html(avcms.images.bulkUploadText);
                avcms.images.loadBulkUpload();
            }
        }).prop('disabled', !$.support.fileInput)
            .parent().addClass($.support.fileInput ? undefined : 'disabled');
    }
};
