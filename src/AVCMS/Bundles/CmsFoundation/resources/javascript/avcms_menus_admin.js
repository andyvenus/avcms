var avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('change', '[name=menu_item_form] [name=type]', avcms.menu.updateFormFields);
    avcms.menu.updateFormFields();

    avcms.event.addEvent('sortable-save-order', avcms.menu.reorderMenuItemsEvent);
    avcms.event.addEvent('browser-toggle-published', avcms.menu.reorderMenuItemsEvent);
    avcms.event.addEvent('browser-delete-item', avcms.menu.reorderMenuItemsEvent);
    avcms.event.addEvent('submit-form-success', function(form) {
        if (form.attr('name', 'menu_item_form')) {
            avcms.admin.reloadMenu();
        }
    });
});

avcms.menu = {
    updateFormFields: function() {
        var field = $('[name=menu_item_form] [name=type]');

        if (field.val() == 'category') {
            $('[name=target]').closest('.form-group').hide();
        }
        else {
            $('[name=target]').closest('.form-group').show();
        }
    },

    reorderMenuItemsEvent: function(element) {
        console.log('f');
        if (element.parents('.avcms-menus-sortable').length > 0) {
            console.log('b');
            avcms.admin.reloadMenu();
        }
    }
};
