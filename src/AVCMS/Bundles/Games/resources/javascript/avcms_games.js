avcms = avcms || {};

$(document).ready(function() {
    avcms.games.onPageLoad();
    avcms.event.addEvent('page-modified', avcms.games.onPageLoad);
});

avcms.games = {
    countdown: null,
    countdown_time: 30,

    onPageLoad: function() {
        if ($('#avcms-game-container').length > 0) {
            avcms.event.addEvent('window-resize', avcms.games.resizeGame);
            avcms.games.resizeGame();

            avcms.games.showAd();

            $('#avcms-game-advert-skip').find('button').click(avcms.games.showGame);
            $('#avcms-game-fullscreen').click(avcms.games.goFullscreen);
        }

        if ($('[name=mobile_only]').length > 0) {
            avcms.games.hideMobileFilter();
            avcms.event.addEvent('window-resize', avcms.games.hideMobileFilter);
        }

        avcms.event.addEvent('window-resize', avcms.games.squareThumbnails);
        avcms.games.squareThumbnails();

        if (!avcms.games.browserHasFlash()) {
            $('#flash-not-enabled').show();
        }
    },

    squareThumbnails: function() {
        var imgs = $('.layout-games-thumbnail.square');

        imgs.each(function() {
            $(this).find('img').height($(this).width());
        });
    },

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
        if (avcms.gamesAdmin !== undefined) {
            return;
        }

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
    },

    hideMobileFilter: function() {
        var field = $('[name=mobile_only]').parents('.checkbox');
        if ($(document).width() > 768) {
            field.hide();
        }
        else {
            field.show();
        }
    },

    browserHasFlash: function() {
        var hasFlash = false;
        try {
            var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
            if (fo) {
                hasFlash = true;
            }
        } catch (e) {
            if (navigator.mimeTypes
                && navigator.mimeTypes['application/x-shockwave-flash'] != undefined
                && navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin) {
                hasFlash = true;
            }
        }

        return hasFlash;
    }
};
