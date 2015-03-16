avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('comment-submit-success', avcms.points.getNotification);
    avcms.event.addEvent('content-rated', function(vote_val) {
        if (vote_val != 'unvote') {
            avcms.points.getNotification();
        }
    });
    avcms.event.addEvent('submit-form-success', function(form) {
        if (form.attr('name') == 'report-form') {
            avcms.points.getNotification();
        }
    });

    if ($('#avcms-game-container').length > 0) {
        setTimeout(function() {avcms.points.addPoints('game_points')}, 1200000);
    }
});

avcms.points = {
    addPoints: function(type) {
        $.get(avcms.config.site_url + 'add-points', {type: type}, avcms.points.showNotification, 'json');
    },

    getNotification: function() {
        $.get(avcms.config.site_url + 'points-notification', avcms.points.showNotification, 'json');
    },

    showNotification: function(data) {
        if (data.success) {
            $.notify(data.message, {position: "right top", className: 'info'});

            if (typeof(data.points) !== 'undefined') {
                var span = $('.avcms-active-user-points');
                var current_points = parseInt(span.first().text(), 10) + parseInt(data.points, 10);

                span.text(current_points);
            }
        }
    }
};
