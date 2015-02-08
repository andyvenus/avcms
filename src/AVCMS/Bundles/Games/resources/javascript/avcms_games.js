avcms = avcms || {};

$(document).ready(function() {
    if ($('#avcms-game-container').length > 0) {
        window.onresize = avcms.games.resizeGame;
        avcms.games.resizeGame();

        avcms.games.showAd();

        $('#avcms-game-advert-skip').find('button').click(avcms.games.showGame);
        $('#avcms-game-fullscreen').click(avcms.games.goFullscreen);
    }
});

avcms.games = {
    countdown: null,
    countdown_time: 30,

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
    },

    showAd: function() {
        var ad_container = $('#avcms-game-advert');
        if (ad_container.length == 0) {
            return;
        }

        var game_container = $('#avcms-game-container');
        var countdown_container = $('#avcms-game-advert-countdown');

        game_container.hide();
        ad_container.show();

        avcms.games.countdown_time = countdown_container.data('countdown-time');

        if (!isNaN(avcms.games.countdown_time) && avcms.games.countdown_time !== 0) {
            avcms.games.countdown = setInterval(avcms.games.adCountdown, 1000);
        }
        else {
            countdown_container.text('');
        }
    },

    adCountdown: function() {
        avcms.games.countdown_time = avcms.games.countdown_time -1;
        if (avcms.games.countdown_time <= 0) {
            avcms.games.showGame();

            return;
        }

        $('#avcms-game-advert-countdown').text(avcms.games.countdown_time);
    },

    showGame: function() {
        clearInterval(avcms.games.countdown);

        $('#avcms-game-container').show();
        $('#avcms-game-advert').hide();
    },

    goFullscreen: function() {
        var game_container = document.getElementById("avcms-game-container");

        if (game_container.requestFullscreen) {
            game_container.requestFullscreen();
        } else if (game_container.webkitRequestFullscreen) {
            game_container.webkitRequestFullscreen();
        } else if (game_container.mozRequestFullScreen) {
            game_container.mozRequestFullScreen();
        } else if (game_container.msRequestFullscreen) {
            game_container.msRequestFullscreen();
        }
    }
};
