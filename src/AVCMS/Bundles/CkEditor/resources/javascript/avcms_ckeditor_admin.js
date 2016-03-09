avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', avcms.ckeditor.load);
});

avcms.ckeditor = {
    load: function() {
        var ua = navigator.userAgent.toLowerCase();
        var isAndroid = ua.indexOf("android") > -1;

        if (!isAndroid) {
            $('[data-html-editor]').ckeditor({
                skin: 'bootstrapck'
            });
        }
    }
};
