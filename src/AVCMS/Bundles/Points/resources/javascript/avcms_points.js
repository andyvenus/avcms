avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('comment-submit-success', avcms.points.getNotification);
});

avcms.points = {
    getNotification: function() {
        $.get(avcms.config.site_url + 'points-notification', function(data) {
            if (data.success) {
                $.notify(data.message, {position: "right top", className: 'info'});
            }
        }, 'json');
    }
};
