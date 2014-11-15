avcms = avcms || {};

$(document).ready(function() {
    $('#search_form').submit(avcms.frontend.search);

    avcms.frontend.updateSearchForm();
});

avcms.frontend = {
    search: function() {
        var search_url = $(this).find('[name=content_type]').val();

        window.location.href = search_url + '?search=' + $(this).find('[name=search]').val();

        return false;
    },

    updateSearchForm: function() {
        console.log('f');
        var search_param = avcms.general.getUrlParameter('search');
        if (search_param !== null) {
            $('#search_form').find('[name=search]').val(search_param);
        }
    }
};