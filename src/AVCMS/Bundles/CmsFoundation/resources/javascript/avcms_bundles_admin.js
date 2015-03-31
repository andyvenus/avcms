avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '.avcms-toggle-bundle', avcms.bundles.toggleBundle);
});

avcms.bundles = {
    toggleBundle: function() {
        var button = $(this);

        var enable = 1;
        if (button.hasClass('btn-success')) {
            enable = 0;
        }

        var bundle = button.parents('[data-bundle]').data('bundle');

        button.html(avcms.general.loaderImg);

        $.post(avcms.config.site_url + 'admin/bundles/toggle', {enable: enable, bundle: bundle}, function() {
            button.toggleClass('btn-success btn-danger');
            if (button.hasClass('btn-danger')) {
                button.text('Disabled');
            }
            else {
                button.text('Enabled');
            }
        }, 'json');
    }
};
