avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '.avcms-get-game-dimensions', avcms.gamesAdmin.getDimensions);
    $('body').on('click', '.avcms-download-feed-game', avcms.gamesAdmin.downloadFeedGame);
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
    }
};
