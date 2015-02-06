avcms = avcms || {};

$(document).ready(function() {
    if ($('#avcms-game-container').length > 0) {
        window.onresize = avcms.games.resizeGame;
        avcms.games.resizeGame();
    }
});

avcms.games = {
    resizeGame: function() {
        var game_container = $('#avcms-game-container');
        var game_container_inner = $('#avcms-game-container-inner');

        var original_width = game_container.data('original-width');
        var original_height = game_container.data('original-height');

        if (game_container.width() < original_width) {
            game_container_inner.css('width', '100%');

            var new_height = original_height / original_width * game_container_inner.width();
            game_container_inner.css('height', new_height);
        }
        else {
            game_container_inner.css('width', original_width);
            game_container_inner.css('height', original_height);
        }
    }
};
