avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-add-image-file', function() {
        avcms.images.addImageFile($(this).parents('form'));
    });
    body.on('click', '.avcms-remove-image-file', avcms.images.removeImageFile);

    body.on('click', '.clear-image-thumbnails-cache', avcms.images.clearThumbnailsCache);

    avcms.event.addEvent('page-modified', function() {
        $('body').on('change', '.avcms-file-path-field, .file-selector-dropdown, [data-file-selector-group]', avcms.images.updateFiles);

        $('.avcms-image-files').sortable({
            handle: ".panel-heading"
        });

        avcms.images.loadBulkUpload();
        avcms.images.updateFiles();
    });

    avcms.event.addEvent('submit-form-success', avcms.image_submissions.onReviewSuccess);
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
    },

    updateFiles: function() {
        $('.avcms-image-files').find('.panel').each(function() {
            if (!$(this).find('.panel-body').length) {
                return;
            }

            var selected = $(this).find('[data-file-selector-target]').filter(':checked').val();

            var field = $(this).find('.avcms-file-path-field[name="'+selected+'"], .file-selector-dropdown[name="'+selected+'"]');

            if (!field.length) {
                return;
            }

            var url = field.val();

            if (!url) {
                return;
            }

            $(this).find('.avcms-image-filename').html(url);

            if (!url.startsWith("http")) {
                url = avcms.config.site_url + 'web/images/' + url;
            }

            $(this).find('.avcms-image-preview').html('<a href="'+url+'" target="_blank"><img src="' + url + '" style="max-width: 100%;max-height:200px;" /></a>');
        });
    },

    clearThumbnailsCache: function()
    {
        if (confirm(avcms.general.trans('Are you sure you want to clear the image thumbnail cache? This may cause some temporary strain on your server when images need to be re-generated.'))) {
            $.post(avcms.config.site_url+ 'admin/images/clear-thumbnail-cache', '', function(data) {
                if (data.success !== true) {
                    alert(data.error);
                }
            });
        }
    }
};

avcms.image_submissions = {
    onReviewSuccess: function(form)
    {
        if (form.attr('name') == 'review_submitted_image_form') {
            $('.browser-finder-item[data-id="'+form.data('item-id')+'"]').remove();
        }
    }
};
