avcms = avcms || {};

$(document).ready(function () {
    avcms.wallpapers.selectCurrentResolution();
});

avcms.wallpapers = {
    selectCurrentResolution: function() {
        var dpr = window.devicePixelRatio || 0;

        var resolution = (screen.width * dpr)+'x'+(screen.height * dpr);

        console.log("Detected resolution: " + resolution);

        var res = $('a[data-resolution="'+resolution+'"]').first();

        if (res.length !== 1) {
            $('.user-resolution').hide();
        } else {
            $('.current-res-link').attr('href', res.attr('href')).text(resolution);
        }

        var browseRes = $('a[data-browse-resolution="'+resolution+'"]').first();

        $('.current-res-browse-link').attr('href', browseRes.attr('href')).text(resolution);
    }
};
