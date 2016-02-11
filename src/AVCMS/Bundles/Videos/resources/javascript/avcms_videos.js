avcms = avcms || {};

$(document).ready(function() {
    avcms.videos.onPageLoad();
    avcms.event.addEvent('page-modified', avcms.videos.onPageLoad);
});

avcms.videos = {
    countdown: null,
    countdown_time: 30,

    onPageLoad: function() {
        if ($('#avcms-video-container').length > 0) {
            avcms.videos.showAd();

            $('#avcms-video-advert-skip').find('button').click(avcms.videos.showVideo);
        }
    },

    showAd: function() {
        if (avcms.videosAdmin !== undefined) {
            return;
        }

        var ad_container = $('#avcms-video-advert');
        if (ad_container.length == 0) {
            return;
        }

        var video_container = $('#avcms-video-container');
        var countdown_container = $('#avcms-video-advert-countdown');

        video_container.hide();
        ad_container.show();

        avcms.videos.countdown_time = countdown_container.data('countdown-time');

        if (!isNaN(avcms.videos.countdown_time) && avcms.videos.countdown_time !== 0) {
            avcms.videos.countdown = setInterval(avcms.videos.adCountdown, 1000);
        }
        else {
            countdown_container.text('');
        }
    },

    adCountdown: function() {
        avcms.videos.countdown_time = avcms.videos.countdown_time -1;
        if (avcms.videos.countdown_time <= 0) {
            avcms.videos.showVideo();

            return;
        }

        $('#avcms-video-advert-countdown').text(avcms.videos.countdown_time);
    },

    showVideo: function() {
        clearInterval(avcms.videos.countdown);

        $('#avcms-video-container').show();
        $('#avcms-video-advert').hide();
    }
};
