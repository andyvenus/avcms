var avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '[data-category-delete-url]', avcms.categories.openDeleteCategoryForm);

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
    }
};
