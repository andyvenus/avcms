$(document).ready(function() {
    var body = $('body');

    body.on('change', 'form[name=edit_video] .avcms-file-path-field', function() {
        if (avcms.videosAdmin.lastUrl == $(this).val()) {
            return;
        }

        avcms.videosAdmin.getVideoInfo($(this).val());

        avcms.videosAdmin.lastUrl = $(this).val();
    });

    body.on('paste', 'form[name=edit_video] .avcms-file-path-field', function() {
        if (avcms.videosAdmin.lastUrl == $(this).val()) {
            return;
        }

        var field = $(this);

        setTimeout(function() {
            avcms.videosAdmin.getVideoInfo(field.val());

            avcms.videosAdmin.lastUrl = field.val();
        }, 100);
    });

    body.on('change', '[name="video_file[file_type]"]', function() {
        if ($(this).val() !== 'file') {
            $('.provider-info').hide();
        } else {
            $('.provider-info').show();
        }
    });

    body.on('click', '.avcms-import-video', avcms.videosAdmin.importSingleVideo);

    body.on('click', '.avcms-bulk-import-videos', avcms.videosAdmin.bulkImportVideos);

    body.on('click', '.video-thumbnail', avcms.videosAdmin.selectVideo);
});

avcms.videosAdmin = {
    lastUrl: null,

    getVideoInfo: function(url) {
        $('.provider-info').html('Provider: ' + avcms.general.loaderImg);

        $.post(avcms.config.site_url + 'admin/videos/get-info', {url: url}, function(response) {
            if (!response.success) {
                alert(response.error);

                return;
            }

            var form = $('[name=edit_video]').filter(':visible');

            if (response.provider.id !== 'none') {
                $.each(response.video, function (key, value) {
                    if (key == 'file') {
                        return;
                    }

                    if (key == 'tags') {
                        var split = value.split(',');
                        form.find('[name="' + key + '"]').select2('val', split);
                        return;
                    }

                    form.find('[name="' + key + '"]').val(value);
                });

                $('.provider-info').html('Provider: <span class="label label-default animated flipInY">' + response.provider.name + '</span>');
            } else {
                $('.provider-info').html('Provider: <span class="label label-default animated flipInY">None</span>');
            }



        }, 'json');
    },

    importSingleVideo: function() {
        var item = $(this).parents('.grid-item');

        var category = item.find('[name=category]').val();

        var id = item.data('id');

        var categories = {};
        categories[id] = category;

        avcms.videosAdmin.importVideos([id], categories);
    },

    bulkImportVideos: function() {
        var ids = avcms.browser.getFinderSelectedIds();
        var categories = {};

        var bulkCategory = $(this).siblings('select[name=category]').val();

        $.each(ids, function(key, id) {
            if (bulkCategory == 0) {
                categories[id] = $('.grid-item[data-id="'+id+'"]').find('[name=category]').val();
            } else {
                categories[id] = bulkCategory;
            }
        });

        avcms.videosAdmin.importVideos(ids, categories);
    },

    importVideos: function(ids, categories) {
        avcms.admin.mainLoaderOn();
        $.post(avcms.config.site_url + 'admin/import-videos/import', {ids: ids, categories: categories}, function(response) {
            avcms.admin.mainLoaderOff();
            if (response.success) {
                $.each(response.urls, function(id, url) {
                    var item = $('.grid-item[data-id='+id+']');

                    item.find('.finder-item-checkbox-container > input').prop('disabled', true).prop('checked', false);

                    item.find('.avcms-import-video').remove();

                    item.find('.grid-item-footer').append('<a href="'+url+'" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-pencil"></span> Edit</a>')
                });

                avcms.browser.checkFinderChecked();
            }
        });
    },

    selectVideo: function() {
        var checkbox = $(this).parents('.grid-item').find('input[type=checkbox]').filter(':not([disabled])');

        checkbox.prop('checked', !checkbox.prop('checked'));

        avcms.browser.checkFinderChecked();
    }
};
