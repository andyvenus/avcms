avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', avcms.ckeditor.load);
});

avcms.ckeditor = {
    load: function() {
        $('[data-html-editor]').ckeditor();
    }
};