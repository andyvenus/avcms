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

        avcms.event.fireEvent('sortable-save-order', [$(this)]);

        $.ajax(url, {
            data: serialized,
            type: 'POST'
        })
    },

    deleteItem: function()
    {
        var button = $(this);
        var url = button.closest('.sortable').data('ajax-delete-action');
        var li = button.closest('li');
        var id = li.data('item-id');

        if (confirm('Are you sure you want to delete this item?')) {
            $.post(url, {ids: id});

            avcms.event.fireEvent('browser-delete-item', [button]);

            li.remove();
        }
    }
}
