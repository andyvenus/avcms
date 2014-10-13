var avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');

    body.on('change', '[name=menu_item_form] [name=type]', avcms.menu.updateFormFields);
    avcms.menu.updateFormFields();
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
    }
};