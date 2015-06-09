avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('click', '.clear-wallpaper-cache', avcms.wallpapers.clearWallpapersCache);

    avcms.event.addEvent('submit-form-success', avcms.wallpaper_submissions.onReviewSuccess);
});

avcms.wallpapers = {
    clearWallpapersCache: function()
    {
        if (confirm(avcms.general.trans('Are you sure you want to clear the wallpaper cache? This may cause some temporary strain on your server when images need to be re-generated.'))) {
            $.post(avcms.config.site_url+ 'admin/wallpapers/clear-cache', '', function(data) {
                if (data.success !== true) {
                    alert(data.error);
                }
            });
        }
    }
};

avcms.wallpaper_submissions = {
    onReviewSuccess: function(form)
    {
        if (form.hasClass('review-wallpaper-submission')) {
            $('.browser-finder-item[data-id="'+form.data('item-id')+'"]').remove();
        }
    }
};
