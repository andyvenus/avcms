var avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '[data-category-delete-url]', avcms.categories.openDeleteCategoryForm);
    $('body').on('click', '[data-reorder-categories-url]', avcms.categories.alphabeticallyReorderCategories);

    avcms.event.addEvent('submit-form-success', avcms.categories.onCategoryDeleted);
});

avcms.categories = {
    openDeleteCategoryForm: function() {
        var button = $(this);

        var delete_category_url = button.data('category-delete-url');

        avcms.general.loadFormModal(delete_category_url);
    },

    onCategoryDeleted: function(form) {
        if (form.attr('name') == 'category_delete_form') {
            avcms.nav.refreshSection('.ajax-editor-inner', 'editor');
        }
    },

    alphabeticallyReorderCategories: function() {
        if (confirm('Are you sure you want to reorder the categories alphabetically?')) {
            $.post($(this).data('reorder-categories-url'), function() {
                avcms.nav.refreshSection('.ajax-editor-inner', 'editor');
            });
        }
    }
};
