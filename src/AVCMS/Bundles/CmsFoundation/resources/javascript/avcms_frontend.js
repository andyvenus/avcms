avcms = avcms || {};

$(document).ready(function() {
    $('#search_form, .avcms-search-form').submit(avcms.frontend.search);

    $('body').on('submit', '[data-ajax-form]', avcms.form.submitForm);

    avcms.frontend.updateSearchForm();
});

avcms.frontend = {
    search: function() {
        var search_url = $(this).find('[name=content_type]').val();

        window.location.href = search_url + '?search=' + $(this).find('[name=search]').val();

        return false;
    },

    updateSearchForm: function() {
        var search_param = avcms.general.getUrlParameter('search');
        if (search_param !== null) {
            $('#search_form').find('[name=search]').val(search_param);
        }
    }
};
