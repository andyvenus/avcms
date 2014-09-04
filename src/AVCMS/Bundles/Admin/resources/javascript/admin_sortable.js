var avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', function() {
        var sortable = $('.sortable');
        sortable.nestedSortable({
            listType: 'ul',
            items: 'li',
            toleranceElement: '> div',
            handle: '.handle',
            maxLevels: 2,
            update: avcms.sortable.saveOrder
        });

        $('.sortable-container').innerHeight(sortable.innerHeight());
    });
});

avcms.sortable = {
    saveOrder: function() {
        var url = $(this).data('ajax-action');
        var serialized = $(this).nestedSortable('serialize');

        $.ajax(url, {
            data: serialized,
            type: 'POST'
        })
    }
}