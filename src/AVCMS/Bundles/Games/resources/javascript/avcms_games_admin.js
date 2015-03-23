avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-get-game-dimensions', avcms.gamesAdmin.getDimensions);
    body.on('click', '.avcms-download-feed-game', function() {
        avcms.gamesAdmin.downloadFeedGame($(this).parents('.browser-finder-item').data('id'));
    });
    body.on('click', '.avcms-bulk-download-feed-games', avcms.gamesAdmin.startBulkDownloadFeedGames);

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

    avcms.event.addEvent('page-modified', function() {
        $('.avcms-category-keywords').select2({
            tags: []
        })
    });

    avcms.event.addEvent('submit-form-success', function(form) {
        if (form.attr('name') == 'game-feed-categories') {
            avcms.browser.changeFinderFilters();
        }
    })
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
        }).fail(avcms.admin.showServerException);

    },

    downloadFeedGame: function(game_id) {
        var finder_item = $('.browser-finder-item[data-id='+game_id+']');
        finder_item.removeClass('avcms-pending');

        var category_id = finder_item.find('select').val();
        var id = finder_item.data('id');

        finder_item.find('.avcms-feed-game-buttons').hide();

        finder_item.find('.avcms-feed-game-pending').hide();
        var status = finder_item.find('.avcms-feed-game-downloading');
        status.show();

        var thumbnail_loader = finder_item.find('.avcms-thumbnail-loader')
        thumbnail_loader.show().html('<img src="'+avcms.config.site_url+'/web/resources/CmsFoundation/images/loader-round.gif">');

        $.post(avcms.config.site_url+'admin/game-feeds/import', {id: id, category: category_id}, function(response) {
            thumbnail_loader.hide();
            finder_item.find('input:checkbox').remove();

            if (response.success) {
                status.hide();
                var installed = finder_item.find('.avcms-feed-game-installed');
                installed.show();
                installed.find('a').attr('href', response.url);
            }
            else {
                status.text(response.error);
            }

            avcms.gamesAdmin.bulkDownloadFeedGames();
        }).fail(avcms.admin.showServerException);
    },

    startBulkDownloadFeedGames: function() {
        var checkboxes = $('.finder-item-checkbox-container :checkbox:checked');

        checkboxes.each(function() {
            var container = $(this).parents('[data-id]');
            container.addClass('avcms-pending');
            container.find('.avcms-feed-game-buttons').hide();
            container.find('.avcms-feed-game-pending').show();
        });

        avcms.gamesAdmin.bulkDownloadFeedGames();
    },

    bulkDownloadFeedGames: function() {
        var finder_item = $('.avcms-pending.browser-finder-item[data-id]').first();

        if (finder_item.length === 1) {
            avcms.gamesAdmin.downloadFeedGame(finder_item.data('id'));
        }
    },

    updateAllFeeds: function()
    {
        var selected_feed = $('.pending[data-feed-id]').first();

        if (selected_feed.length === 1) {
            avcms.gamesAdmin.updateFeed(selected_feed.data('feed-id'), true);
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
        }).fail(avcms.admin.showServerException);
    }
};
