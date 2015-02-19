avcms = avcms || {};

$(document).ready(function () {
    $('body').on('click', '.avcms-reset-template', function() {
        var finder_item = $(this).parents('[data-bundle]');

        avcms.templates.resetTemplate(finder_item.data('bundle'), finder_item.data('id'));
    });

    avcms.event.addEvent('submit-form-success', avcms.templates.triggerAssetRegen);
});

avcms.templates = {
    resetTemplate: function(bundle, file) {
        if (confirm(avcms.general.trans('Are you sure you want to reset this template?'))) {
            $.post(avcms.config.site_url + 'admin/templates/reset', {bundle: bundle, file: file}, function () {
                $('[data-id="' + file + '"]').find('.avcms-reset-template').remove();

                avcms.nav.refreshSection('.ajax-editor-inner', 'editor');

                if (file.indexOf('.css') !== -1 || file.indexOf('.js') !== -1) {
                    setTimeout(regenerateAssets, 300);
                }
            });
        }
    },

    triggerAssetRegen: function(form) {
        if (form.attr('name') != 'edit-template-form') {
            return;
        }

        $('.editor-content').filter(':visible').find('.alert[data-bundle]').show();

        if (form.data('item-id').indexOf('.css') !== -1 || form.data('item-id').indexOf('.js') !== -1) {
            regenerateAssets();
        }
    }
};
