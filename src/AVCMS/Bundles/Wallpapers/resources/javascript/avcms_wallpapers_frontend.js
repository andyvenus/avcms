avcms = avcms || {};

$(document).ready(function () {
    avcms.wallpapers.selectCurrentResolution();
});

avcms.wallpapers = {
    selectCurrentResolution: function() {
        var resolution = screen.width+'x'+screen.height;

        var res = $('a[data-resolution="'+resolution+'"]').first();

        if (res.length !== 1) {
            $('.user-resolution').hide();
            return;
        }

        $('.current-res-link').attr('href', res.attr('href')).text(resolution);
    }
};
