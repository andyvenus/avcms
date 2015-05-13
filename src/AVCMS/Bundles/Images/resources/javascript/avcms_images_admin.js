avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '.avcms-add-image-file', avcms.images.addImageFile);
});

avcms.images = {
    newFileCount: 0,

    addImageFile: function() {
        var file_fields = $(this).parents('form').find('.avcms-new-image').first().html();

        avcms.images.newFileCount++;

        $('.avcms-image-files').append(file_fields.split('[new]').join('[new-'+avcms.images.newFileCount+']'));

        avcms.nav.onPageModified();
    }
};
