var avcms = avcms || {};

$(document).ready(function() {
    $('body').on('click', '[data-category-delete-url]', avcms.categories.openDeleteCategoryForm);
    $('body').on('click', '[data-reorder-categories-url]', avcms.categories.alphabeticallyReorderCategories);

    avcms.event.addEvent('submit-form-success', avcms.categories.onCategoryDeleted);

    avcms.event.addEvent('submit-form', function(form) {
        if (!form.data('edit-category')) {
            return;
        }

        avcms.categories.edit_name = form.find('[name=name]').val();
    });

    avcms.event.addEvent('submit-form-success', avcms.categories.refreshCategorySelects);
});

avcms.categories = {
    edit_name: null,

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
    },

    refreshCategorySelects: function(form, submit_data) {
        if (!form.data('edit-category')) {
            return;
        }

        var selects = $('select[name=category_id]');
        var category_name = avcms.categories.edit_name;

        var existing = selects.find('option[value='+submit_data.id+']');
        if (existing.length) {
            existing.text(category_name);
        }
        else {
            var option = '<option value="' + submit_data.id + '">' + category_name + '</option>';
            selects.append(option);
        }
    }
};
