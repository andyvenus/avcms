var avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '[data-category-delete-url]', avcms.reports.openDeleteCategoryForm);
});

avcms.categories = {
    openDeleteCategoryForm: function() {
        var button = $(this);

        var delete_category_url = button.data('category-delete-url');

        avcms.general.loadFormModal(delete_category_url);
    },

    onCategoryDeleted: function() {

    }
};
