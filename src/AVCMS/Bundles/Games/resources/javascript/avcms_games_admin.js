avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '.avcms-get-game-dimensions', avcms.gamesAdmin.getDimensions);
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
                alert(data.error);
            }
        });

    }
};
