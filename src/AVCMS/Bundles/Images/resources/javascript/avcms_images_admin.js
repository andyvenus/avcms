avcms = avcms || {};

$(document).ready(function() {
    var body = $('body');
    body.on('click', '.avcms-add-image-file', function() {
        avcms.images.addImageFile($(this).parents('form'));
    });
    body.on('click', '.avcms-remove-image-file', avcms.images.removeImageFile);

    avcms.event.addEvent('page-modified', function() {
        var container = $('.avcms-image-files').filter(':visible');
        if (container.length != 0 && container.find('.panel').length == 0) {
            var form = $('form[data-item-id]').filter(':visible');

            avcms.images.addImageFile(form);
        }

        $('.avcms-image-files').sortable();
    });
});

avcms.images = {
    newFileCount: 0,

    addImageFile: function(form) {
        var file_fields = form.find('.avcms-new-image').first().html();

        avcms.images.newFileCount++;

        $('.avcms-image-files').filter(':visible').append(file_fields.split('new-files').join('new-'+avcms.images.newFileCount+''));

        avcms.nav.onPageModified();
    },

    removeImageFile: function() {
        if ($('.avcms-image-files').filter(':visible').find('.panel').length == 1) {
            alert('There must be at least one image file');
            return;
        }

        $(this).parents('.panel').remove();
    }
};
