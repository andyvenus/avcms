var avcms = avcms || {};

$(document).ready(function() {
    avcms.event.addEvent('page-modified', function() {
        var sortable = $('.sortable');

        var levels = $('.sortable').data('sortable-levels');

        sortable.nestedSortable({
            listType: 'ul',
            items: 'li',
            toleranceElement: '> div',
            handle: '.handle',
            maxLevels: levels,
            update: avcms.sortable.saveOrder
        });

        $('.sortable-container').innerHeight(sortable.innerHeight());
    });

    $('body').on('click', '.sortable .delete-item', avcms.sortable.deleteItem);
});

avcms.sortable = {
    saveOrder: function() {
        var url = $(this).data('ajax-action');
        var serialized = $(this).nestedSortable('serialize');

        $.ajax(url, {
            data: serialized,
            type: 'POST'
        })
    },

    deleteItem: function()
    {
        var url = $(this).closest('.sortable').data('ajax-delete-action');
        var li = $(this).closest('li');
        var id = li.data('item-id');

        if (confirm('Are you sure you want to delete this item?')) {
            $.post(url, {ids: id});
            li.remove();
        }
    }
}
