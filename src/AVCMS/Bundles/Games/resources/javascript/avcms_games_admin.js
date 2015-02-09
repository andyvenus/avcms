avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-get-game-dimensions', avcms.gamesAdmin.getDimensions);
    body.on('click', '.avcms-download-feed-game', avcms.gamesAdmin.downloadFeedGame);

    body.on('click', '.avcms-update-feed', function() {
        avcms.gamesAdmin.updateFeed($(this).parents('[data-feed-id]').data('feed-id'), false);
    });

    body.on('click', '.avcms-update-all-feeds', function() {
        var container_status = $('.avcms-feed-status');
        container_status.find('.avcms-buttons').hide();
        container_status.find('.avcms-pending').show();
        avcms.gamesAdmin.updateAllFeeds();
        $(this).fadeOut();
    });
});

avcms.gamesAdmin = {
    getDimensions: function() {
        var button = $(this);
        var selected = $('input[name="game_file[file_type]"]:checked').filter(':visible').val();

        if (selected !== 'file' && selected !== 'game_file[find]') {
            alert(avcms.general.trans('No file specified'));
            return;
        }

        button.attr('disabled', true);

        var form = $(this).closest('form');

        $.post(avcms.config.site_url+'admin/games/get-dimensions', {file: $('[name="'+selected+'"]').val()}, function(data) {
            button.attr('disabled', false);

            if (data.success) {
                form.find('input[name=width]').val(data.width);
                form.find('input[name=height]').val(data.height);
            }
            else {
                button.parent().find('.avcms-game-dimensions-error').text(data.error);
            }
        });

    },

    downloadFeedGame: function() {
        var category_id = $(this).siblings('select').val();
        var finder_item = $(this).parents('.browser-finder-item')
        var id = finder_item.data('id');

        $(this).attr('disabled');

        finder_item.find('.avcms-feed-game-buttons').hide();

        var status = finder_item.find('.avcms-feed-game-status');
        status.show().text('Downloading...');

        $.post(avcms.config.site_url+'admin/game-feeds/import', {id: id, category: category_id}, function(response) {
            if (response.success) {
                status.hide();
                var installed = finder_item.find('.avcms-feed-game-installed');
                installed.show();
                installed.find('a').attr('href', response.url);
            }
            else {
                status.text(response.error);
            }
        });
    },

    updateAllFeeds: function()
    {
        var selected_feed = $('.pending[data-feed-id]').first();

        if (selected_feed.length === 1) {
            avcms.gamesAdmin.updateFeed(selected_feed.data('feed-id'), false);
        }
        else {
            avcms.browser.changeFinderFilters();
        }
    },

    updateFeed: function(feed_id, all_feeds) {
        var container = $('.pending[data-feed-id='+feed_id+']');
        container.removeClass('pending');

        var container_status = container.find('.avcms-feed-status');

        container_status.find('.avcms-buttons, .avcms-pending').hide();
        container_status.find('.avcms-loading').show();

        $.post(avcms.config.site_url+'admin/game-feeds/download', {id: feed_id}, function(response) {
            container_status.find('.avcms-loading').hide();

            if (response.success) {
                container_status.find('.avcms-done').show().text(response.message);
                if (all_feeds) {
                    avcms.gamesAdmin.updateAllFeeds();
                }
                else {
                    avcms.browser.changeFinderFilters();
                }
            }
            else {
                container_status.find('.avcms-error').show().text(response.error);
            }
        });
    }
};
