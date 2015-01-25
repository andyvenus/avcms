avcms = avcms || {};

$(document).ready(function () {
    avcms.wallpapers.selectCurrentResolution();
});

avcms.wallpapers = {
    selectCurrentResolution: function() {
        var dpr = window.devicePixelRatio || 0;

        var resolution = (screen.width * dpr)+'x'+(screen.height * dpr);

        var res = $('a[data-resolution="'+resolution+'"]').first();

        if (res.length !== 1) {
            $('.user-resolution').hide();
            return;
        }

        $('.current-res-link').attr('href', res.attr('href')).text(resolution);
    }
};
