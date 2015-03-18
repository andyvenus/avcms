avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('click', '.new-upload-button', avcms.wallpaper_bulk.newUpload);
    body.on('click', '.clear-wallpaper-cache', avcms.wallpapers.clearWallpapersCache);

    avcms.event.addEvent('page-modified', avcms.wallpaper_bulk.loadFileUploader);
    avcms.event.addEvent('submit-form-success', avcms.wallpaper_bulk.folderAdded);
    avcms.event.addEvent('submit-form-success', avcms.wallpaper_submissions.onReviewSuccess);
});

avcms.wallpaper_bulk = {
    newUpload: function() {
        avcms.nav.refreshSection('.ajax-editor-inner', 'editor');
    },

    loadFileUploader: function() {
        var field = $('.bulk-file-upload');

        if (field.length > 0) {
            field.fileupload({
                url: avcms.config.site_url + 'admin/wallpapers/upload',
                dataType: 'json',
                add: function (e, data) {
                    var file = data.files[0];

                    $('.selected-files-area').append('<div class="well well-sm clearfix" data-image-name="' + file.name + '">' +
                    '<div class="col-md-6"> <img class="new-image" src="" width="90"/> ' + file.name + '</div>' +
                    '<div class="col-md-6 text-right image-upload-progress"></div>' +
                    '</div>');

                    var file_extension = file.name.split('.').pop();
                    var valid_extensions = ['png', 'gif', 'jpg', 'jpeg', 'bmp'];

                    if (valid_extensions.indexOf(file_extension) !== -1 && window.FileReader !== undefined) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('.new-image[src=""]').first().attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);
                    }
                    else {
                        $('.new-image[src=""]').first().remove();
                    }

                    data.context = $('.start-upload-btn').show().click(function () {
                        data.submit();
                    });
                },
                start: function (e) {
                    $('.bulk-upload').slideUp();
                },
                stop: function (e) {
                    $('.new-upload').slideDown();
                },
                done: function (e, data) {
                    var name = data.files[0].name;
                    var progresser = $('[data-image-name="' + name + '"]').find('.image-upload-progress');
                    if (data.result.success === true) {
                        progresser.html('<div class="label label-success">100%</div>');
                    }
                    else {
                        progresser.html('<div class="label label-danger">' + data.result.error + '</div>');
                    }
                },
                progress: function (e, data) {
                    var name = data.files[0].name;
                    var progress = parseInt(data.loaded / data.total * 100, 10);

                    var progresser = $('[data-image-name="' + name + '"]').find('.image-upload-progress');

                    progresser.html('<div class="label label-info">' + progress + '%</div>');
                },
                fail: function (e, data) {
                    alert('Something went wrong. Check your browser\'s console for more details');
                    console.log(data);
                }
            }).prop('disabled', !$.support.fileInput)
                .parent().addClass($.support.fileInput ? undefined : 'disabled');
        }
    },

    folderAdded: function(form, data) {
        if ($(form).attr('name') == 'wallpaper_new_folder') {
            avcms.browser.changeFinderFilters();

            var folder_field = $('select[name=folder]');
            if (folder_field.length > 0) {
                var value = data.folder;
                folder_field.append($("<option></option>")
                    .attr("value", value)
                    .text(value));
                folder_field.select2('val', value);
            }
        }
    }
};

avcms.wallpapers = {
    clearWallpapersCache: function()
    {
        if (confirm(avcms.general.trans('Are you sure you want to clear the wallpaper cache? This may cause some temporary strain on your server when images need to be re-generated.'))) {
            $.post(avcms.config.site_url+ 'admin/wallpapers/clear-cache', '', function(data) {
                if (data.success !== true) {
                    alert(data.error);
                }
            });
        }
    }
};

avcms.wallpaper_submissions = {
    onReviewSuccess: function(form)
    {
        if (form.hasClass('review-wallpaper-submission')) {
            $('.browser-finder-item[data-id="'+form.data('item-id')+'"]').remove();
        }
    }
}
