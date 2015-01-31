avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', avcms.updates.check);
});

avcms.updates = {
    check: function() {
        if ($('.avcms-update-loading').length === 0) {
            return;
        }

        $.get(avcms.config.site_url + 'admin/update-checker', function(data) {
            console.log(data);

            if (data.update_available === true) {
                var btn = $('.avcms-update-version-button');
                btn.html(btn.html() + ' ' + data.version);

                $('.avcms-update-notes').html(data.release_info);
                $('.avcms-update-release-notes-button').attr('href', data.forum_thread);
                var ver_info = $('.avcms-update-version-info');
                ver_info.html(ver_info.html() + ': ' + data.version);

                $('.avcms-update-loading').hide();
                $('.avcms-update-info').slideDown();
            }
            else if (data.update_available === null) {
                $('.avcms-update-loading').hide();
                $('.avcms-update-error').html(data.status_message);
            }
            else {
                $('.avcms-no-update').show();
                $('.avcms-update-loading').hide();
            }
        }, 'json');
    }
};